@extends('layouts.app')
@section('title','Log Aktivitas Petugas')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">Log Aktivitas Petugas</h4>
            <p class="text-muted small mb-0">Riwayat aktivitas seluruh petugas</p>
        </div>
        <span class="badge bg-primary text-white px-3 py-2 rounded-pill">
            <i class="bi bi-clock-history me-1"></i> Riwayat
        </span>
    </div>

    @if($logs->count())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0 py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Log Aktivitas</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted">
                            <th>#</th>
                            <th>Petugas</th>
                            <th>Aktivitas</th>
                            <th>Jumlah</th>
                            <th>Waktu</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $i => $log)
                        <tr>
                            <td>{{ $i + $logs->firstItem() }}</td>
                            <td>{{ $log->user->name ?? '-' }}</td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $log->activity }}</span></td>
                            <td>{{ $log->jumlah ?? '-' }}</td>
                            <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td style="max-width:360px; white-space:pre-wrap; word-break:break-word;">{{ $log->description ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-0">
                {{ $logs->links() }}
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Tidak ada log aktivitas petugas.
        </div>
    @endif
</div>
@endsection
