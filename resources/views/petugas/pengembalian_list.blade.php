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
                            <th>Kondisi</th>
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

                            <!-- KONDISI -->
                            <td>
                                @if($p->status !== 'approved')
                                    @if($p->kondisi_pengembalian)
                                        <span class="badge {{ $p->kondisi_pengembalian === 'baik' ? 'bg-success' : ($p->kondisi_pengembalian === 'rusak' ? 'bg-warning text-dark' : ($p->kondisi_pengembalian === 'hilang' ? 'bg-danger' : 'bg-secondary')) }}">
                                            {{ ucfirst($p->kondisi_pengembalian) }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                @else
                                    <span class="text-muted small">-</span>
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
                                    <button class="btn btn-sm btn-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $p->id }}">
                                        <i class="bi bi-eye me-1"></i> Detail
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <!-- MODAL DETAIL KONDISI PENGEMBALIAN -->
                        @if($p->status !== 'approved')
                        <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Kondisi Pengembalian</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Info Peminjaman -->
                                        <div class="mb-4">
                                            <h6 class="fw-bold mb-3">Informasi Peminjaman</h6>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Nama Peminjam</small>
                                                    <strong>{{ $p->user->name ?? '-' }}</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Alat yang Dipinjam</small>
                                                    <strong>{{ $p->alat->nama ?? '-' }} ({{ $p->jumlah }} pcs)</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Tanggal Mulai</small>
                                                    <strong>{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Jadwal Kembali</small>
                                                    <strong>{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}</strong>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- Kondisi Pengembalian -->
                                        <div class="mb-4">
                                            <h6 class="fw-bold mb-3">Kondisi Pengembalian</h6>
                                            <div class="row g-4">
                                                <!-- Gambar Referensi -->
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block mb-2 fw-semibold">Gambar Referensi Alat</small>
                                                    @if($p->alat->gambar)
                                                        <img src="{{ asset('storage/'.$p->alat->gambar) }}"
                                                             alt="gambar alat"
                                                             class="rounded shadow-sm w-100"
                                                             style="max-height: 250px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded p-5 text-center">
                                                            <i class="bi bi-image fs-1 text-muted"></i>
                                                            <p class="text-muted mt-2">Tidak ada gambar</p>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Gambar Pengembalian -->
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block mb-2 fw-semibold">Foto Kondisi Saat Dikembalikan</small>
                                                    @if($p->gambar_pengembalian)
                                                        <img src="{{ asset('storage/'.$p->gambar_pengembalian) }}"
                                                             alt="gambar pengembalian"
                                                             class="rounded shadow-sm w-100"
                                                             style="max-height: 250px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded p-5 text-center">
                                                            <i class="bi bi-image fs-1 text-muted"></i>
                                                            <p class="text-muted mt-2">Tidak ada foto</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Status & Catatan -->
                                        <div class="mb-4">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Status Kondisi</small>
                                                    <div>
                                                        <span class="badge {{ $p->kondisi_pengembalian === 'baik' ? 'bg-success' : ($p->kondisi_pengembalian === 'rusak' ? 'bg-warning text-dark' : ($p->kondisi_pengembalian === 'hilang' ? 'bg-danger' : 'bg-secondary')) }}">
                                                            {{ ucfirst($p->kondisi_pengembalian ?? '-') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @if($p->denda_amount > 0)
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Denda</small>
                                                    <strong class="text-danger">Rp {{ number_format($p->denda_amount, 0, ',', '.') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if($p->catatan_pengembalian || $p->denda_reason)
                                        <div class="alert alert-light">
                                            @if($p->catatan_pengembalian)
                                                <h6 class="fw-bold mb-2">Catatan Pengembalian</h6>
                                                <p class="mb-0">{{ $p->catatan_pengembalian }}</p>
                                            @endif
                                            @if($p->denda_reason)
                                                <h6 class="fw-bold mb-2 mt-3">Alasan Denda</h6>
                                                <p class="mb-0">{{ $p->denda_reason }}</p>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- MODAL RETURN + DENDA -->
                        @if($p->status === 'approved')
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
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
