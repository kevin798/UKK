@extends('layouts.app')
@section('title', 'Pengembalian')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Pengembalian Peminjaman</h4>
        <p class="text-muted small mb-0">
            Terima dan catat pengembalian alat
        </p>
    </div>

    <!-- ALERTS -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($peminjamans->isEmpty())
        <!-- EMPTY STATE -->
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Belum ada pengembalian yang perlu diproses
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
                            <th>Jadwal Kembali</th>
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
                                @if($p->status === 'approved')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass-split me-1"></i> Belum dikembalikan
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        <i class="bi bi-arrow-repeat me-1"></i> Sudah dikembalikan
                                    </span>
                                @endif
                            </td>

                            <!-- AKSI -->
                            <td class="text-center">
                                @if($p->status === 'approved')
                                    <button class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#returnModal{{ $p->id }}">
                                        <i class="bi bi-check2-circle me-1"></i> Terima
                                    </button>
                                @else
                                    <span class="text-muted small">Selesai</span>
                                @endif
                            </td>
                        </tr>

                        <!-- MODAL RETURN + DENDA -->
                        <div class="modal fade" id="returnModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('petugas.peminjaman.return', $p->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Terima Pengembalian</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            @php
                                                $lateDays = 0;
                                                if ($p->tanggal_selesai) {
                                                    $lateDays = max(0, now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($p->tanggal_selesai), false) * -1);
                                                }
                                                $suggestedFine = $lateDays * 10000;
                                            @endphp

                                            <div class="alert alert-warning d-flex align-items-center gap-2">
                                                <i class="bi bi-clock-history fs-5"></i>
                                                <div class="small mb-0">
                                                    Keterlambatan: <strong>{{ $lateDays }} hari</strong>.
                                                    Denda otomatis disarankan Rp {{ number_format($suggestedFine,0,',','.') }} (10.000/hari).
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Kondisi Pengembalian</label>
                                                <select name="kondisi_pengembalian" class="form-select" required>
                                                    <option value="baik">Baik</option>
                                                    <option value="rusak">Rusak</option>
                                                    <option value="hilang">Hilang</option>
                                                    <option value="terlambat" {{ $lateDays > 0 ? 'selected' : '' }}>Terlambat</option>
                                                </select>
                                                <small class="text-muted">Pilih kondisi barang saat dikembalikan.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Denda (Rp)</label>
                                                <input type="number" step="0.01" min="0" name="denda_amount"
                                                       class="form-control" value="{{ $suggestedFine }}">
                                                <small class="text-muted">Bisa disesuaikan; default 10.000/hari keterlambatan.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Keterangan / Alasan Denda</label>
                                                <textarea name="denda_reason" rows="3" class="form-control" placeholder="Contoh: Terlambat {{ $lateDays }} hari, kerusakan ringan, dll."></textarea>
                                            </div>

                                            <input type="hidden" name="denda_type" value="">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan & Selesai</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
