<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function showDashboard()
    {
        $totalPeminjam = User::where('role', 'user')->count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalAlat = Alat::count();

        $alatTersedia = Alat::sum('jumlah');

        $peminjamanAktif = Peminjaman::where('status', 'approved')->count();

        return view('admin.dasboard', compact(
            'totalPeminjam',
            'totalPetugas',
            'totalAlat',
            'alatTersedia',
            'peminjamanAktif'
        ));
    }
}
