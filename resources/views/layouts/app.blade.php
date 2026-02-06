<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            overflow: hidden;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
        }
        .content-wrapper {
            height: 100vh;
            overflow-y: auto;
        }
    </style>
</head>

@php
    $isProfile = request()->routeIs('user.profile.*');
@endphp

<body class="bg-light">

<div class="d-flex">

    {{-- SIDEBAR --}}
    @unless($isProfile)
        <aside class="sidebar bg-dark text-white">
            <x-sidebar role="{{ auth()->user()->role }}" />
        </aside>
    @endunless

    {{-- MAIN CONTENT --}}
    <div class="flex-fill d-flex flex-column">

        {{-- TOPBAR --}}
        <header class="bg-white border-bottom shadow-sm">
            @include('layouts.topbar')
        </header>

        {{-- PAGE CONTENT --}}
        <main class="content-wrapper p-4">
            @yield('content')
        </main>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
