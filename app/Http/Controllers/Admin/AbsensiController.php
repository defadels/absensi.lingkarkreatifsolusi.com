<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal  = $request->get('tanggal', now()->toDateString());
        $pegawaiId = $request->get('pegawai_id');

        $query = Absensi::with(['pegawai.user', 'lokasi_kerja'])
            ->where('tanggal', $tanggal);

        if ($pegawaiId) {
            $query->where('pegawai_id', $pegawaiId);
        }

        $absensi  = $query->latest()->paginate(20)->withQueryString();
        $pegawai  = Pegawai::with('user')->get();

        return view('admin.absensi.index', compact('absensi', 'pegawai', 'tanggal', 'pegawaiId'));
    }

    public function show(Absensi $absensi)
    {
        $absensi->load(['pegawai.user', 'lokasi_kerja']);
        return view('admin.absensi.show', compact('absensi'));
    }

    public function rekap(Request $request)
    {
        $tanggalMulai  = $request->get('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggalSelesai = $request->get('tanggal_selesai', now()->toDateString());
        $pegawaiId     = $request->get('pegawai_id');

        $pegawaiList = Pegawai::with(['user', 'absensi' => function ($q) use ($tanggalMulai, $tanggalSelesai) {
            $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        }, 'izin' => function ($q) use ($tanggalMulai, $tanggalSelesai) {
            $q->where('status_persetujuan', 'diterima')
              ->where(function ($q2) use ($tanggalMulai, $tanggalSelesai) {
                  $q2->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                     ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai]);
              });
        }])->when($pegawaiId, fn($q) => $q->where('id', $pegawaiId))->get();

        $pegawaiDropdown = Pegawai::with('user')->get();

        return view('admin.absensi.rekap', compact(
            'pegawaiList', 'tanggalMulai', 'tanggalSelesai', 'pegawaiDropdown', 'pegawaiId'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tanggalMulai  = $request->get('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggalSelesai = $request->get('tanggal_selesai', now()->toDateString());
        $pegawaiId     = $request->get('pegawai_id');

        $pegawaiList = Pegawai::with(['user', 'absensi' => function ($q) use ($tanggalMulai, $tanggalSelesai) {
            $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        }, 'izin' => function ($q) use ($tanggalMulai, $tanggalSelesai) {
            $q->where('status_persetujuan', 'diterima')
              ->where(function ($q2) use ($tanggalMulai, $tanggalSelesai) {
                  $q2->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                     ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai]);
              });
        }])->when($pegawaiId, fn($q) => $q->where('id', $pegawaiId))->get();

        $pdf = Pdf::loadView('pdf.rekap-absensi', compact(
            'pegawaiList', 'tanggalMulai', 'tanggalSelesai'
        ))->setPaper('a4', 'landscape');

        return $pdf->download("rekap-absensi-{$tanggalMulai}-sd-{$tanggalSelesai}.pdf");
    }
}
