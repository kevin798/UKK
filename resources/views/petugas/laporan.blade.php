@extends('layouts.app')
@section('title','Laporan Petugas')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Laporan ke Admin</h4>
            <p class="text-muted small mb-0">Kirim laporan insiden/denda/temuan ke admin</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light border-0">
            <h6 class="fw-bold mb-0">Buat Laporan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('petugas.laporan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Judul</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" class="form-control @error('judul') is-invalid @enderror" required>
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Lampiran (opsional)</label>
                    <input type="file" name="lampiran" class="form-control @error('lampiran') is-invalid @enderror" accept="image/*,application/pdf">
                    <div class="form-text">jpg/png/pdf maks 2MB</div>
                    @error('lampiran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button class="btn btn-primary">Kirim Laporan</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Laporan Saya</h6>
            <span class="badge bg-primary-subtle text-primary">Total {{ $reports->total() }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-uppercase small text-muted">
                        <th>#</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Lampiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $i => $report)
                    <tr>
                        <td>{{ $reports->firstItem() + $i }}</td>
                        <td>{{ $report->judul }}</td>
                        <td>
                            @if($report->status === 'resolved')
                                <span class="badge bg-success">Selesai</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                        <td>
                            @if($report->lampiran)
                                <a href="{{ asset('storage/'.$report->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat</a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada laporan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
