@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid px-3 px-md-4 pt-4">
    {{-- pt-4 = jarak dari navbar --}}

    <!-- HEADER + BACK BUTTON -->
    <div class="d-flex align-items-start justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Profile</h4>
            <p class="text-muted small mb-0">
                Perbarui informasi akun Anda
            </p>
        </div>

        <!-- BUTTON KEMBALI -->
        <a href="{{ route('user.dashboard') }}"
           class="btn btn-outline-secondary btn-sm mt-1">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">

            <x-profile.edit-form
                :user="$user"
                :action="route('user.profile.update')"
                :back-route="route('user.dashboard')" />

        </div>
    </div>

</div>
@endsection

