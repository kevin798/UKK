@extends('layouts.app')

@section('title', 'Data Alat')

@section('content')
<div class="container-fluid px-3 px-md-4 pb-5">
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Data Alat</h4>
                @if($kategoriCount > 0)
                <a href="{{ route('alat.create') }}" class="btn btn-primary rounded-pill">
                    + Tambah
                </a>
                @else
                <button class="btn btn-secondary rounded-pill" disabled title="Tidak ada kategori">
                    + Tambah
                </button>
                @endif
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

            @if($kategoriCount === 0)
            <div class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Anda belum memiliki kategori.
                <a href="{{ route('kategori.create') }}">Tambah kategori</a> terlebih dahulu untuk menambah alat.
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($alat as $item)
                        <tr>
                            <td>
                                @if($item->gambar)
                                <img src="{{ asset('storage/'.$item->gambar) }}"
                                    alt="gambar alat"
                                    width="70"
                                    class="rounded shadow-sm">
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td class="fw-medium">{{ $item->nama }}</td>

                            <td>
                                <span class="badge bg-secondary">{{ $item->kategori->nama ?? '-' }}</span>
                            </td>

                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>


                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="{{ route('alat.edit', $item->id) }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Edit
                                    </a>

                                    <form action="{{ route('alat.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                            onclick="return confirm('Hapus alat ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Tidak ada data alat
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
