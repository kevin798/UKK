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
                                <small class="text-muted">{{ $p->alat->kategori->nama ?? '-' }}</small>
                            </td>
                            <td>{{ $p->jumlah }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}</td>
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
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#returnModal{{ $p->id }}">
                                        <i class="bi bi-arrow-repeat me-1"></i>Kembalikan
                                    </button>

                                    <!-- Modal Pengembalian -->
                                    <div class="modal fade" id="returnModal{{ $p->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Konfirmasi Pengembalian</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('user.peminjaman.return', $p->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p class="mb-3 text-center"><strong>{{ $p->alat->nama ?? 'Alat #'.$p->alat_id }}</strong></p>

                                                        <!-- Gambar Alat Referensi dan Upload -->
                                                        <div class="row g-3 mb-4">
                                                            <!-- Gambar Alat Referensi -->
                                                            <div class="col-6">
                                                                <small class="text-muted d-block mb-2 fw-semibold">Gambar Referensi</small>
                                                                <div class="text-center">
                                                                    @if($p->alat->gambar)
                                                                        <img src="{{ asset('storage/'.$p->alat->gambar) }}"
                                                                             alt="gambar alat"
                                                                             class="rounded shadow-sm"
                                                                             style="max-width: 100%; max-height: 150px; object-fit: cover;">
                                                                    @else
                                                                        <div class="bg-light rounded p-4">
                                                                            <i class="bi bi-image fs-5 text-muted"></i>
                                                                            <p class="text-muted small mt-2">Tidak ada gambar</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <!-- Upload Gambar Kondisi -->
                                                            <div class="col-6">
                                                                <small class="text-muted d-block mb-2 fw-semibold">Foto Kondisi Saat Dikembalikan</small>
                                                                <div class="text-center">
                                                                    <div class="border-2 border-dashed rounded p-3" style="border-color: #dee2e6;">
                                                                        <input type="file" id="gambar_pengembalian{{ $p->id }}"
                                                                               name="gambar_pengembalian" accept="image/*"
                                                                               class="d-none"
                                                                               onchange="previewImage(this, 'preview-{{ $p->id }}')">

                                                                        <div id="preview-{{ $p->id }}">
                                                                            <label for="gambar_pengembalian{{ $p->id }}" style="cursor: pointer;">
                                                                                <i class="bi bi-cloud-upload fs-5 text-primary"></i>
                                                                                <p class="text-primary small fw-medium mt-2 mb-0">Klik untuk upload</p>
                                                                                <small class="text-muted">atau drag & drop</small>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="kondisi{{ $p->id }}" class="form-label fw-medium">
                                                                Kondisi Alat <span class="text-danger">*</span>
                                                            </label>
                                                            <select id="kondisi{{ $p->id }}" name="kondisi"
                                                                    class="form-select" required>
                                                                <option value="">-- Pilih Kondisi --</option>
                                                                <option value="baik">Baik</option>
                                                                <option value="rusak">Rusak</option>
                                                                <option value="hilang">Hilang</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="catatan{{ $p->id }}" class="form-label fw-medium">
                                                                Catatan (Opsional)
                                                            </label>
                                                            <textarea id="catatan{{ $p->id }}" name="catatan"
                                                                      class="form-control" rows="3"
                                                                      placeholder="Deskripsi lebih detail mengenai kondisi alat..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class=" me-1"></i>Konfirmasi
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
    @endif

</div>

<script>
function previewImage(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="position-relative">
                    <img src="${e.target.result}" alt="preview" style="max-width: 100%; max-height: 150px; object-fit: cover; border-radius: 0.375rem;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 mt-1 me-1"
                            onclick="clearImage('${input.id}', '${previewId}')">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }
}

function clearImage(inputId, previewId) {
    document.getElementById(inputId).value = '';
    const preview = document.getElementById(previewId);
    const inputElement = document.getElementById(inputId);

    preview.innerHTML = `
        <label for="${inputId}" style="cursor: pointer;">
            <i class="bi bi-cloud-upload fs-5 text-primary"></i>
            <p class="text-primary small fw-medium mt-2 mb-0">Klik untuk upload</p>
            <small class="text-muted">atau drag & drop</small>
        </label>
    `;
}
</script>
@endsection
