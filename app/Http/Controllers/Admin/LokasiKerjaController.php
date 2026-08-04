<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LokasiKerja;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class LokasiKerjaController extends Controller
{
    public function index()
    {
        $lokasi = LokasiKerja::withCount('pegawai')->latest()->get();
        return view('admin.lokasi-kerja.index', compact('lokasi'));
    }

    public function create()
    {
        // Pegawai yang belum punya lokasi kerja diprioritaskan,
        // tetapi semua pegawai tetap bisa dipilih (untuk re-assign)
        $pegawaiList = Pegawai::with('user')
            ->orderByRaw('lokasi_kerja_id IS NOT NULL')
            ->get();

        return view('admin.lokasi-kerja.create', compact('pegawaiList'));
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
            'pegawai_ids'       => ['nullable', 'array'],
            'pegawai_ids.*'     => ['integer', 'exists:pegawai,id'],
        ]);

        $lokasi = LokasiKerja::create([
            'nama_lokasi'       => $request->nama_lokasi,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'radius_meter'      => $request->radius_meter,
            'jam_masuk_standar' => $request->jam_masuk_standar . ':00',
            'toleransi_menit'   => $request->toleransi_menit,
            'is_active'         => true,
        ]);

        // Tetapkan karyawan yang dipilih ke lokasi ini
        if ($request->filled('pegawai_ids')) {
            Pegawai::whereIn('id', $request->pegawai_ids)
                ->update(['lokasi_kerja_id' => $lokasi->id]);
        }

        return redirect()->route('admin.lokasi-kerja.index')
            ->with('success', 'Lokasi kerja berhasil ditambahkan dan karyawan telah ditetapkan.');
    }

    public function edit(LokasiKerja $lokasiKerja)
    {
        $lokasiKerja->load('pegawai.user');

        // Semua pegawai: yang sudah di sini + yang belum punya lokasi
        $pegawaiList = Pegawai::with('user')
            ->where(function ($q) use ($lokasiKerja) {
                $q->whereNull('lokasi_kerja_id')
                  ->orWhere('lokasi_kerja_id', $lokasiKerja->id);
            })
            ->get();

        // ID pegawai yang sudah ditetapkan ke lokasi ini
        $assignedIds = $lokasiKerja->pegawai->pluck('id')->toArray();

        return view('admin.lokasi-kerja.edit', compact('lokasiKerja', 'pegawaiList', 'assignedIds'));
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
            'pegawai_ids'       => ['nullable', 'array'],
            'pegawai_ids.*'     => ['integer', 'exists:pegawai,id'],
        ]);

        $lokasiKerja->update([
            'nama_lokasi'       => $request->nama_lokasi,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'radius_meter'      => $request->radius_meter,
            'jam_masuk_standar' => $request->jam_masuk_standar . ':00',
            'toleransi_menit'   => $request->toleransi_menit,
        ]);

        // Hapus ketetapan lama untuk lokasi ini (yang tidak dipilih lagi)
        Pegawai::where('lokasi_kerja_id', $lokasiKerja->id)
            ->update(['lokasi_kerja_id' => null]);

        // Set karyawan yang baru dipilih
        if ($request->filled('pegawai_ids')) {
            Pegawai::whereIn('id', $request->pegawai_ids)
                ->update(['lokasi_kerja_id' => $lokasiKerja->id]);
        }

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
        // Karena onDelete('set null'), FK pegawai.lokasi_kerja_id otomatis null
        $lokasiKerja->delete();
        return redirect()->route('admin.lokasi-kerja.index')
            ->with('success', 'Lokasi kerja berhasil dihapus. Karyawan terkait perlu ditetapkan ulang.');
    }
}
