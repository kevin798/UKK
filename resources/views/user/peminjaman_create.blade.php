@extends('layouts.app')
@section('title','Ajukan Peminjaman')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Ajukan Peminjaman</h4>
            <p class="text-muted small mb-0">Lengkapi form di bawah untuk meminjam alat</p>
        </div>
        <a href="{{ route('user.peminjaman') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke katalog
        </a>
    </div>

    <div class="row g-4">
        <!-- FORM -->
        <div class="col-12 col-lg-8 col-xl-6 mx-auto">
            <div class="card border-0 shadow-sm h-100" id="form-peminjaman">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        Form Peminjaman
                    </h6>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('user.peminjaman.store') }}">
                        @csrf

                        <!-- KATEGORI -->
                        <div class="mb-3">
                            <label class="form-label fw-medium">Kategori</label>
                            @php
                                $presetAlat = request('alat_id') ? $alats->firstWhere('id', request('alat_id')) : null;
                                $presetKategoriId = optional(optional($presetAlat)->kategori->first())->id;
                            @endphp
                            <select name="kategori_id" id="kategori_id"
                                class="form-select @error('kategori_id') is-invalid @enderror"
                                required {{ request('alat_id') ? 'disabled' : '' }}>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}"
                                        @selected(old('kategori_id', $presetKategoriId) == $kat->id)>
                                        {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @if(request('alat_id'))
                                <input type="hidden" name="kategori_id" value="{{ $presetKategoriId }}">
                            @endif
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ALAT -->
                        <div class="mb-3">
                            <label class="form-label fw-medium">Nama Alat</label>
                            @php
                                $presetAlatId = request('alat_id');
                            @endphp
                            <select name="alat_id" id="alat_id"
                                class="form-select @error('alat_id') is-invalid @enderror"
                                required {{ request('alat_id') ? 'disabled' : '' }}>
                                <option value="">-- Pilih Alat --</option>
                                @foreach($alats as $alat)
                                    <option value="{{ $alat->id }}"
                                        data-kategori="{{ $alat->kategori->pluck('id')->join(',') }}"
                                        data-foto="{{ $alat->gambar ? asset('storage/'.$alat->gambar) : '' }}"
                                        data-stok="{{ $alat->jumlah }}"
                                        @selected(old('alat_id', $presetAlatId) == $alat->id)>
                                        {{ $alat->nama ?? $alat->nama_alat ?? 'Alat #'.$alat->id }}
                                    </option>
                                @endforeach
                            </select>
                            @if(request('alat_id'))
                                <input type="hidden" name="alat_id" value="{{ $presetAlatId }}">
                            @endif
                            <small id="stok-info" class="text-muted d-block mt-1">Stok tersisa: —</small>
                            @error('alat_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FOTO ALAT -->
                        <div class="mb-3 text-center" id="foto-alat-wrapper" style="display:none;">
                            <img id="foto-alat"
                                 src=""
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height:200px; object-fit:contain;"
                                 alt="Foto Alat">
                        </div>

                        <!-- JUMLAH -->
                        <div class="mb-3">
                            <label class="form-label fw-medium">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah" min="1"
                                value="{{ old('jumlah',1) }}"
                                class="form-control @error('jumlah') is-invalid @enderror"
                                required>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TANGGAL -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai"
                                    value="{{ old('tanggal_mulai', now()->toDateString()) }}"
                                    class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Tanggal Kembali</label>
                                <input type="date" name="tanggal_selesai"
                                    value="{{ old('tanggal_selesai') }}"
                                    class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                    required>
                            </div>
                        </div>

                        <!-- KETERANGAN -->
                        <div class="mt-3">
                            <label class="form-label fw-medium">Keterangan (Opsional)</label>
                            <textarea name="keterangan" rows="3"
                                class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                            <small class="text-muted">
                                Tambahkan alasan atau kebutuhan peminjaman
                            </small>
                        </div>

                        <!-- SUBMIT -->
                        <button type="submit" class="btn btn-primary w-100 mt-4">
                            <i class="bi bi-send me-2"></i>
                            Ajukan Peminjaman
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- SCRIPT FILTER ALAT + FOTO --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kategori = document.getElementById('kategori_id');
    const alat = document.getElementById('alat_id');
    const fotoWrapper = document.getElementById('foto-alat-wrapper');
    const foto = document.getElementById('foto-alat');
    const jumlah = document.getElementById('jumlah');
    const stokInfo = document.getElementById('stok-info');
    const placeholder = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="600" height="400"><rect width="100%25" height="100%25" fill="%23f8f9fa"/><text x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" fill="%236c757d" font-size="24" font-family="Arial, sans-serif">Tidak ada foto</text></svg>';

    function filterAlat() {
        const kategoriId = kategori.value;
        Array.from(alat.options).forEach(option => {
            if (!option.value) return;
            const categories = (option.dataset.kategori || '').split(',').filter(Boolean);
            option.hidden = kategoriId !== '' && !categories.includes(kategoriId);
        });
        // jika preset dari katalog, jangan ubah
        if (alat.hasAttribute('disabled')) {
            alat.value = alat.dataset.preset || alat.value;
        }
    }

    function tampilFoto() {
        const selected = alat.options[alat.selectedIndex];
        const fotoUrl = selected?.dataset?.foto;
        foto.src = fotoUrl || placeholder;
        fotoWrapper.style.display = fotoUrl ? 'block' : 'block';
    }

    function syncStok() {
        const selected = alat.options[alat.selectedIndex];
        const stok = Number(selected?.dataset?.stok || 0);
        if (stok > 0) {
            jumlah.max = stok;
            if (!jumlah.value || jumlah.value < 1) jumlah.value = 1;
            if (jumlah.value > stok) jumlah.value = stok;
            stokInfo.textContent = `Stok tersisa: ${stok}`;
            jumlah.disabled = false;
        } else {
            stokInfo.textContent = 'Stok tidak tersedia';
            jumlah.value = '';
            jumlah.disabled = true;
        }
    }

    kategori.addEventListener('change', filterAlat);
    alat.addEventListener('change', () => { tampilFoto(); syncStok(); });

    // init state
    filterAlat();
    if (alat.value) { tampilFoto(); }
    syncStok();
});
</script>

@endsection
