@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-4 pb-5">
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Data Kategori</h4>
                <a href="{{ route('kategori.create') }}" class="btn btn-primary rounded-pill">
                    + Tambah
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Kategori</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $item)
                        <tr>
                            <td class="fw-medium">{{ $item->nama }}</td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <a href="{{ route('kategori.edit', $item->id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    Edit
                                </a>

                                <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                        onclick="return confirm('Hapus kategori ini?')">
                                        Hapus
                                    </button>
                                </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                Tidak ada data kategori
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
