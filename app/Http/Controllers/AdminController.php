<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showDashboard()
    {
        $totalPeminjam = User::where('role', 'user')->count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalAlat = Alat::count();

        $alatTersedia = Alat::sum('jumlah');

        $peminjamanAktif = Peminjaman::where('status', 'approved')->count();

        $recentLoans = Peminjaman::with(['user', 'alat'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dasboard', compact(
            'totalPeminjam',
            'totalPetugas',
            'totalAlat',
            'alatTersedia',
            'peminjamanAktif',
            'recentLoans'
        ));
    }

    public function showDenda()
    {
        $dendaList = Peminjaman::with(['user', 'alat', 'dendaSetter'])
            ->where('denda_amount', '>', 0)
            ->latest()
            ->paginate(15);

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $dendaSummary = Peminjaman::with('dendaSetter')
            ->where('denda_amount', '>', 0)
            ->whereMonth('updated_at', $currentMonth)
            ->whereYear('updated_at', $currentYear)
            ->select('denda_set_by',
                DB::raw('SUM(denda_amount) as total_denda'),
                DB::raw('COUNT(*) as total_kasus'),
                DB::raw('MAX(updated_at) as terakhir'))
            ->groupBy('denda_set_by')
            ->get();

        $dendaMonthTotal = $dendaSummary->sum('total_denda');
        $dendaMonthCases = $dendaSummary->sum('total_kasus');

        return view('admin.denda', compact('dendaList', 'dendaSummary', 'dendaMonthTotal', 'dendaMonthCases'));
    }
}
