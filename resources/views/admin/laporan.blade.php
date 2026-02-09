@extends('layouts.app')
@section('title','Laporan Petugas')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Laporan Petugas</h4>
            <p class="text-muted small mb-0">Semua laporan yang dikirim petugas</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="fw-bold mb-0">Daftar Laporan</h6>
                <p class="text-muted small mb-0">Klik status untuk menandai selesai</p>
            </div>
            <span class="badge bg-primary-subtle text-primary">Total {{ $reports->total() }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-uppercase small text-muted">
                        <th>#</th>
                        <th>Petugas</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Lampiran</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $i => $report)
                    <tr>
                        <td>{{ $reports->firstItem() + $i }}</td>
                        <td>{{ $report->user->name ?? 'Petugas#'.$report->user_id }}</td>
                        <td>{{ $report->judul }}</td>
                        <td class="text-muted small" style="max-width:280px; white-space:pre-wrap;">{{ $report->deskripsi }}</td>
                        <td>
                            @if($report->lampiran)
                                <a href="{{ asset('storage/'.$report->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat</a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.laporan.status', $report->id) }}" method="POST" class="d-inline">
                                @csrf
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="pending" @selected($report->status==='pending')>Pending</option>
                                    <option value="resolved" @selected($report->status==='resolved')>Selesai</option>
                                </select>
                            </form>
                        </td>
                        <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada laporan.</td></tr>
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
