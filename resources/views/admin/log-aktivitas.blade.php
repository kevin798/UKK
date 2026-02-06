@extends('layouts.app')
@section('title','Log Aktivitas')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">Log Aktivitas Admin</h4>
            <p class="text-muted small mb-0">Riwayat aktivitas yang dilakukan oleh admin</p>
        </div>
        <span class="badge bg-primary text-white px-3 py-2 rounded-pill">
            <i class="bi bi-clock-history me-1"></i> Riwayat
        </span>
    </div>

    @if($logs->count())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0 py-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Log Aktivitas
                </h6>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted">
                            <th>#</th>
                            <th>Admin</th>
                            <th>Aktivitas</th>
                            <th>Jumlah</th>
                            <th>Rentang Waktu</th>
                            <th style="width:26%;">Catatan</th>
                            <th class="text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $i => $log)
                        <tr>
                            <td>{{ $i + $logs->firstItem() }}</td>
                            <td>{{ $log->user->name ?? '-' }}</td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $log->activity }}</span></td>
                            <td>{{ $log->jumlah ?? '-' }}</td>
                            <td>
                                {{ $log->tanggal_mulai ? \Carbon\Carbon::parse($log->tanggal_mulai)->format('d/m/Y') : '-' }}
                                s/d
                                {{ $log->tanggal_selesai ? \Carbon\Carbon::parse($log->tanggal_selesai)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="small text-muted" style="max-width:240px; white-space:pre-wrap; word-break:break-word; word-wrap:break-word;">
                                {{ $log->description ?? '-' }}
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary btn-log-detail" data-id="{{ $log->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
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
