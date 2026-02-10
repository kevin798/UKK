@extends('layouts.app')
@section('title','Pengembalian')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Pengembalian Alat</h4>
        <p class="text-muted small mb-0">
            Lihat pinjaman yang perlu dikembalikan dan konfirmasi pengembalian.
        </p>
    </div>

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
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Tidak ada pengembalian yang perlu diproses.
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted">
                            <th>#</th>
                            <th>Alat</th>
                            <th>Jumlah</th>
                            <th>Mulai</th>
                            <th>Jadwal Kembali</th>
                            <th>Foto</th>
                            <th>Status</th>
                            <th>Denda</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjamans as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $p->alat->nama ?? $p->alat->nama_alat ?? 'Alat #'.$p->alat_id }}</strong><br>
                                <small class="text-muted">
                                    @forelse($p->alat->kategori as $kat)
                                        <span class="badge bg-secondary me-1">{{ $kat->nama }}</span>
                                    @empty
                                        -
                                    @endforelse
                                </small>
                            </td>
                            <td>{{ $p->jumlah }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $path = $p->foto_pengembalian;
                                    if ($path && str_starts_with($path, 'public/')) {
                                        $path = \Illuminate\Support\Str::after($path, 'public/');
                                    }
                                    $fotoUrl = $path ? asset('storage/'.$path) : null;
                                @endphp
                                @if($fotoUrl)
                                    <a href="{{ $fotoUrl }}" target="_blank">
                                        <img src="{{ $fotoUrl }}"
                                             alt="Foto pengembalian"
                                             style="width:56px; height:56px; object-fit:cover; border-radius:6px;">
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($p->status === 'approved')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass-split me-1"></i> Belum dikembalikan
                                    </span>
                                @elseif($p->status === 'return_requested')
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-send-check me-1"></i> Menunggu verifikasi petugas
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        <i class="bi bi-arrow-repeat me-1"></i> Sudah dikembalikan
                                    </span>
                                @endif
                                @if($p->status_barang)
                                    <div class="small text-muted mt-1">Status barang: {{ ucfirst($p->status_barang) }}</div>
                                @endif
                            </td>
                            <td>
                                @if(($p->denda_amount ?? 0) > 0)
                                    <div class="small">
                                        <div>Rp {{ number_format($p->denda_amount,0,',','.') }}</div>
                                        <span class="badge bg-secondary">{{ strtoupper($p->denda_status ?? 'UNPAID') }}</span>
                                        @if($p->keterlambatan_hari)
                                            <div class="text-muted">Terlambat {{ $p->keterlambatan_hari }} hari</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->status === 'approved')
                                    <button class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#returnUserModal{{ $p->id }}">
                                        <i class="bi bi-arrow-repeat me-1"></i> Saya sudah kembalikan
                                    </button>
                                @elseif(($p->denda_amount ?? 0) > 0 && ($p->denda_status ?? 'unpaid') !== 'paid')
                                    <form action="{{ route('user.peminjaman.pay-fine', $p->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Tandai denda telah dibayar?')">
                                        @csrf
        <div class="mb-2">
            <input type="text" name="catatan_pembayaran" class="form-control form-control-sm"
                   placeholder="Catatan pembayaran (opsional)">
        </div>
                                        <button class="btn btn-sm btn-success">
                                            <i class="bi bi-cash-coin me-1"></i> Bayar Denda
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @foreach($peminjamans as $p)
        @if($p->status === 'approved')
        <div class="modal fade" id="returnUserModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('user.peminjaman.return', $p->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Pengembalian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Catatan Kondisi</label>
                                <textarea name="catatan_pengembalian" rows="3" class="form-control" placeholder="Deskripsikan kondisi alat saat dikembalikan (opsional)"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto Kondisi (wajib)</label>
                                <input type="file" name="foto_pengembalian" accept="image/*" class="form-control" required>
                                <small class="text-muted">Unggah foto alat saat dikembalikan.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Kirim Pengembalian</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    @endif

</div>
@endsection
