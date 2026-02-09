@extends('layouts.app')
@section('title', 'Dashboard Ringkasan')

@section('content')
{{-- Background abu-abu muda --}}
<div class="container-fluid py-4 min-vh-100" style="background-color: #f4f7f6;">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4 px-2">
        <div>
            <h4 class="fw-bold text-dark mb-0">Ringkasan Sistem</h4>
            <p class="text-muted small mb-0">Pantau data peminjam, petugas, dan inventaris alat.</p>
        </div>
        <div class="d-none d-md-block">
            <span class="badge bg-primary px-3 py-2 shadow-sm rounded-pill">
                <i class="bi bi-calendar3 me-2"></i>{{ date('d M Y') }}
            </span>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-4">

        {{-- Total Peminjam --}}
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 border-top border-primary border-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-secondary text-uppercase fw-bold small mb-2">Total Peminjam</h6>
                            <h2 class="fw-bold mb-0">{{ $totalPeminjam }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.peminjam') }}" class="text-primary small fw-medium text-decoration-none">
                            Lihat detail <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Petugas --}}
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 border-top border-info border-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-secondary text-uppercase fw-bold small mb-2">Total Petugas</h6>
                            <h2 class="fw-bold mb-0">{{ $totalPetugas }}</h2>
                        </div>
                        <div class="bg-info bg-opacity-10 text-info rounded-circle p-3">
                            <i class="bi bi-person-badge fs-3"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.petugas') }}" class="text-info small fw-medium text-decoration-none">
                            Kelola petugas <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Alat --}}
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 border-top border-success border-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-secondary text-uppercase fw-bold small mb-2">Total Alat</h6>
                            <h2 class="fw-bold mb-0">{{ $totalAlat }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="bi bi-box-seam fs-3"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $alatTersedia }} Total</span>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle ms-1">{{ $peminjamanAktif }} Dipinjam</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- TABLE RIWAYAT PEMINJAMAN --}}
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">

                {{-- Header Tabel --}}
                <div class="card-header bg-dark py-3 d-flex align-items-center justify-content-between border-0">
                    <h5 class="fw-bold text-white mb-0">Riwayat Peminjaman Terbaru</h5>
                </div>

                <div class="card-body p-0">
                    @if(isset($recentLoans) && $recentLoans->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">
                                    <tr>
                                        <th class="py-3 small text-uppercase fw-bold text-muted">User</th>
                                        <th class="py-3 small text-uppercase fw-bold text-muted">Alat</th>
                                        <th class="py-3 small text-uppercase fw-bold text-muted text-center">Mulai</th>
                                        <th class="py-3 small text-uppercase fw-bold text-muted text-center">Kembali</th>
                                        <th class="py-3 small text-uppercase fw-bold text-muted text-center">Status</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white">
                                    @foreach($recentLoans as $loan)
                                        <tr>
                                            <td class="fw-bold text-dark">
                                                {{ $loan->user->name ?? 'User#'.$loan->user_id }}
                                                <div class="small text-muted">{{ $loan->user->email ?? '' }}</div>
                                            </td>
                                            <td class="text-muted">
                                                {{ $loan->alat->nama ?? $loan->alat->nama_alat ?? 'Alat#'.$loan->alat_id }}
                                            </td>
                                            <td class="text-center text-muted">
                                                {{ \Carbon\Carbon::parse($loan->tanggal_mulai)->format('d M Y') }}
                                            </td>
                                            <td class="text-center text-muted">
                                                {{ \Carbon\Carbon::parse($loan->tanggal_selesai)->format('d M Y') }}
                                            </td>
                                            <td class="text-center px-4">
                                                @if($loan->status === 'pending')
                                                    <span class="badge bg-warning-subtle text-warning px-3">Pending</span>
                                                @elseif($loan->status === 'approved')
                                                    <span class="badge bg-success-subtle text-success px-3">Disetujui</span>
                                                @elseif($loan->status === 'returned')
                                                    <span class="badge bg-primary-subtle text-primary px-3">Dikembalikan</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger px-3">Ditolak</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    @else
                        <div class="p-4">
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle me-2"></i>Tidak ada riwayat peminjaman.
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
