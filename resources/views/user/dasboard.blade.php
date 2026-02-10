@extends('layouts.app')
@section('title', 'Dashboard User')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <!-- HEADER -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(120deg,#e8f1ff,#f7fbff);">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <p class="text-muted small mb-1">Selamat datang kembali</p>
                <h4 class="fw-bold mb-1">Halo, {{ auth()->user()->name }}!</h4>
                <p class="text-muted small mb-0">Ringkasan aktivitas peminjaman Anda</p>
            </div>
            <span class="badge bg-primary text-white px-3 py-2 rounded-pill">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->format('d M Y') }}
            </span>
        </div>
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

        <!-- MENUNGGU VERIFIKASI PENGEMBALIAN -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-uppercase text-muted fw-semibold">
                                Pengembalian Menunggu
                            </small>
                            <h2 class="fw-bold mt-1 mb-0">
                                {{ $returnRequested ?? 0 }}
                            </h2>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info p-3 rounded-circle">
                            <i class="bi bi-arrow-repeat fs-4"></i>
                        </span>
                    </div>

                    <a href="{{ route('user.pengembalian') }}"
                       class="d-inline-flex align-items-center gap-1 text-info small fw-medium mt-3 text-decoration-none">
                        Lihat pengembalian <i class="bi bi-arrow-right"></i>
                    </a>
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

                </div>
            </div>
        </div>

    </div>

    <!-- QUICK LINKS -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">
                                Menu Cepat
                            </h6>
                            <p class="text-muted small mb-0">
                                Navigasi ke halaman penting dengan cepat
                            </p>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info p-3 rounded-circle">
                            <i class="bi bi-lightning-charge fs-4"></i>
                        </span>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('user.peminjaman') }}"
                           class="btn btn-primary btn-sm">
                            <i class="bi bi-bag-plus me-1"></i> Katalog & Ajukan
                        </a>
                        <a href="{{ route('user.pengembalian') }}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-repeat me-1"></i> Pengembalian
                        </a>
                        <a href="{{ route('log.aktivitas') }}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-clock-history me-1"></i> Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
