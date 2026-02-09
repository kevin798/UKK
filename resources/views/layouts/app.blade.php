<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <!-- Bootstrap -->
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <style>
        :root {
            --surface: #ffffff;
            --surface-soft: #f6f7fb;
            --border: #e6e8ec;
            --text: #212529;
            --muted: #6c757d;
            --shadow: 0 14px 30px rgba(33, 37, 41, 0.08);
            --radius: 14px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            background: var(--surface-soft);
            color: var(--text);
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            flex-shrink: 0;
            background: #121212;
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
            background-color: var(--surface);
            border-top: 1px solid var(--border);
            padding: 2rem 1rem;
            text-align: center;
            color: var(--muted);
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

        /* Card & controls */
        .card { border: 0; border-radius: var(--radius); box-shadow: var(--shadow); }
        .card-header { border-bottom: 1px solid var(--border); }
        .btn { border-radius: 10px; }
        .form-control, .form-select { border-radius: 10px; border-color: var(--border); }
        .form-control:focus, .form-select:focus { box-shadow: 0 0 0 0.15rem rgba(13,110,253,.15); border-color: #0d6efd; }

        /* Table polish */
        .table {
            --bs-table-hover-bg: #f8fafc;
        }
        .table thead th {
            background: #f1f3f5;
            color: #495057;
            font-size: 12px;
            letter-spacing: .02em;
            border-bottom: 1px solid #dee2e6;
        }
        .table > :not(caption) > * > * {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
        .table tbody tr:not(:last-child) td {
            border-bottom-color: #eef1f4;
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

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
</body>
</html>
