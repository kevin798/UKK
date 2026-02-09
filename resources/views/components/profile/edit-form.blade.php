@props(['user' => null, 'action', 'backRoute'])

@php
    $user = $user ?? auth()->user();
    $path = $user->photo;
    if ($path && str_starts_with($path,'public/')) {
        $path = \Illuminate\Support\Str::after($path,'public/');
    }
    $photoUrl = $path ? asset('storage/'.$path) : 'https://via.placeholder.com/120';
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <h6 class="fw-bold mb-3">Profil</h6>

        <form id="profile-form"
              action="{{ $action }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="text-center mb-4">
                <img src="{{ $photoUrl }}"
                    class="rounded-circle border shadow-sm mb-2"
                    width="120"
                    height="120"
                    alt="Profile Photo">

                <input type="file"
                       name="photo"
                       class="form-control form-control-sm"
                       disabled>
                <small class="text-muted d-block mt-1">
                    JPG / PNG • Max 2MB
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Username</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       class="form-control @error('name') is-invalid @enderror"
                       required
                       disabled>
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">No. Telepon</label>
                <input type="text"
                       name="phone"
                       value="{{ old('phone', $user->phone) }}"
                       class="form-control @error('phone') is-invalid @enderror"
                       placeholder="08xxxxxxxxxx"
                       disabled>
                @error('phone')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Alamat</label>
                <textarea name="address"
                          class="form-control @error('address') is-invalid @enderror"
                          rows="3"
                          disabled>{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="button"
                        class="btn btn-primary btn-sm"
                        id="btn-edit-profile">
                    <i class="bi bi-pencil me-1"></i>Edit
                </button>
                <button type="button"
                        class="btn btn-secondary btn-sm"
                        id="btn-cancel-profile"
                        disabled>
                    Batal
                </button>

                <button type="submit"
                        class="btn btn-success btn-sm"
                        id="btn-save-profile"
                        disabled>
                    <i class="bi bi-save me-1"></i>
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    toggleEditProfile(false);
    document.getElementById('btn-edit-profile').addEventListener('click', () => toggleEditProfile(true));
    document.getElementById('btn-cancel-profile').addEventListener('click', () => toggleEditProfile(false));
});

function toggleEditProfile(enable) {
    const form = document.getElementById('profile-form');
    form.querySelectorAll('input, textarea').forEach(el => {
        if (el.name !== '_token' && el.name !== '_method') {
            el.disabled = !enable;
        }
    });
    document.getElementById('btn-save-profile').disabled = !enable;
    document.getElementById('btn-cancel-profile').disabled = !enable;
    document.getElementById('btn-edit-profile').disabled = enable;
}
</script>
@endpush
