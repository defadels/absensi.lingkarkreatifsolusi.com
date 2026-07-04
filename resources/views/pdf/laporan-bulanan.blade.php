<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan Kehadiran</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a2e; }
        .header { background: #1e1b4b; color: white; padding: 20px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header .sub { font-size: 13px; opacity: 0.9; margin-top: 2px; }
        .header p { font-size: 10px; opacity: 0.7; margin-top: 4px; }
        .meta { display: flex; gap: 24px; padding: 0 24px; margin-bottom: 16px; }
        .meta-item label { font-size: 9px; text-transform: uppercase; color: #6366f1; font-weight: bold; }
        .meta-item p { font-size: 12px; font-weight: bold; }
        table { width: calc(100% - 48px); margin: 0 24px; border-collapse: collapse; }
        thead tr { background: #1e1b4b; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 10px; }
        tbody tr:nth-child(even) { background: #f5f3ff; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e0e7ff; font-size: 10px; }
        .num { text-align: center; }
        .persen-bar { background: #e0e7ff; height: 8px; border-radius: 4px; display: inline-block; width: 60px; vertical-align: middle; margin-right: 4px; }
        .persen-fill { background: #6366f1; height: 8px; border-radius: 4px; display: inline-block; }
        .sign-area { margin: 30px 24px 0; display: flex; justify-content: flex-end; }
        .sign-box { text-align: center; width: 200px; }
        .sign-box .sign-line { border-top: 1px solid #1e1b4b; margin-top: 50px; padding-top: 4px; font-size: 10px; }
        .footer { margin-top: 20px; padding: 10px 24px; border-top: 1px solid #e0e7ff; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Kehadiran Bulanan</h1>
        <p class="sub">PT Lingkar Kreatif Solusi</p>
        <p>Dicetak pada {{ now()->isoFormat('D MMMM Y, HH:mm') }}</p>
    </div>

    <div class="meta">
        <div class="meta-item">
            <label>Periode</label>
            <p>{{ $bulanNama }} {{ $tahun }}</p>
        </div>
        <div class="meta-item">
            <label>Total Pegawai</label>
            <p>{{ $pegawaiList->count() }}</p>
        </div>
        <div class="meta-item">
            <label>Hari Kalender</label>
            <p>{{ \Carbon\Carbon::parse($tanggalMulai)->diffInDays(\Carbon\Carbon::parse($tanggalSelesai)) + 1 }} hari</p>
        </div>
        <div class="meta-item">
            <label>HRD</label>
            <p>{{ auth()->user()->name }}</p>
        </div>
    </div>

    @php $hariKerja = \Carbon\Carbon::parse($tanggalMulai)->diffInDays(\Carbon\Carbon::parse($tanggalSelesai)) + 1; @endphp
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>NIP</th>
                <th>Jabatan</th>
                <th>Divisi</th>
                <th class="num">Hadir</th>
                <th class="num">Terlambat</th>
                <th class="num">Izin</th>
                <th class="num">Alpha</th>
                <th class="num">Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawaiList as $i => $item)
            @php
                $hadir = $item->absensi->where('status', 'hadir')->count();
                $terlambat = $item->absensi->where('status', 'terlambat')->count();
                $izin = $item->izin->count();
                $totalAbsen = $hadir + $terlambat;
                $alpha = max(0, $hariKerja - $totalAbsen - $izin);
                $persen = $hariKerja > 0 ? round(($totalAbsen / $hariKerja) * 100) : 0;
            @endphp
            <tr>
                <td class="num">{{ $i + 1 }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->nip ?? '—' }}</td>
                <td>{{ $item->jabatan ?? '—' }}</td>
                <td>{{ $item->divisi ?? '—' }}</td>
                <td class="num" style="color: #059669; font-weight: bold">{{ $hadir }}</td>
                <td class="num" style="color: #d97706; font-weight: bold">{{ $terlambat }}</td>
                <td class="num" style="color: #7c3aed">{{ $izin }}</td>
                <td class="num" style="color: #dc2626">{{ $alpha }}</td>
                <td class="num">{{ $persen }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="sign-area">
        <div class="sign-box">
            <p style="font-size:10px">Jakarta, {{ now()->isoFormat('D MMMM Y') }}</p>
            <p style="font-size:10px; margin-top: 4px">Mengetahui,</p>
            <div class="sign-line">HRD Manager<br>PT Lingkar Kreatif Solusi</div>
        </div>
    </div>

    <div class="footer">
        Dokumen resmi. Digenerate otomatis oleh Sistem Absensi PT Lingkar Kreatif Solusi. Periode: {{ $bulanNama }} {{ $tahun }}.
    </div>
</body>
</html>
