@extends('layouts.app')
@section('title', 'Profil')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Profil</h4>
            <p class="text-muted small mb-0">Lihat dan perbarui informasi akun Anda</p>
        </div>
        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <x-profile.edit-form
                :user="auth()->user()"
                :action="route('user.profile.update')"
                :back-route="route('user.dashboard')" />
        </div>
    </div>

</div>
@endsection
