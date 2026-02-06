@extends('layouts.app')
@section('title','Peminjaman')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Kelola Peminjaman</h4>
        <p class="text-muted small mb-0">
            Ajukan dan lihat riwayat peminjaman alat Anda
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

        <!-- FORM PEMINJAMAN -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        Ajukan Peminjaman Baru
                    </h6>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('user.peminjaman.store') }}">
                        @csrf

                        <!-- KATEGORI -->
                        <div class="mb-3">
                            <label class="form-label fw-medium">Kategori</label>
                            <select name="kategori_id" id="kategori_id"
                                class="form-select @error('kategori_id') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}"
                                        @selected(old('kategori_id') == $kat->id)>
                                        {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ALAT (DISABLED SEBELUM PILIH KATEGORI) -->
                        <div class="mb-3">
                            <label class="form-label fw-medium">Nama Alat</label>
                            <select name="alat_id" id="alat_id"
                                class="form-select @error('alat_id') is-invalid @enderror"
                                disabled required>
                                <option value="">-- Pilih Alat --</option>
                                @foreach($alats as $alat)
                                    <option value="{{ $alat->id }}"
                                        @selected(old('alat_id') == $alat->id)>
                                        {{ $alat->nama ?? $alat->nama_alat ?? 'Alat #'.$alat->id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('alat_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- JUMLAH -->
                        <div class="mb-3">
                            <label class="form-label fw-medium">Jumlah</label>
                            <input type="number" name="jumlah" min="1"
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

        <!-- INFO -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Informasi
                    </h6>
                </div>

                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2">Cara Mengajukan</h6>
                    <ol class="small text-muted mb-4">
                        <li>Pilih kategori</li>
                        <li>Pilih alat</li>
                        <li>Tentukan jumlah</li>
                        <li>Atur tanggal</li>
                        <li>Klik Ajukan Peminjaman</li>
                    </ol>

                    <hr>

                    <h6 class="fw-bold mb-2">Status Peminjaman</h6>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li class="mb-2">
                            <span class="badge bg-warning text-dark me-1">Pending</span>
                            Menunggu persetujuan
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-success me-1">Approved</span>
                            Peminjaman disetujui
                        </li>
                        <li>
                            <span class="badge bg-danger me-1">Rejected</span>
                            Peminjaman ditolak
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- SCRIPT KONTROL ALAT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kategori = document.getElementById('kategori_id');
    const alat = document.getElementById('alat_id');

    function toggleAlat() {
        if (kategori.value) {
            alat.disabled = false;
        } else {
            alat.disabled = true;
            alat.value = '';
        }
    }

    // Saat halaman load (old input)
    toggleAlat();

    // Saat kategori berubah
    kategori.addEventListener('change', toggleAlat);
});
</script>

@endsection
