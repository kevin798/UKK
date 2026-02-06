@extends('layouts.app')

@section('title', 'Tambah Petugas')

@section('content')
<div class="container pt-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Tambah Petugas</h4>
                <a href="{{ route('admin.petugas') }}" class="btn btn-secondary rounded-pill">
                    Kembali
                </a>
            </div>

            <form action="{{ route('admin.petugas.store') }}" method="POST">
                @csrf

                <!-- NAMA -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-medium">
                        Nama <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Masukan Nama"
                        required>
                    <small class="text-muted">Minimal 3 karakter</small>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- EMAIL -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-medium">
                        Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email"
                        required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- PASSWORD + ICON MATA -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-medium">
                        Password <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukan Password"
                            required>

                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                    <small class="text-muted">Minimal 8 karakter</small>

                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- BUTTON -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        Simpan
                    </button>
                    <a href="{{ route('admin.petugas') }}" class="btn btn-secondary rounded-pill px-4">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT TOGGLE PASSWORD -->
<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');

    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});
</script>
@endsection
