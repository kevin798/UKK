<div class="mb-4">
        <h4 class="fw-bold mb-1">Daftar Alat</h4>
        <p class="text-muted small mb-0">
            Lihat daftar lengkap alat yang tersedia untuk dipinjam
        </p>
    </div>
=======
    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Daftar Alat</h4>
        <p class="text-muted small mb-0">
            Lihat daftar lengkap alat yang tersedia untuk dipinjam
        </p>
    </div>

    <!-- FILTER KATEGORI -->
    <div class="mb-4">
        <form method="GET" action="{{ route('user.alat-list') }}" class="d-flex gap-3 align-items-end">
            <div class="flex-grow-1">
                <label for="kategori_id" class="form-label fw-medium">Filter berdasarkan Kategori</label>
                <select name="kategori_id" id="kategori_id" class="form-select">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
        </form>
    </div>
