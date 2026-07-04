<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function index()
    {
        $pegawai = auth()->user()->pegawai;
        $izin = Izin::where('pegawai_id', $pegawai->id)
            ->latest()->paginate(15);

        return view('pegawai.izin.index', compact('izin'));
    }

    public function create()
    {
        return view('pegawai.izin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai'   => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'jenis_izin'      => ['required', 'in:sakit,cuti,dinas'],
            'keterangan'      => ['required', 'string', 'min:10'],
            'bukti_file'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $pegawai = auth()->user()->pegawai;

        $buktiPath = null;
        if ($request->hasFile('bukti_file')) {
            $buktiPath = $request->file('bukti_file')->store('izin-bukti', 'public');
        }

        Izin::create([
            'pegawai_id'       => $pegawai->id,
            'tanggal_mulai'    => $request->tanggal_mulai,
            'tanggal_selesai'  => $request->tanggal_selesai,
            'jenis_izin'       => $request->jenis_izin,
            'keterangan'       => $request->keterangan,
            'bukti_file'       => $buktiPath,
            'status_persetujuan' => 'pending',
        ]);

        return redirect()->route('pegawai.izin.index')
            ->with('success', 'Pengajuan izin berhasil dikirim. Menunggu verifikasi HRD.');
    }
}
