<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\LokasiKerja;
use App\Models\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal  = $request->get('tanggal', now()->toDateString());
        $pegawaiId = $request->get('pegawai_id');
        $lokasiId  = $request->get('lokasi_id');

        $query = Absensi::with(['pegawai.user', 'pegawai.lokasiKerja', 'lokasi_kerja'])
            ->where('tanggal', $tanggal);

        if ($pegawaiId) {
            $query->where('pegawai_id', $pegawaiId);
        }

        if ($lokasiId) {
            $query->where('lokasi_kerja_id', $lokasiId);
        }

        $absensi    = $query->latest()->paginate(20)->withQueryString();
        $pegawai    = Pegawai::with('user')->get();
        $lokasiList = LokasiKerja::orderBy('nama_lokasi')->get();

        return view('admin.absensi.index', compact(
            'absensi', 'pegawai', 'lokasiList', 'tanggal', 'pegawaiId', 'lokasiId'
        ));
    }

    public function show(Absensi $absensi)
    {
        $absensi->load(['pegawai.user', 'pegawai.lokasiKerja', 'lokasi_kerja']);

        $jarakMasuk = $this->resolveJarak(
            $absensi->jarak_masuk_meter,
            $absensi->latitude_masuk,
            $absensi->longitude_masuk,
            $absensi->lokasi_kerja
        );
        $jarakPulang = $this->resolveJarak(
            $absensi->jarak_pulang_meter,
            $absensi->latitude_pulang,
            $absensi->longitude_pulang,
            $absensi->lokasi_kerja
        );

        return view('admin.absensi.show', compact('absensi', 'jarakMasuk', 'jarakPulang'));
    }

    private function resolveJarak(?int $jarakTersimpan, $latitude, $longitude, ?LokasiKerja $lokasi): ?int
    {
        if ($jarakTersimpan !== null) {
            return (int) round($jarakTersimpan);
        }

        if ($latitude === null || $longitude === null || !$lokasi) {
            return null;
        }

        return (int) round($this->hitungJarak(
            (float) $latitude,
            (float) $longitude,
            (float) $lokasi->latitude,
            (float) $lokasi->longitude
        ));
    }

    private function hitungJarak(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    public function rekap(Request $request)
    {
        $tanggalMulai   = $request->get('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggalSelesai = $request->get('tanggal_selesai', now()->toDateString());
        $pegawaiId      = $request->get('pegawai_id');
        $lokasiId       = $request->get('lokasi_id');

        $pegawaiList = Pegawai::with([
            'user',
            'lokasiKerja',
            'absensi' => function ($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
            },
            'izin' => function ($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->where('status_persetujuan', 'diterima')
                  ->where(function ($q2) use ($tanggalMulai, $tanggalSelesai) {
                      $q2->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                         ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai]);
                  });
            },
        ])
        ->when($pegawaiId, fn($q) => $q->where('id', $pegawaiId))
        ->when($lokasiId, fn($q) => $q->where('lokasi_kerja_id', $lokasiId))
        ->get();

        $pegawaiDropdown = Pegawai::with('user')->get();
        $lokasiList      = LokasiKerja::orderBy('nama_lokasi')->get();

        return view('admin.absensi.rekap', compact(
            'pegawaiList', 'tanggalMulai', 'tanggalSelesai',
            'pegawaiDropdown', 'lokasiList', 'pegawaiId', 'lokasiId'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tanggalMulai   = $request->get('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggalSelesai = $request->get('tanggal_selesai', now()->toDateString());
        $pegawaiId      = $request->get('pegawai_id');
        $lokasiId       = $request->get('lokasi_id');

        $pegawaiList = Pegawai::with([
            'user',
            'lokasiKerja',
            'absensi' => function ($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
            },
            'izin' => function ($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->where('status_persetujuan', 'diterima')
                  ->where(function ($q2) use ($tanggalMulai, $tanggalSelesai) {
                      $q2->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                         ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai]);
                  });
            },
        ])
        ->when($pegawaiId, fn($q) => $q->where('id', $pegawaiId))
        ->when($lokasiId, fn($q) => $q->where('lokasi_kerja_id', $lokasiId))
        ->get();

        $pdf = Pdf::loadView('pdf.rekap-absensi', compact(
            'pegawaiList', 'tanggalMulai', 'tanggalSelesai'
        ))->setPaper('a4', 'landscape');

        return $pdf->download("rekap-absensi-{$tanggalMulai}-sd-{$tanggalSelesai}.pdf");
    }
}
