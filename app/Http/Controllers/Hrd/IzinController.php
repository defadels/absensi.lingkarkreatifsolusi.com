<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $status    = $request->get('status', 'pending');
        $pegawaiId = $request->get('pegawai_id');

        $query = Izin::with('pegawai.user')
            ->when($status !== 'semua', fn($q) => $q->where('status_persetujuan', $status))
            ->when($pegawaiId, fn($q) => $q->where('pegawai_id', $pegawaiId))
            ->latest();

        $izin     = $query->paginate(20)->withQueryString();
        $pegawai  = Pegawai::with('user')->get();
        $pendingCount = Izin::where('status_persetujuan', 'pending')->count();

        return view('hrd.izin.index', compact('izin', 'pegawai', 'status', 'pegawaiId', 'pendingCount'));
    }

    public function show(Izin $izin)
    {
        $izin->load('pegawai.user');
        return view('hrd.izin.show', compact('izin'));
    }

    public function approve(Izin $izin)
    {
        if ($izin->status_persetujuan !== 'pending') {
            return back()->with('error', 'Izin ini sudah diproses sebelumnya.');
        }

        $izin->update([
            'status_persetujuan' => 'diterima',
            'diverifikasi_oleh'  => auth()->id(),
            'catatan_hrd'        => null,
        ]);

        return redirect()->route('hrd.izin.index')
            ->with('success', 'Pengajuan izin telah disetujui.');
    }

    public function reject(Request $request, Izin $izin)
    {
        $request->validate([
            'catatan_hrd' => ['required', 'string', 'min:5'],
        ]);

        if ($izin->status_persetujuan !== 'pending') {
            return back()->with('error', 'Izin ini sudah diproses sebelumnya.');
        }

        $izin->update([
            'status_persetujuan' => 'ditolak',
            'diverifikasi_oleh'  => auth()->id(),
            'catatan_hrd'        => $request->catatan_hrd,
        ]);

        return redirect()->route('hrd.izin.index')
            ->with('success', 'Pengajuan izin telah ditolak.');
    }
}
