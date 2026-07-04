<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a2e; }
        .header { background: #1e1b4b; color: white; padding: 20px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p { font-size: 11px; opacity: 0.8; margin-top: 3px; }
        .meta { display: flex; gap: 30px; padding: 0 24px; margin-bottom: 16px; }
        .meta-item label { font-size: 9px; text-transform: uppercase; color: #6366f1; font-weight: bold; }
        .meta-item p { font-size: 12px; font-weight: bold; color: #1e1b4b; }
        table { width: 100%; border-collapse: collapse; margin: 0 24px; width: calc(100% - 48px); }
        thead tr { background: #1e1b4b; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 10px; font-weight: bold; }
        tbody tr:nth-child(even) { background: #f5f3ff; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e0e7ff; font-size: 10px; }
        .badge-hadir { background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 20px; font-size: 9px; }
        .badge-terlambat { background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 20px; font-size: 9px; }
        .footer { margin-top: 20px; padding: 12px 24px; border-top: 1px solid #e0e7ff; text-align: right; font-size: 9px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rekap Kehadiran Pegawai</h1>
        <p>PT Lingkar Kreatif Solusi — Dicetak pada {{ now()->isoFormat('D MMMM Y, HH:mm') }}</p>
    </div>

    <div class="meta">
        <div class="meta-item">
            <label>Periode</label>
            <p>{{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d/m/Y') }}</p>
        </div>
        <div class="meta-item">
            <label>Total Pegawai</label>
            <p>{{ $pegawaiList->count() }}</p>
        </div>
        <div class="meta-item">
            <label>Dicetak Oleh</label>
            <p>{{ auth()->user()->name }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Nama Pegawai</th>
                <th style="width: 12%">NIP</th>
                <th style="width: 13%">Jabatan</th>
                <th style="width: 13%">Divisi</th>
                <th style="width: 8%; text-align:center">Hadir</th>
                <th style="width: 9%; text-align:center">Terlambat</th>
                <th style="width: 8%; text-align:center">Izin</th>
                <th style="width: 12%; text-align:center">Total Absen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawaiList as $i => $item)
            @php
                $hadir = $item->absensi->where('status', 'hadir')->count();
                $terlambat = $item->absensi->where('status', 'terlambat')->count();
                $izin = $item->izin->count();
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->nip ?? '—' }}</td>
                <td>{{ $item->jabatan ?? '—' }}</td>
                <td>{{ $item->divisi ?? '—' }}</td>
                <td style="text-align:center"><span class="badge-hadir">{{ $hadir }}</span></td>
                <td style="text-align:center"><span class="badge-terlambat">{{ $terlambat }}</span></td>
                <td style="text-align:center">{{ $izin }}</td>
                <td style="text-align:center; font-weight: bold">{{ $hadir + $terlambat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh Sistem Absensi PT Lingkar Kreatif Solusi
    </div>
</body>
</html>
