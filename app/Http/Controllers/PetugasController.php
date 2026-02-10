<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Alat;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    public function showDashboard()
    {
        $totalPending = Peminjaman::where('status', 'pending')->count();
        $totalApproved = Peminjaman::where('status', 'approved')->count();
        $totalRejected = Peminjaman::where('status', 'rejected')->count();
        
        return view('petugas.dashboard', compact(
            'totalPending',
            'totalApproved',
            'totalRejected'
        ));
    }

    public function showPeminjamanList()
    {
        $peminjamans = Peminjaman::with('user', 'alat')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('petugas.peminjaman_list', compact('peminjamans'));
    }

    public function showPengembalianList()
    {
        $peminjamans = Peminjaman::with('user', 'alat')
            ->whereIn('status', ['return_requested', 'returned'])
            ->orderBy('tanggal_selesai', 'asc')
            ->get();

        return view('petugas.pengembalian_list', compact('peminjamans'));
    }

    public function dendaList()
    {
        $base = Peminjaman::with(['user', 'alat'])
            ->where('denda_amount', '>', 0)
            ->where('denda_set_by', auth()->id());

        $dendaList = (clone $base)
            ->latest()
            ->paginate(15);

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthQuery = (clone $base)
            ->whereMonth('updated_at', $currentMonth)
            ->whereYear('updated_at', $currentYear);

        $summary = [
            'total_nominal' => $monthQuery->sum('denda_amount'),
            'total_kasus' => (clone $monthQuery)->count(),
            'belum_lunas' => (clone $monthQuery)->where('denda_status', 'unpaid')->count(),
            'lunas' => (clone $monthQuery)->where('denda_status', 'paid')->count(),
        ];

        $recentReasons = (clone $base)
            ->select('user_id', 'denda_reason', 'denda_amount', 'updated_at')
            ->whereNotNull('denda_reason')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('petugas.denda', compact('dendaList', 'summary', 'recentReasons'));
    }

    public function dendaPrint(Request $request)
    {
        $dendaList = Peminjaman::with(['user', 'alat'])
            ->where('denda_amount', '>', 0)
            ->where('denda_set_by', auth()->id())
            ->latest()
            ->get();

        $totals = [
            'nominal' => $dendaList->sum('denda_amount'),
            'kasus'   => $dendaList->count(),
        ];

        $view = view('petugas.denda_print', compact('dendaList', 'totals'));
        return $view;
    }

    public function updateDenda(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'denda_amount' => 'required|numeric|min:0',
            'denda_reason' => 'nullable|string|max:1000',
            'denda_status' => 'required|string|in:unpaid,paid,waived,none',
        ]);

        $peminjaman->update([
            'denda_amount' => $validated['denda_amount'],
            'denda_reason' => $validated['denda_reason'] ?? null,
            'denda_status' => $validated['denda_status'],
            'denda_set_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Denda berhasil diperbarui.');
    }

    public function approvePeminjaman($id)
    {
        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        if ($peminjaman->status !== 'pending') {
            return redirect()->back()->with('warning', 'Peminjaman tidak dalam status pending.');
        }

        $alat = $peminjaman->alat;
        if (!$alat) {
            return redirect()->back()->with('error', 'Alat tidak ditemukan.');
        }

        DB::transaction(function () use ($peminjaman, $alat) {
            // stok sudah dikurangi saat pengajuan, cukup set status
            $peminjaman->update(['status' => 'approved']);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Approve Peminjaman',
                'jumlah' => $peminjaman->jumlah,
                'tanggal_mulai' => $peminjaman->tanggal_mulai,
                'tanggal_selesai' => $peminjaman->tanggal_selesai,
                'description' => sprintf(
                    'Menyetujui peminjaman alat %s untuk user #%s',
                    $alat->nama ?? $alat->nama_alat ?? 'Alat#'.$alat->id,
                    $peminjaman->user_id
                ),
            ]);
        });

        return redirect()->back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function rejectPeminjaman(Request $request, $id)
    {
        $validated = $request->validate([
            'alasan' => 'required|string|max:500',
        ]);

        $peminjaman = Peminjaman::with('alat')->findOrFail($id);
        $alat = $peminjaman->alat;

        DB::transaction(function () use ($peminjaman, $validated, $alat) {
            // Kembalikan stok yang sudah di-hold saat pengajuan
            if ($alat) {
                $alat->jumlah += $peminjaman->jumlah;
                $alat->save();
            }

            $peminjaman->update([
                'status' => 'rejected',
                'keterangan' => $validated['alasan'],
            ]);
        });

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Reject Peminjaman',
            'jumlah' => $peminjaman->jumlah,
            'tanggal_mulai' => $peminjaman->tanggal_mulai,
            'tanggal_selesai' => $peminjaman->tanggal_selesai,
            'description' => sprintf(
                'Menolak peminjaman %s. Alasan: %s',
                $peminjaman->alat->nama ?? $peminjaman->alat->nama_alat ?? 'Alat#'.$peminjaman->alat_id,
                $validated['alasan']
            ),
        ]);

        return redirect()->back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function returnPeminjaman(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        if ($peminjaman->status !== 'return_requested') {
            return redirect()->back()->with('warning', 'Pengembalian belum diajukan oleh pengguna.');
        }

        $alat = $peminjaman->alat;
        if (!$alat) {
            return redirect()->back()->with('error', 'Alat tidak ditemukan.');
        }

        $validated = $request->validate([
            'status_barang' => 'required|string|in:baik,rusak,hilang,terlambat',
            'denda_amount' => 'nullable|numeric|min:0',
            'denda_reason' => 'nullable|string|max:1000',
            'denda_type' => 'nullable|string|max:50',
        ]);

        $hariTerlambat = 0;
        if ($peminjaman->tanggal_selesai) {
            $hariTerlambat = max(0, now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($peminjaman->tanggal_selesai), false) * -1);
        }

        $lateFinePerDay = 10000; // Rp10.000 per hari keterlambatan
        $autoLateFine = $hariTerlambat > 0 ? $hariTerlambat * $lateFinePerDay : 0;
        $finalFine = $validated['denda_amount'] ?? null;
        if ($finalFine === null || $finalFine === '') {
            $finalFine = $autoLateFine;
        }

        DB::transaction(function () use ($peminjaman, $alat, $validated, $hariTerlambat, $finalFine, $autoLateFine, $lateFinePerDay) {
            $alat->jumlah = $alat->jumlah + $peminjaman->jumlah;
            $alat->save();

            $peminjaman->update([
                'status' => 'returned',
                'kondisi_pengembalian' => $validated['status_barang'],
                'status_barang' => $validated['status_barang'],
                'denda_amount' => $finalFine,
                'denda_status' => ($finalFine ?? 0) > 0 ? 'unpaid' : 'none',
                'denda_reason' => $validated['denda_reason'] ?? null,
                'denda_type' => $validated['denda_type']
                    ?? ($hariTerlambat > 0 ? 'terlambat' : $validated['status_barang']),
                'denda_set_by' => auth()->id(),
                'keterlambatan_hari' => $hariTerlambat,
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Terima Pengembalian',
                'jumlah' => $peminjaman->jumlah,
                'tanggal_mulai' => $peminjaman->tanggal_mulai,
                'tanggal_selesai' => $peminjaman->tanggal_selesai,
                'description' => sprintf(
                    'Terima pengembalian %s. Kondisi: %s. Denda: Rp%s (terlambat %s hari).',
                    $peminjaman->alat->nama ?? $peminjaman->alat->nama_alat ?? 'Alat#'.$peminjaman->alat_id,
                    $validated['status_barang'],
                    number_format($finalFine ?? 0, 0, ',', '.'),
                    $hariTerlambat
                ),
            ]);
        });

        return redirect()->back()->with('success', 'Peminjaman ditandai telah dikembalikan dan stok diperbarui.');
    }
}
