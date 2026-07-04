<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::with('user')->latest();

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('nip', 'like', '%' . $request->search . '%')
              ->orWhere('jabatan', 'like', '%' . $request->search . '%')
              ->orWhere('divisi', 'like', '%' . $request->search . '%');
        }

        $pegawai = $query->paginate(15)->withQueryString();

        return view('admin.pegawai.index', compact('pegawai'));
    }

    public function show(Pegawai $pegawai)
    {
        $pegawai->load('user');
        $absensiTerbaru = Absensi::where('pegawai_id', $pegawai->id)
            ->latest('tanggal')->take(10)->get();

        return view('admin.pegawai.show', compact('pegawai', 'absensiTerbaru'));
    }

    public function edit(Pegawai $pegawai)
    {
        $pegawai->load('user');
        return view('admin.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nip'     => ['nullable', 'string', 'max:50', 'unique:pegawai,nip,' . $pegawai->id],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'divisi'  => ['nullable', 'string', 'max:100'],
            'no_hp'   => ['nullable', 'string', 'max:20'],
            'alamat'  => ['nullable', 'string'],
            'name'    => ['required', 'string', 'max:255'],
            'role'    => ['required', 'in:pegawai,hrd,admin'],
        ]);

        $pegawai->user->update([
            'name' => $request->name,
            'role' => $request->role,
        ]);

        $pegawai->update([
            'nip'     => $request->nip,
            'jabatan' => $request->jabatan,
            'divisi'  => $request->divisi,
            'no_hp'   => $request->no_hp,
            'alamat'  => $request->alamat,
        ]);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        $user = $pegawai->user;
        $pegawai->delete();
        $user->delete();

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }
}
