@extends('layouts.app')
@section('title','Denda Saya')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Denda yang Saya Tetapkan</h4>
            <p class="text-muted small mb-0">Daftar denda untuk peminjam yang Anda proses</p>
        </div>
    </div>

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
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center">Edit</th>
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
                            <td style="max-width:240px;white-space:pre-wrap;">{{ $item->denda_reason ?? '-' }}</td>
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
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editDenda{{ $item->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- MODAL EDIT DENDA -->
                        <div class="modal fade" id="editDenda{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('petugas.denda.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Denda</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nominal Denda (Rp)</label>
                                                <input type="number" step="0.01" min="0" name="denda_amount"
                                                       class="form-control"
                                                       value="{{ $item->denda_amount }}">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Status Denda</label>
                                                <select name="denda_status" class="form-select">
                                                    <option value="unpaid" @selected($item->denda_status === 'unpaid')>Belum Lunas</option>
                                                    <option value="paid" @selected($item->denda_status === 'paid')>Lunas</option>
                                                    <option value="waived" @selected($item->denda_status === 'waived')>Dihapus</option>
                                                    <option value="none" @selected($item->denda_status === 'none')>Tidak Ada</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Keterangan</label>
                                                <textarea name="denda_reason" rows="3" class="form-control"
                                                          placeholder="Alasan atau catatan">{{ $item->denda_reason }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
            <i class="bi bi-info-circle me-2"></i>Belum ada denda yang Anda tetapkan.
        </div>
    @endif
</div>
@endsection
