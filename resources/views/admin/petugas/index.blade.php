@extends('layouts.app')

@section('title', 'Data Petugas')

@section('content')
<div class="container pt-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Data Petugas</h4>
                <a href="{{ route('admin.petugas.create') }}" class="btn btn-primary rounded-pill">
                    + Tambah Petugas
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
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($petugas as $item)
                        <tr>
                            <td class="fw-medium">{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ ucfirst($item->role) }}</span>
                            </td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success-subtle text-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <a href="{{ route('admin.petugas.edit', $item->id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    Edit
                                </a>

                                <form action="{{ route('admin.petugas.delete', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                        onclick="return confirm('Hapus petugas ini?')">
                                        Hapus
                                    </button>
                                </form>

                                <form action="{{ route('admin.petugas.toggle', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm {{ $item->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }} rounded-pill px-3"
                                            onclick="return confirm('Ubah status petugas ini?')">
                                        {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Tidak ada data petugas
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
