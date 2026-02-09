<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            flex-shrink: 0;
        }

        .main-wrapper {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 100vh;
        }

        header {
            flex-shrink: 0;
        }

        main {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 2rem;
        }

        footer {
            flex-shrink: 0;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 2rem 1rem;
            text-align: center;
            color: #6c757d;
            margin-top: auto;
        }

        .d-flex-layout {
            display: flex;
            min-height: 100vh;
        }

        .d-flex-layout > * {
            flex: 0 0 auto;
        }

        .d-flex-layout .flex-fill {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

@php
    $isProfile = request()->routeIs([
        'user.profile.*',
        'admin.profile.*',
        'petugas.profile.*',
    ]);
@endphp

<body class="bg-light">

<div class="d-flex-layout">

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
        <main class="p-3 p-md-4">
            @yield('content')
        </main>

        {{-- FOOTER removed --}}

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
