@extends('layouts.app')

@section('title', 'Daftar Alat')

@section('content')
<div class="container-fluid px-3 px-md-4 pb-5">
    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Daftar Alat</h4>
        <p class="text-muted small mb-0">
            Lihat daftar lengkap alat yang tersedia untuk dipinjam
        </p>
    </div>

    <!-- FILTER KATEGORI -->
    <div class="mb-4">
        <form method="GET" action="{{ route('user.alat-list') }}" class="d-flex gap-2 align-items-end flex-wrap">
            <div class="flex-grow-1" style="min-width: 250px;">
                <label for="kategori_id" class="form-label fw-medium">Filter berdasarkan Kategori</label>
                <select name="kategori_id" id="kategori_id" class="form-select">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel me-2"></i>Filter
            </button>
            @if(request('kategori_id'))
                <a href="{{ route('user.alat-list') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                </a>
            @endif
        </form>
    </div>

    <!-- DAFTAR ALAT -->
    @if($alat->count())
        <div class="row g-3">
            @foreach($alat as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <!-- GAMBAR ALAT -->
                        <div style="height: 200px; overflow: hidden; background-color: #f8f9fa;">
                            @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}"
                                    alt="{{ $item->nama }}"
                                    class="img-fluid w-100 h-100"
                                    style="object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center w-100 h-100">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                        </div>

                        <!-- KONTEN KARTU -->
                        <div class="card-body">
                            <h6 class="card-title fw-bold mb-2">{{ $item->nama }}</h6>

                            <!-- KATEGORI -->
                            <div class="mb-2">
                                <span class="badge bg-secondary">
                                    {{ $item->kategori->nama ?? '-' }}
                                </span>
                            </div>

                            <!-- INFO ALAT -->
                            <dl class="row mb-3" style="font-size: 0.9rem;">
                                <dt class="col-6">Jumlah:</dt>
                                <dd class="col-6 fw-bold">{{ $item->jumlah }} pcs</dd>

                                <dt class="col-6">Keterangan:</dt>
                                <dd class="col-6 text-muted small">
                                    {{ Str::limit($item->keterangan ?? '-', 50) }}
                                </dd>
                            </dl>

                            <!-- STATUS KETERSEDIAAN -->
                            @if($item->jumlah > 0)
                                <div class="mb-3">
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Tersedia
                                    </span>
                                </div>
                            @else
                                <div class="mb-3">
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>Tidak Tersedia
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- TOMBOL AKSI -->
                        <div class="card-footer bg-white border-0 p-3">
                            @if($item->jumlah > 0)
                                <a href="{{ route('user.peminjaman.create') }}?alat_id={{ $item->id }}"
                                    class="btn btn-primary w-100 btn-sm">
                                    <i class="bi bi-hand-index me-2"></i>Pinjam
                                </a>
                            @else
                                <button class="btn btn-secondary w-100 btn-sm" disabled>
                                    <i class="bi bi-lock me-2"></i>Tidak Bisa Dipinjam
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center py-5" role="alert">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #0d6efd;"></i>
            <h5 class="mt-3 fw-bold">Tidak ada alat tersedia</h5>
            <p class="text-muted mb-0">Saat ini tidak ada alat yang dapat dipinjam.</p>
        </div>
    @endif
</div>

@endsection
