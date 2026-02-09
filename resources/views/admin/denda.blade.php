@extends('layouts.app')
@section('title','Denda Peminjaman')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Laporan Denda</h4>
            <p class="text-muted small mb-0">Rekap denda yang ditetapkan petugas</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Dashboard</a>
    </div>

    @isset($dendaSummary)
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total denda bulan ini</p>
                    <h4 class="fw-bold text-danger mb-0">Rp {{ number_format($dendaMonthTotal ?? 0,0,',','.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Kasus denda bulan ini</p>
                    <h4 class="fw-bold mb-0">{{ $dendaMonthCases ?? 0 }}</h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Petugas terlibat</p>
                    <h4 class="fw-bold mb-0">{{ $dendaSummary->count() }}</h4>
                </div>
            </div>
        </div>
    </div>
    @endisset

    @isset($dendaSummary)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="fw-bold mb-0">Rekap per Petugas (bulan ini)</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-uppercase small text-muted">
                        <th>Petugas</th>
                        <th>Total Denda</th>
                        <th>Kasus</th>
                        <th>Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dendaSummary as $row)
                    <tr>
                        <td>{{ $row->dendaSetter->name ?? 'Petugas#'.$row->denda_set_by }}</td>
                        <td class="fw-bold text-danger">Rp {{ number_format($row->total_denda,0,',','.') }}</td>
                        <td>{{ $row->total_kasus }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->terakhir)->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada data bulan ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endisset

    @if($dendaList->count())
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted">
                            <th>#</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Denda</th>
                            <th>Kondisi</th>
                            <th>Keterangan</th>
                            <th>Ditentukan Oleh</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dendaList as $i => $item)
                        <tr>
                            <td>{{ $dendaList->firstItem() + $i }}</td>
                            <td>
                                <strong>{{ $item->user->name ?? 'User#'.$item->user_id }}</strong><br>
                                <small class="text-muted">{{ $item->user->email ?? '' }}</small>
                            </td>
                            <td>{{ $item->alat->nama ?? $item->alat->nama_alat ?? 'Alat#'.$item->alat_id }}</td>
                            <td class="fw-bold text-danger">Rp {{ number_format($item->denda_amount,2,',','.') }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($item->kondisi_pengembalian ?? '-') }}</span></td>
                            <td style="max-width:260px;white-space:pre-wrap;">{{ $item->denda_reason ?? '-' }}</td>
                            <td>{{ $item->dendaSetter->name ?? 'Petugas#'.$item->denda_set_by }}</td>
                            <td>
                                @if($item->denda_status === 'paid')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($item->denda_status === 'waived')
                                    <span class="badge bg-secondary">Dihapus</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Lunas</span>
                                @endif
                            </td>
                            <td>{{ $item->updated_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-0">
                {{ $dendaList->links() }}
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>Belum ada denda yang tercatat.
        </div>
    @endif
</div>
@endsection
