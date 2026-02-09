@extends('layouts.app')
@section('title','Peminjaman')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Katalog Alat</h4>
        <p class="text-muted small mb-0">
            Temukan alat yang tersedia, lalu lanjutkan ke form pengajuan
        </p>
    </div>

    <!-- ALERT SUCCESS -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- ALERT ERROR -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>
            <strong>Terjadi Kesalahan</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-search text-muted"></i>
                        <input type="text" id="cari-alat" class="form-control form-control-sm" placeholder="Cari alat..." style="min-width: 220px;">
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-filter text-muted"></i>
                        <select id="filter-kategori" class="form-select form-select-sm" style="min-width: 200px;">
                            <option value="">Semua kategori</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($alats->isEmpty())
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>Belum ada alat yang tersedia.
                        </div>
                    @else
                        <div class="row g-3" id="list-alat">
                            @foreach($alats as $alat)
                                @php
                                    $fotoAlat = $alat->gambar ? asset('storage/'.$alat->gambar) : null;
                                @endphp
                                <div class="col-12 col-md-6 col-xl-4 alat-item"
                                     data-nama="{{ Str::lower($alat->nama ?? $alat->nama_alat ?? 'Alat #'.$alat->id) }}"
                                     data-kategori="{{ $alat->kategori_id }}">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="ratio ratio-4x3 bg-light rounded-top overflow-hidden">
                                            <img src="{{ $fotoAlat ?? '' }}"
                                                 alt="Foto {{ $alat->nama ?? 'Alat' }}"
                                                 class="w-100 h-100"
                                                 style="object-fit: cover;"
                                                 onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22600%22 height=%22400%22><rect width=%22100%%22 height=%22100%%22 fill=%22%23f8f9fa%22/><text x=%2250%%22 y=%2250%%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%236c757d%22 font-size=%2220%22 font-family=%22Arial%2C sans-serif%22>Tidak ada foto</text></svg>';">
                                        </div>
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-1">
                                                {{ $alat->nama ?? $alat->nama_alat ?? 'Alat #'.$alat->id }}
                                            </h6>
                                            <p class="small text-muted mb-2">
                                                {{ $alat->kategori->nama ?? 'Tanpa kategori' }}
                                            </p>
                                            @php
                                                $tersisa = max(0, ($alat->jumlah ?? 0) - ($alat->dipinjam_count ?? 0));
                                            @endphp
                                            <p class="small mb-2">
                                                <span class="badge bg-light text-dark border">
                                                    Stok tersisa: {{ $tersisa }}
                                                </span>
                                            </p>
                                            @if($alat->keterangan)
                                                <p class="small text-muted mb-3">
                                                    {{ Str::limit($alat->keterangan, 120) }}
                                                </p>
                                            @endif
                                            <a href="{{ route('user.peminjaman.create', ['alat_id' => $alat->id]) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-bag-plus me-1"></i>Pinjam alat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT FILTER ALAT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cariAlat = document.getElementById('cari-alat');
    const listAlatItems = document.querySelectorAll('.alat-item');
    const filterKategori = document.getElementById('filter-kategori');

    cariAlat?.addEventListener('input', (e) => {
        const keyword = e.target.value.toLowerCase();
        listAlatItems.forEach(item => {
            const nama = item.dataset.nama || '';
            const matchNama = nama.includes(keyword);
            const matchKategori = !filterKategori?.value || item.dataset.kategori === filterKategori.value;
            item.style.display = (matchNama && matchKategori) ? '' : 'none';
        });
    });

    filterKategori?.addEventListener('change', () => {
        const keyword = (cariAlat?.value || '').toLowerCase();
        listAlatItems.forEach(item => {
            const nama = item.dataset.nama || '';
            const matchNama = nama.includes(keyword);
            const matchKategori = !filterKategori.value || item.dataset.kategori === filterKategori.value;
            item.style.display = (matchNama && matchKategori) ? '' : 'none';
        });
    });
});
</script>

@endsection
