@extends('layouts.app')

@section('title', 'Tambah Alat')

@section('content')
<div class="container-fluid px-3 px-md-4 pb-5">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Tambah Alat</h4>
                <a href="{{ route('alat.index') }}" class="btn btn-secondary rounded-pill">
                    Kembali
                </a>
            </div>

            <form action="{{ route('alat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="nama" class="form-label fw-medium">Nama Alat <span class="text-danger">*</span></label>
                    <input type="text" id="nama" name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama') }}"
                           placeholder="Masukkan nama alat" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="kategori_id" class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
                    <select id="kategori_id" name="kategori_id"
                            class="form-select @error('kategori_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jumlah" class="form-label fw-medium">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" id="jumlah" name="jumlah"
                           class="form-control @error('jumlah') is-invalid @enderror"
                           value="{{ old('jumlah') }}"
                           placeholder="Masukkan jumlah" required>
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="keterangan" class="form-label fw-medium">Keterangan </label>
                    <textarea id="keterangan" name="keterangan" rows="4" maxlength="150"
                              class="form-control @error('keterangan') is-invalid @enderror"
                              placeholder="Masukkan keterangan">{{ old('keterangan') }}</textarea>
                    <small class="d-block mt-1 text-muted"><span id="char-count">0</span>/150 karakter</small>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="gambar" class="form-label fw-medium">Foto Alat</label>
                    <input type="file" id="gambar" name="gambar"
                           class="form-control @error('gambar') is-invalid @enderror"
                           accept="image/*">
                    @error('gambar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="text-muted d-block mt-1">JPG, PNG • Max 2MB</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        Simpan
                    </button>
                    <a href="{{ route('alat.index') }}" class="btn btn-secondary rounded-pill px-4">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const keteranganInput = document.getElementById('keterangan');
    const charCount = document.getElementById('char-count');

    keteranganInput.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });

    // Initialize character count on page load
    charCount.textContent = keteranganInput.value.length;
</script>
@endsection

