@props(['role'])
@php
    $activeClass = 'nav-link active d-flex align-items-center gap-2';
    $inactiveClass = 'nav-link text-white-50 d-flex align-items-center gap-2';
@endphp

<aside class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark position-fixed" style="width: 280px; height: 100vh; top: 0; left: 0; overflow-y: auto; z-index: 1000;">

    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4 fw-bold">
            PinjamAlat
        </span>
    </a>

    <hr>

    <ul class="nav nav-pills flex-column mb-auto gap-1">

        {{-- ADMIN --}}
        @if ($role === 'admin')
            <li class="nav-item">
                <div class="text-uppercase small fw-bold text-white-50 mt-2 mb-1 ms-3" style="font-size: 0.75rem;">
                    Admin
                </div>
            </li>
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}"
                   aria-current="page">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.petugas') }}" class="{{ request()->routeIs('admin.petugas', 'admin.petugas.*') ? $activeClass : $inactiveClass }}">
                    Kelola Petugas
                </a>
            </li>
            <li>
                <a href="{{ route('admin.peminjam') }}" class="{{ request()->routeIs('admin.peminjam', 'admin.peminjam.*') ? $activeClass : $inactiveClass }}">
                    Kelola Peminjam
                </a>
            </li>
            <li>
                <a href="{{ route("alat.index") }}" class="{{ request()->routeIs('alat.*') ? $activeClass : $inactiveClass }}">
                    Data Alat
                </a>
            </li>
            <li>
                <a href="{{ route('kategori.index') }}" class="{{ request()->routeIs('kategori.*') ? $activeClass : $inactiveClass }}">
                    Kategori
                </a>
            </li>
            <li>
                <a href="{{ route('admin.laporan') }}" class="{{ request()->routeIs('admin.laporan') ? $activeClass : $inactiveClass }}">
                    Laporan Petugas
                </a>
            </li>
            <li>
                <a href="{{ route('admin.log-aktivitas') }}" class="{{ request()->routeIs('admin.log-aktivitas*') ? $activeClass : $inactiveClass }}">
                    Log Aktivitas
                </a>
            </li>
        @endif

        @if ($role === 'petugas')
            <li class="nav-item">
                <div class="text-uppercase small fw-bold text-white-50 mt-2 mb-1 ms-3" style="font-size: 0.75rem;">
                    Petugas
                </div>
            </li>
            <li>
                <a href="{{ route('petugas.dashboard') }}"
                   class="{{ request()->routeIs('petugas.dashboard') ? $activeClass : $inactiveClass }}">
                    <i class="bi bi-grid"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('petugas.peminjaman') }}"
                   class="{{ request()->routeIs('petugas.peminjaman') ? $activeClass : $inactiveClass }}">
                    <i class="bi bi-check-circle"></i>
                    Persetujuan
                </a>
            </li>
            <li>
                <a href="{{ route('petugas.pengembalian') }}"
                   class="{{ request()->routeIs('petugas.pengembalian') ? $activeClass : $inactiveClass }}">
                    <i class="bi bi-arrow-repeat"></i>
                    Pengembalian
                </a>
            </li>
            <li>
                <a href="{{ route('petugas.denda') }}"
                   class="{{ request()->routeIs('petugas.denda') ? $activeClass : $inactiveClass }}">
                    <i class="bi bi-cash-stack"></i>
                    Laporan Denda
                </a>
            </li>
            <li>
                <a href="{{ route('petugas.laporan') }}"
                   class="{{ request()->routeIs('petugas.laporan') ? $activeClass : $inactiveClass }}">
                    <i class="bi bi-flag"></i>
                    Laporan ke Admin
                </a>
            </li>
            <li>
                <a href="{{ route('petugas.log-aktivitas') }}"
                   class="{{ request()->routeIs('petugas.log-aktivitas') ? $activeClass : $inactiveClass }}">
                    <i class="bi bi-clock-history"></i>
                    Log Aktivitas
                </a>
            </li>
        @endif

        {{-- USER --}}
        @if ($role === 'user')
            <li class="nav-item">
                <div class="text-uppercase small fw-bold text-white-50 mt-2 mb-1 ms-3" style="font-size: 0.75rem;">
                    Peminjam
                </div>
            </li>
            <li>
                <a href="{{ route('user.dashboard') }}"
                   class="{{ request()->routeIs('user.dashboard') ? $activeClass : $inactiveClass }}">
                    <i class="bi bi-house-door"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('user.peminjaman') }}"
                   class="{{ request()->routeIs('user.peminjaman') ? $activeClass : $inactiveClass }}">
                    <i class="bi bi-cart3"></i>
                    Peminjaman
                </a>
            </li>

            <li>
                <a href="{{ route('user.pengembalian') }}"
                   class="{{ request()->routeIs('user.pengembalian') ? $activeClass : $inactiveClass }}">
                    <i class="bi bi-arrow-repeat"></i>
                    Pengembalian
                </a>
            </li>

            <li>
                <a href="{{ route('log.aktivitas') }}"
                   class="{{ request()->routeIs('log.aktivitas') ? $activeClass : $inactiveClass }}">
                    <i class="bi bi-clock-history"></i>
                    Log Aktivitas
                </a>
            </li>
        @endif

    </ul>

</aside>
