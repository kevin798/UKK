@extends('layouts.app')

@section('title', 'Data Peminjam')

@section('content')
<div class="container pt-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Data Peminjam</h4>
            </div>

            <!-- ALERT SUCCESS -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- ALERT ERROR -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-secondary text-uppercase small">
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Terdaftar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjam as $item)
                        <tr>
                            <td class="fw-medium">{{ $item->name }}</td>
                            <td class="text-muted">{{ $item->email }}</td>
                            <td>
                                <span class="badge
                                    {{ $item->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ ucfirst($item->role) }}
                                </span>
                            </td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    <a href="{{ route('admin.peminjam.edit', $item->id) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.peminjam.delete', $item->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus peminjam ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-4 d-block mb-2"></i>
                                Tidak ada data peminjam
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="d-flex justify-content-end mt-4">
                {{ $peminjam->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
