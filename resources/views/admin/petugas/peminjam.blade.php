@extends('layouts.app')

@section('title', 'Data Peminjam')

@section('content')
<div class="container pt-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Data Peminjam</h4>
            </div>

            <!-- ALERT SUCCESS -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- ALERT ERROR -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-secondary text-uppercase small">
                            <th>Nama</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>Foto</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjam as $item)
                        @php
                            $path = $item->photo;
                            if ($path && str_starts_with($path,'public/')) {
                                $path = \Illuminate\Support\Str::after($path,'public/');
                            }
                            $photoUrl = $path ? asset('storage/'.$path) : '';
                        @endphp
                        <tr>
                            <td class="fw-medium">{{ $item->name }}</td>
                            <td class="text-muted">
                                <div>{{ $item->email }}</div>
                                @if($item->phone)
                                    <div class="small text-muted">Telp: {{ $item->phone }}</div>
                                @endif
                            </td>
                            <td class="text-muted small" style="max-width:220px;">{{ $item->address ?? '-' }}</td>
                            <td>
                                @php
                                    $path = $item->photo;
                                    if ($path && str_starts_with($path,'public/')) $path = \Illuminate\Support\Str::after($path,'public/');
                                    $photoUrl = $path ? asset('storage/'.$path) : null;
                                @endphp
                                @if($photoUrl)
                                    <img src="{{ $photoUrl }}" alt="Foto {{ $item->name }}" style="width:42px;height:42px;object-fit:cover;border-radius:8px;">
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success-subtle text-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-secondary rounded-pill px-3 btn-view"
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->name }}"
                                            data-email="{{ $item->email }}"
                                            data-phone="{{ $item->phone }}"
                                            data-address="{{ $item->address }}"
                                            data-photo="{{ $photoUrl }}"
                                            data-role="{{ ucfirst($item->role) }}"
                                            data-status="{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}"
                                            data-joined="{{ $item->created_at->format('d M Y') }}">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <form action="{{ route('admin.peminjam.delete', $item->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus peminjam ini?')">
                                        @csrf
                                        @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            Hapus
                                    </button>
                                </form>

                                    <form action="{{ route('admin.peminjam.toggle', $item->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Ubah status peminjam ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="btn btn-sm {{ $item->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }} rounded-pill px-3">
                                            {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-4 d-block mb-2"></i>
                                Tidak ada data peminjam
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="d-flex justify-content-end mt-4">
                {{ $peminjam->links() }}
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const template = `
    <div class="modal fade" id="peminjamDetailModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">Detail Peminjam</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="d-flex gap-3 mb-3 align-items-center">
              <div id="pd-photo-wrapper" class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width:72px;height:72px;">
                <img id="pd-photo" src="" alt="Foto" style="width:72px;height:72px;object-fit:cover;border-radius:50%;display:none;">
                <i id="pd-photo-placeholder" class="bi bi-person text-muted" style="font-size:32px;"></i>
              </div>
              <div>
                <div class="fw-bold fs-6" id="pd-name"></div>
                <div class="text-muted small" id="pd-role"></div>
                <span class="badge bg-secondary mt-2" id="pd-status"></span>
              </div>
            </div>
            <div class="border rounded p-3 bg-light">
              <dl class="row small mb-0">
                <dt class="col-4">Email</dt><dd class="col-8 mb-1" id="pd-email"></dd>
                <dt class="col-4">Phone</dt><dd class="col-8 mb-1" id="pd-phone"></dd>
                <dt class="col-4">Alamat</dt><dd class="col-8 mb-1" id="pd-address"></dd>
                <dt class="col-4">Terdaftar</dt><dd class="col-8 mb-0" id="pd-joined"></dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>`;

    if (!document.getElementById('peminjamDetailModal')) {
        const div = document.createElement('div');
        div.innerHTML = template;
        document.body.appendChild(div);
    }

    const modalEl = document.getElementById('peminjamDetailModal');
    const modal = new bootstrap.Modal(modalEl);

    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', () => {
            const photo = btn.dataset.photo;
            const photoEl = document.getElementById('pd-photo');
            const placeholder = document.getElementById('pd-photo-placeholder');
            photoEl.style.display = photo ? 'block' : 'none';
            placeholder.style.display = photo ? 'none' : 'block';
            if (photo) photoEl.src = photo;

            document.getElementById('pd-name').textContent = btn.dataset.name || '-';
            document.getElementById('pd-role').textContent = btn.dataset.role || '-';
            document.getElementById('pd-status').textContent = btn.dataset.status || '-';
            document.getElementById('pd-email').textContent = btn.dataset.email || '-';
            document.getElementById('pd-phone').textContent = btn.dataset.phone || '-';
            document.getElementById('pd-address').textContent = btn.dataset.address || '-';
            document.getElementById('pd-joined').textContent = btn.dataset.joined || '-';
            modal.show();
        });
    });
});
</script>
@endpush
