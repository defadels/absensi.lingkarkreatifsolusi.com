<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function monitoring(Request $request)
    {
        $tanggal   = $request->get('tanggal', now()->toDateString());
        $pegawaiId = $request->get('pegawai_id');

        $query = Absensi::with(['pegawai.user', 'lokasi_kerja'])
            ->where('tanggal', $tanggal);

        if ($pegawaiId) {
            $query->where('pegawai_id', $pegawaiId);
        }

        $absensi = $query->latest()->paginate(20)->withQueryString();
        $pegawai = Pegawai::with('user')->get();

        // Stats for today
        $totalPegawai  = Pegawai::count();
        $totalHadir    = Absensi::where('tanggal', $tanggal)->where('status', 'hadir')->count();
        $totalTerlambat = Absensi::where('tanggal', $tanggal)->where('status', 'terlambat')->count();
        $totalAlpha    = $totalPegawai - $totalHadir - $totalTerlambat;

        return view('hrd.monitoring', compact(
            'absensi', 'pegawai', 'tanggal', 'pegawaiId',
            'totalPegawai', 'totalHadir', 'totalTerlambat', 'totalAlpha'
        ));
    }

    public function laporan(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $tanggalMulai  = \Carbon\Carbon::create($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $tanggalSelesai = \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth()->toDateString();

        $pegawaiList = Pegawai::with([
            'user',
            'absensi' => fn($q) => $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]),
            'izin'    => fn($q) => $q->where('status_persetujuan', 'diterima')
                ->where(fn($q2) => $q2->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                    ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai])),
        ])->get();

        return view('hrd.laporan', compact('pegawaiList', 'bulan', 'tahun', 'tanggalMulai', 'tanggalSelesai'));
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $tanggalMulai  = \Carbon\Carbon::create($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $tanggalSelesai = \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth()->toDateString();

        $bulanNama = \Carbon\Carbon::create($tahun, $bulan, 1)->translatedFormat('F');

        $pegawaiList = Pegawai::with([
            'user',
            'absensi' => fn($q) => $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]),
            'izin'    => fn($q) => $q->where('status_persetujuan', 'diterima')
                ->where(fn($q2) => $q2->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                    ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai])),
        ])->get();

        $pdf = Pdf::loadView('pdf.laporan-bulanan', compact(
            'pegawaiList', 'bulan', 'tahun', 'bulanNama', 'tanggalMulai', 'tanggalSelesai'
        ))->setPaper('a4', 'landscape');

        return $pdf->download("laporan-kehadiran-{$bulanNama}-{$tahun}.pdf");
    }

    public function dashboard()
    {
        return redirect()->route('hrd.monitoring');
    }
}
