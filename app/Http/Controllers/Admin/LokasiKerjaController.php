<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LokasiKerja;
use Illuminate\Http\Request;

class LokasiKerjaController extends Controller
{
    public function index()
    {
        $lokasi = LokasiKerja::latest()->get();
        return view('admin.lokasi-kerja.index', compact('lokasi'));
    }

    public function create()
    {
        return view('admin.lokasi-kerja.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi'       => ['required', 'string', 'max:255'],
            'latitude'          => ['required', 'numeric', 'between:-90,90'],
            'longitude'         => ['required', 'numeric', 'between:-180,180'],
            'radius_meter'      => ['required', 'integer', 'min:10', 'max:5000'],
            'jam_masuk_standar' => ['required', 'date_format:H:i'],
            'toleransi_menit'   => ['required', 'integer', 'min:0', 'max:120'],
        ]);

        LokasiKerja::create([
            'nama_lokasi'       => $request->nama_lokasi,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'radius_meter'      => $request->radius_meter,
            'jam_masuk_standar' => $request->jam_masuk_standar . ':00',
            'toleransi_menit'   => $request->toleransi_menit,
            'is_active'         => true,
        ]);

        return redirect()->route('admin.lokasi-kerja.index')
            ->with('success', 'Lokasi kerja berhasil ditambahkan.');
    }

    public function edit(LokasiKerja $lokasiKerja)
    {
        return view('admin.lokasi-kerja.edit', compact('lokasiKerja'));
    }

    public function update(Request $request, LokasiKerja $lokasiKerja)
    {
        $request->validate([
            'nama_lokasi'       => ['required', 'string', 'max:255'],
            'latitude'          => ['required', 'numeric', 'between:-90,90'],
            'longitude'         => ['required', 'numeric', 'between:-180,180'],
            'radius_meter'      => ['required', 'integer', 'min:10', 'max:5000'],
            'jam_masuk_standar' => ['required', 'date_format:H:i'],
            'toleransi_menit'   => ['required', 'integer', 'min:0', 'max:120'],
        ]);

        $lokasiKerja->update([
            'nama_lokasi'       => $request->nama_lokasi,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'radius_meter'      => $request->radius_meter,
            'jam_masuk_standar' => $request->jam_masuk_standar . ':00',
            'toleransi_menit'   => $request->toleransi_menit,
        ]);

        return redirect()->route('admin.lokasi-kerja.index')
            ->with('success', 'Lokasi kerja berhasil diperbarui.');
    }

    public function toggleActive(LokasiKerja $lokasiKerja)
    {
        $lokasiKerja->update(['is_active' => !$lokasiKerja->is_active]);

        $status = $lokasiKerja->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Lokasi kerja berhasil {$status}.");
    }

    public function destroy(LokasiKerja $lokasiKerja)
    {
        $lokasiKerja->delete();
        return redirect()->route('admin.lokasi-kerja.index')
            ->with('success', 'Lokasi kerja berhasil dihapus.');
    }
}
