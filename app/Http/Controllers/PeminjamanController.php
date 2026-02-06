<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PeminjamanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $peminjamans = Peminjaman::with('alat.kategori')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $alats = Alat::all();

        // ✅ TAMBAHKAN KATEGORI
        $kategori = Kategori::orderBy('nama')->get();

        return view('user.loans', compact('peminjamans', 'alats', 'kategori'));
    }

    public function create()
    {
        $alats = Alat::all();

        // ✅ TAMBAHKAN KATEGORI
        $kategori = Kategori::orderBy('nama')->get();

        return view('user.loans', compact('alats', 'kategori'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'kategori_id'     => ['required', 'exists:kategori,id'], // ✅ WAJIB
            'alat_id'         => ['required', 'exists:alats,id'],
            'jumlah'          => ['required', 'integer', 'min:1'],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'keterangan'      => ['nullable', 'string', 'max:1000'],
        ]);

        $alat = Alat::find($validated['alat_id']);
        if (!$alat) {
            return Redirect::back()
                ->withErrors(['alat_id' => 'Alat tidak ditemukan.'])
                ->withInput();
        }

        if ($validated['jumlah'] > $alat->jumlah) {
            return Redirect::back()
                ->withErrors(['jumlah' => 'Stok alat tidak mencukupi.'])
                ->withInput();
        }

        Peminjaman::create([
            'user_id'         => $user->id,
            'kategori_id'     => $validated['kategori_id'], // ✅ DISIMPAN
            'alat_id'         => $validated['alat_id'],
            'jumlah'          => $validated['jumlah'],
            'tanggal_mulai'   => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'keterangan'      => $validated['keterangan'] ?? null,
            'status'          => 'pending',
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'activity' => 'Ajukan Peminjaman',
            'jumlah' => $validated['jumlah'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'description' => sprintf(
                'Mengajukan peminjaman alat %s',
                $alat->nama ?? $alat->nama_alat ?? 'Alat#' . $alat->id
            ),
        ]);

        return redirect()
            ->route('user.peminjaman')
            ->with('success', 'Permintaan peminjaman berhasil dikirim.');
    }

    public function show($id)
    {
        $user = Auth::user();

        // ✅ EAGER LOAD AGAR KATEGORI TAMPIL
        $peminjaman = Peminjaman::with(['alat.kategori', 'kategori'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('user.peminjaman_show', compact('peminjaman'));
    }
}
