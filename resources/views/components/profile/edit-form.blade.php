@props(['user' => null, 'action', 'backRoute'])

@php
    $user = $user ?? auth()->user();
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <form action="{{ $action }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- FOTO PROFILE -->
            <div class="text-center mb-4">
                <img src="{{ $user->photo
                    ? asset('storage/'.$user->photo)
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
                       value="{{ old('name', $user->name) }}"
                       class="form-control @error('name') is-invalid @enderror"
                       required>
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- NO TELP -->
            <div class="mb-3">
                <label class="form-label fw-medium">No. Telepon</label>
                <input type="text"
                       name="phone"
                       value="{{ old('phone', $user->phone) }}"
                       class="form-control @error('phone') is-invalid @enderror"
                       placeholder="08xxxxxxxxxx">
                @error('phone')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- ALAMAT -->
            <div class="mb-4">
                <label class="form-label fw-medium">Alamat</label>
                <textarea name="address"
                          class="form-control @error('address') is-invalid @enderror"
                          rows="3">{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- ACTION BUTTON -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ $backRoute }}"
                   class="btn btn-secondary btn-sm">
                    Batal
                </a>

                <button type="submit"
                        class="btn btn-primary btn-sm">
                    <i class="bi bi-save me-1"></i>
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>
