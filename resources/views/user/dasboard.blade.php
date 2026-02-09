@extends('layouts.app')
@section('title', 'Dashboard User')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                Halo, {{ auth()->user()->name }} 👋
            </h4>
            <p class="text-muted small mb-0">
                Kelola peminjaman alat dengan mudah
            </p>
        </div>

        <span class="badge bg-primary text-white px-3 py-2 rounded-pill">
            <i class="bi bi-calendar3 me-1"></i>
            {{ now()->format('d M Y') }}
        </span>
    </div>

    <!-- STATISTIC CARDS -->
    <div class="row g-4 mb-4">

        <!-- PEMINJAMAN AKTIF -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-uppercase text-muted fw-semibold">
                                Peminjaman Aktif
                            </small>
                            <h2 class="fw-bold mt-1 mb-0">
                                {{ $activeLoans ?? 0 }}
                            </h2>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                            <i class="bi bi-box-seam fs-4"></i>
                        </span>
                    </div>

                    <a href="{{ route('user.peminjaman') }}"
                       class="d-inline-flex align-items-center gap-1 text-primary small fw-medium mt-3 text-decoration-none">
                        Lihat detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- MENUNGGU PERSETUJUAN -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-uppercase text-muted fw-semibold">
                                Menunggu Persetujuan
                            </small>
                            <h2 class="fw-bold mt-1 mb-0">
                                {{ $pendingLoans ?? 0 }}
                            </h2>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                            <i class="bi bi-clock-history fs-4"></i>
                        </span>
                    </div>

                    <span class="text-warning small fw-medium d-block mt-3">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Dalam proses
                    </span>
                </div>
            </div>
        </div>

        <!-- TOTAL PEMINJAMAN -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-uppercase text-muted fw-semibold">
                                Total Peminjaman
                            </small>
                            <h2 class="fw-bold mt-1 mb-0">
                                {{ $totalLoans ?? 0 }}
                            </h2>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success p-3 rounded-circle">
                            <i class="bi bi-check-circle fs-4"></i>
                        </span>
                    </div>

                    <span class="badge bg-success mt-3">
                        {{ $approvedLoans ?? 0 }} Disetujui
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- ACTION SECTION -->
    <div class="row g-4 mb-4">

        <!-- DAFTAR ALAT -->
        <div class="col-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">
                                Daftar Alat
                            </h6>
                            <p class="text-muted small mb-0">
                                Lihat daftar alat yang tersedia
                            </p>
                        </div>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary p-3 rounded-circle">
                            <i class="bi bi-tools fs-4"></i>
                        </span>
                    </div>

                    <a href="{{ route('user.alat-list') }}"
                       class="btn btn-secondary btn-sm">
                        <i class="bi bi-tools me-1"></i> Lihat Daftar
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- RIWAYAT PEMINJAMAN TERBARU -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-1">Riwayat Peminjaman Terbaru</h6>
                <p class="text-muted small mb-0">5 transaksi terakhir yang kamu ajukan</p>
            </div>
            <a href="{{ route('user.peminjaman') }}" class="btn btn-outline-primary btn-sm">
                Lihat semua
            </a>
        </div>
        @if($recentLoans->isEmpty())
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-2"></i>Belum ada riwayat peminjaman.
                </div>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted">
                            <th>#</th>
                            <th>Alat</th>
                            <th>Jumlah</th>
                            <th>Mulai</th>
                            <th>Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLoans as $index => $loan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $loan->alat->nama ?? $loan->alat->nama_alat ?? 'Alat #'.$loan->alat_id }}</strong>
                                </td>
                                <td>{{ $loan->jumlah }}</td>
                                <td>{{ \Carbon\Carbon::parse($loan->tanggal_mulai)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($loan->tanggal_selesai)->format('d/m/Y') }}</td>
                                <td>
                                    @if($loan->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($loan->status === 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($loan->status === 'returned')
                                        <span class="badge bg-primary">Dikembalikan</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
