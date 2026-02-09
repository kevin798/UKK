@extends('layouts.app')
@section('title', 'Daftar Peminjaman')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Daftar Pengajuan Peminjaman</h4>
        <p class="text-muted small mb-0">
            Proses persetujuan peminjaman alat dari pengguna
        </p>
    </div>

    <!-- ALERT SUCCESS -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($peminjamans->isEmpty())
        <!-- EMPTY STATE -->
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Belum ada pengajuan peminjaman
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted">
                            <th>#</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Jumlah</th>
                            <th>Mulai</th>
                            <th>Kembali</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjamans as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <!-- PEMINJAM -->
                            <td>
                                <strong>{{ $p->user->name ?? '-' }}</strong><br>
                                <small class="text-muted">{{ $p->user->email ?? '-' }}</small>
                            </td>

                            <!-- ALAT -->
                            <td>{{ $p->alat->nama ?? '-' }}</td>

                            <td>{{ $p->jumlah }}</td>

                            <!-- TANGGAL -->
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}</td>

                            <!-- STATUS -->
                            <td>
                                @if($p->status === 'pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock me-1"></i> Pending
                                    </span>
                                @elseif($p->status === 'approved')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check me-1"></i> Disetujui
                                    </span>
                                @elseif($p->status === 'returned')
                                    <span class="badge bg-primary">
                                        <i class="bi bi-arrow-repeat me-1"></i> Dikembalikan
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x me-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>

                            <!-- AKSI -->
                            <td class="text-center">
                                @if($p->status === 'pending')
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">

                                        <!-- APPROVE -->
                                        <form action="{{ route('petugas.peminjaman.approve', $p->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Setujui peminjaman ini?')">
                                            @csrf
                                            <button class="btn btn-sm btn-success">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </form>

                                        <!-- REJECT -->
                                        <button class="btn btn-sm btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectModal{{ $p->id }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>

                                    <!-- MODAL REJECT -->
                                    <div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('petugas.peminjaman.reject', $p->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Peminjaman</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label class="form-label">Alasan Penolakan</label>
                                                        <textarea name="alasan"
                                                                  class="form-control"
                                                                  rows="4"
                                                                  required></textarea>
                                                        <small class="text-muted">
                                                            Jelaskan alasan penolakan
                                                        </small>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="btn btn-danger">
                                                            Tolak
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @elseif($p->status === 'approved')
                                    <!-- RETURN -->
                                    <form action="{{ route('petugas.peminjaman.return', $p->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Tandai peminjaman telah dikembalikan?')">
                                        @csrf
                                        <button class="btn btn-sm btn-primary">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
