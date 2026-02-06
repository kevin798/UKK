@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <!-- HEADER + BACK BUTTON -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Profile</h4>
            <p class="text-muted small mb-0">
                Perbarui informasi akun Anda
            </p>
        </div>

        <!-- BUTTON KEMBALI (FIXED & WORKING) -->
        <button type="button"
                onclick="window.history.back()"
                class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </button>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <form action="{{ route('user.profile.update') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- FOTO PROFILE -->
                        <div class="text-center mb-4">
                            <img src="{{ auth()->user()->photo
                                ? asset('storage/'.auth()->user()->photo)
                                : 'https://via.placeholder.com/120' }}"
                                class="rounded-circle border shadow-sm mb-2"
                                width="120"
                                height="120"
                                alt="Profile Photo">

                            <input type="file"
                                   name="photo"
                                   class="form-control form-control-sm">
                            <small class="text-muted d-block mt-1">
                                JPG / PNG • Max 2MB
                            </small>
                        </div>

                        <!-- USERNAME -->
                        <div class="mb-3">
                            <label class="form-label fw-medium">Username</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', auth()->user()->name) }}"
                                   class="form-control"
                                   required>
                        </div>

                        <!-- NO TELP -->
                        <div class="mb-3">
                            <label class="form-label fw-medium">No. Telepon</label>
                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone', auth()->user()->phone) }}"
                                   class="form-control"
                                   placeholder="08xxxxxxxxxx">
                        </div>

                        <!-- ALAMAT -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Alamat</label>
                            <textarea name="address"
                                      class="form-control"
                                      rows="3">{{ old('address', auth()->user()->address) }}</textarea>
                        </div>

                        <!-- ACTION BUTTON -->
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button"
                                    onclick="window.history.back()"
                                    class="btn btn-secondary btn-sm">
                                Batal
                            </button>

                            <button type="submit"
                                    class="btn btn-primary btn-sm">
                                <i class="bi bi-save me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
