@extends('layouts.app')
@section('title','Log Aktivitas')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1">Log Aktivitas Admin & Petugas</h4>
            <p class="text-muted small mb-0">Riwayat aktivitas yang dilakukan oleh admin maupun petugas</p>
        </div>
        <span class="badge bg-primary text-white px-3 py-2 rounded-pill">
            <i class="bi bi-clock-history me-1"></i> Riwayat
        </span>
    </div>

    @if($logs->count())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Log Aktivitas</h6>
                    <p class="text-muted small mb-0">Pantau semua aksi dan status peminjaman</p>
                </div>
                <div class="d-flex align-items-end gap-2 flex-wrap">
                    <form method="GET" class="d-flex align-items-end gap-2 flex-wrap">
                        <div>
                            <label class="form-label small mb-1">Dari</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control form-control-sm">
                        </div>
                        <div>
                            <label class="form-label small mb-1">Sampai</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control form-control-sm">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        @if(request()->hasAny(['start_date','end_date']))
                            <a href="{{ route('admin.log-aktivitas') }}" class="btn btn-sm btn-secondary">Reset</a>
                        @endif
                    </form>
                    <span class="badge bg-primary-subtle text-primary">Total {{ $logs->total() }}</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted">
                            <th>#</th>
                            <th>Pengguna</th>
                            <th>Peran</th>
                            <th>Aktivitas</th>
                            <th>Jumlah</th>
                            <th>Dibuat</th>
                            <th style="width:26%;">Catatan</th>
                            <th class="text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $i => $log)
                        <tr>
                            <td>{{ $i + $logs->firstItem() }}</td>
                            <td>{{ $log->user->name ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ $log->user->role ?? '-' }}</span></td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $log->activity }}</span></td>
                            <td>{{ $log->jumlah ?? '-' }}</td>
                            <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td class="small text-muted" style="max-width:240px; white-space:pre-wrap; word-break:break-word; word-wrap:break-word;">
                                {{ $log->description ?? '-' }}
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary btn-log-detail" data-id="{{ $log->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Tidak ada log aktivitas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-0">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span class="text-muted small">
                        Menampilkan {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} entri
                    </span>
                    <div class="ms-auto">
                        {{ $logs->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Tidak ada log aktivitas.
        </div>
    @endif

</div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.btn-log-detail');
        const modalHtml = `
        <div class="modal fade" id="logDetailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Log Aktivitas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <dl class="row">
                            <dt class="col-sm-3">User</dt><dd class="col-sm-9" id="ld-user"></dd>
                            <dt class="col-sm-3">Activity</dt><dd class="col-sm-9" id="ld-activity"></dd>
                            <dt class="col-sm-3">Jumlah</dt><dd class="col-sm-9" id="ld-jumlah"></dd>
                            <dt class="col-sm-3">Waktu</dt><dd class="col-sm-9" id="ld-waktu"></dd>
                            <dt class="col-sm-3">Description</dt><dd class="col-sm-9" id="ld-description"></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>`;

        if (!document.getElementById('logDetailModal')) {
            const div = document.createElement('div');
            div.innerHTML = modalHtml;
            document.body.appendChild(div);
        }

        const logModalEl = document.getElementById('logDetailModal');
        const logModal = new bootstrap.Modal(logModalEl);

        buttons.forEach(btn => {
            btn.addEventListener('click', async function () {
                const id = this.dataset.id;
                try {
                    const res = await fetch(`{{ url('admin/log-aktivitas') }}/` + id);
                    if (!res.ok) throw new Error('Gagal mengambil data');
                    const data = await res.json();
                    document.getElementById('ld-user').textContent = data.user ? data.user.name : '-';
                    document.getElementById('ld-activity').textContent = data.activity;
                    document.getElementById('ld-jumlah').textContent = data.jumlah || '-';
                    const mulai = data.tanggal_mulai || '-';
                    const selesai = data.tanggal_selesai || '-';
                    document.getElementById('ld-waktu').textContent = mulai + ' s/d ' + selesai;
                    document.getElementById('ld-description').textContent = data.description || '-';
                    logModal.show();
                } catch (err) {
                    console.error(err);
                    alert('Tidak dapat memuat detail log.');
                }
            });
        });
    });
    </script>
@endsection
