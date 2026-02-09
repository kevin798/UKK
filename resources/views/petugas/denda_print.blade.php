<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Denda</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <style>
        body { padding: 24px; }
        .table thead th { background: #f1f3f5; }
    </style>
</head>
<body onload="window.print()">
    <h4 class="mb-1">Laporan Denda Petugas</h4>
    <p class="text-muted small mb-3">Dicetak: {{ now()->format('d M Y H:i') }}</p>

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Denda</th>
                <th>Kondisi</th>
                <th>Alasan</th>
                <th>Status</th>
                <th>Tgl</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dendaList as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->user->name ?? 'User#'.$item->user_id }}</td>
                <td>{{ $item->alat->nama ?? $item->alat->nama_alat ?? 'Alat#'.$item->alat_id }}</td>
                <td>Rp {{ number_format($item->denda_amount,0,',','.') }}</td>
                <td>{{ ucfirst($item->kondisi_pengembalian ?? '-') }}</td>
                <td style="max-width:220px;">{{ $item->denda_reason ?? '-' }}</td>
                <td>{{ ucfirst($item->denda_status ?? '-') }}</td>
                <td>{{ $item->updated_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="mt-3 fw-bold">Total denda: Rp {{ number_format($totals['nominal'],0,',','.') }} ({{ $totals['kasus'] }} kasus)</p>
</body>
</html>
