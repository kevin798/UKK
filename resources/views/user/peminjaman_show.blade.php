@extends('layouts.app')
@section('title', 'Detail Peminjaman')

@section('content')
<div class="container-fluid px-3 px-md-4 pb-5">
    <div class="mb-4">
        <a href="{{ route('user.peminjaman') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Detail Peminjaman #{{ $peminjaman->id }}</h6>
                        @if($peminjaman->status === 'pending')
                            <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Menunggu Persetujuan</span>
                        @elseif($peminjaman->status === 'approved')
                            <span class="badge bg-success"><i class="bi bi-check"></i> Disetujui</span>
                        @elseif($peminjaman->status === 'returned')
                            <span class="badge bg-primary"><i class="bi bi-arrow-clockwise"></i> Dikembalikan</span>
                        @else
                            <span class="badge bg-danger"><i class="bi bi-x"></i> Ditolak</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold small mb-2">Informasi Alat</h6>
                            <div class="mb-3">
                                <span class="d-block text-muted small">Nama Alat</span>
                                <p class="fw-bold">{{ $peminjaman->alat->nama ?? $peminjaman->alat->nama_alat ?? 'Alat #'.$peminjaman->alat_id }}</p>
                            </div>
                            <div>
                                <span class="d-block text-muted small">Kategori</span>
                                <p class="fw-bold">{{ $peminjaman->alat->kategori->nama ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold small mb-2">Jumlah Peminjaman</h6>
                            <div>
                                <span class="d-block text-muted small">Jumlah</span>
                                <p class="fw-bold text-primary" style="font-size: 24px;">{{ $peminjaman->jumlah }}</p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold small mb-2">Tanggal Peminjaman</h6>
                            <div class="mb-3">
                                <span class="d-block text-muted small">Mulai</span>
                                <p class="fw-bold">{{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('d F Y') }}</p>
                            </div>
                            <div>
                                <span class="d-block text-muted small">Kembali</span>
                                <p class="fw-bold">{{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold small mb-2">Durasi</h6>
                            <div>
                                <span class="d-block text-muted small">Estimasi</span>
                                <p class="fw-bold">
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($peminjaman->tanggal_selesai)) + 1 }} hari
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div>
                        <h6 class="text-muted text-uppercase fw-bold small mb-2">Keterangan Peminjaman</h6>
                        <p class="text-muted">
                            {{ $peminjaman->keterangan ?? 'Tidak ada keterangan tambahan' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom-0 py-3">
                    <h6 class="fw-bold mb-0">Informasi Peminjam</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <span class="d-block text-muted small mb-2">Nama</span>
                        <p class="fw-bold">{{ $peminjaman->user->name }}</p>
                    </div>
                    <div class="mb-4">
                        <span class="d-block text-muted small mb-2">Email</span>
                        <p class="text-break">{{ $peminjaman->user->email }}</p>
                    </div>
                    <hr>
                    <div>
                        <span class="d-block text-muted small mb-2">Tanggal Pengajuan</span>
                        <p class="small">{{ $peminjaman->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($peminjaman->status === 'rejected')
                <div class="alert alert-danger mt-3 border-start border-danger border-4">
                    <h6 class="fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Peminjaman Ditolak</h6>
                    <p class="mb-0 small mt-2">{{ $peminjaman->keterangan }}</p>
                </div>
            @elseif($peminjaman->status === 'pending')
                <div class="alert alert-warning mt-3 border-start border-warning border-4">
                    <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Sedang Diproses</h6>
                    <p class="mb-0 small mt-2">Pengajuan Anda sedang menunggu persetujuan dari petugas. Kami akan menginformasikan status terbaru segera.</p>
                </div>
            @elseif($peminjaman->status === 'approved')
                <div class="alert alert-success mt-3 border-start border-success border-4">
                    <h6 class="fw-bold"><i class="bi bi-check-circle me-2"></i>Peminjaman Disetujui</h6>
                    <p class="mb-0 small mt-2">Peminjaman Anda telah disetujui. Silakan ambil alat sesuai jadwal yang telah ditentukan.</p>

                    <!-- Form pengembalian oleh user -->
                    <form action="{{ route('user.peminjaman.return', $peminjaman->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Tandai peminjaman ini telah dikembalikan?')">
                        @csrf
                        <button class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-repeat me-1"></i> Saya telah mengembalikan
                        </button>
                    </form>
                </div>
            @elseif($peminjaman->status === 'returned')
                <div class="alert alert-primary mt-3 border-start border-primary border-4">
                    <h6 class="fw-bold"><i class="bi bi-arrow-clockwise me-2"></i>Peminjaman Dikembalikan</h6>
                    <p class="mb-0 small mt-2">Anda telah mengembalikan alat. Terima kasih.</p>
                </div>
            @endif

            @if(($peminjaman->denda_amount ?? 0) > 0)
                <div class="alert alert-warning mt-3 border-start border-warning border-4">
                    <h6 class="fw-bold mb-1"><i class="bi bi-cash-coin me-2"></i>Denda</h6>
                    <p class="mb-1 small">
                        Nominal: <strong>Rp {{ number_format($peminjaman->denda_amount, 0, ',', '.') }}</strong>
                        (Status: {{ strtoupper($peminjaman->denda_status ?? 'unpaid') }})
                    </p>
                    @if($peminjaman->keterlambatan_hari)
                        <p class="mb-1 small">Keterlambatan: {{ $peminjaman->keterlambatan_hari }} hari.</p>
                    @endif
                    @if($peminjaman->denda_reason)
                        <p class="mb-0 small text-muted">Catatan: {{ $peminjaman->denda_reason }}</p>
                    @endif

                    @if(($peminjaman->denda_status ?? 'unpaid') !== 'paid')
                        <form action="{{ route('user.peminjaman.pay-fine', $peminjaman->id) }}"
                              method="POST" class="mt-3"
                              onsubmit="return confirm('Tandai denda telah dibayar?')">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small mb-1">Catatan pembayaran (opsional)</label>
                                <input type="text" name="catatan_pembayaran" class="form-control form-control-sm"
                                       placeholder="Contoh: sudah transfer via bank">
                            </div>
                            <button class="btn btn-sm btn-success">
                                <i class="bi bi-check2-circle me-1"></i> Tandai Sudah Bayar
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
