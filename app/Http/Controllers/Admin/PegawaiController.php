<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\LokasiKerja;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::with(['user', 'lokasiKerja'])->latest();

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('nip', 'like', '%' . $request->search . '%')
              ->orWhere('jabatan', 'like', '%' . $request->search . '%')
              ->orWhere('divisi', 'like', '%' . $request->search . '%');
        }

        // Filter by lokasi
        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_kerja_id', $request->lokasi_id);
        }

        $pegawai  = $query->paginate(15)->withQueryString();
        $lokasiList = LokasiKerja::orderBy('nama_lokasi')->get();

        return view('admin.pegawai.index', compact('pegawai', 'lokasiList'));
    }

    public function create()
    {
        $lokasiList = LokasiKerja::where('is_active', true)->orderBy('nama_lokasi')->get();

        return view('admin.pegawai.create', compact('lokasiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'no_hp'           => ['nullable', 'string', 'max:20'],
            'role'            => ['required', 'in:admin,pegawai,hrd'],
            'password'        => ['required', 'confirmed', Rules\Password::defaults()],
            'nip'             => ['nullable', 'string', 'max:50', 'unique:pegawai,nip'],
            'jabatan'         => ['nullable', 'string', 'max:100'],
            'divisi'          => ['nullable', 'string', 'max:100'],
            'alamat'          => ['nullable', 'string'],
            'lokasi_kerja_id' => [
                'nullable',
                'integer',
                Rule::exists('lokasi_kerja', 'id')->where('is_active', true),
            ],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'no_hp'    => $validated['no_hp'] ?? null,
                'role'     => $validated['role'],
                'password' => Hash::make($validated['password']),
            ]);

            return Pegawai::create([
                'user_id'        => $user->id,
                'lokasi_kerja_id' => $validated['lokasi_kerja_id'] ?? null,
                'nip'             => $validated['nip'] ?? null,
                'jabatan'         => $validated['jabatan'] ?? null,
                'divisi'          => $validated['divisi'] ?? null,
                'no_hp'           => $validated['no_hp'] ?? null,
                'alamat'          => $validated['alamat'] ?? null,
            ]);
        });

        return redirect()->route('admin.pegawai.index')
            ->with('success', "Akun {$validated['name']} berhasil ditambahkan.");
    }

    public function show(Pegawai $pegawai)
    {
        $pegawai->load(['user', 'lokasiKerja']);
        $absensiTerbaru = Absensi::with('lokasi_kerja')
            ->where('pegawai_id', $pegawai->id)
            ->latest('tanggal')->take(10)->get();

        return view('admin.pegawai.show', compact('pegawai', 'absensiTerbaru'));
    }

    public function edit(Pegawai $pegawai)
    {
        $pegawai->load(['user', 'lokasiKerja']);
        $lokasiList = LokasiKerja::where('is_active', true)->orderBy('nama_lokasi')->get();
        return view('admin.pegawai.edit', compact('pegawai', 'lokasiList'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nip'             => ['nullable', 'string', 'max:50', 'unique:pegawai,nip,' . $pegawai->id],
            'jabatan'         => ['nullable', 'string', 'max:100'],
            'divisi'          => ['nullable', 'string', 'max:100'],
            'no_hp'           => ['nullable', 'string', 'max:20'],
            'alamat'          => ['nullable', 'string'],
            'name'            => ['required', 'string', 'max:255'],
            'role'            => ['required', 'in:pegawai,hrd,admin'],
            'lokasi_kerja_id' => ['nullable', 'integer', 'exists:lokasi_kerja,id'],
        ]);

        $pegawai->user->update([
            'name' => $request->name,
            'role' => $request->role,
        ]);

        $pegawai->update([
            'nip'             => $request->nip,
            'jabatan'         => $request->jabatan,
            'divisi'          => $request->divisi,
            'no_hp'           => $request->no_hp,
            'alamat'          => $request->alamat,
            'lokasi_kerja_id' => $request->lokasi_kerja_id ?: null,
        ]);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        $user = $pegawai->user;

        // Hapus data terkait terlebih dahulu untuk menghindari foreign key constraint
        $pegawai->absensi()->delete();
        $pegawai->izin()->delete();
        $pegawai->delete();

        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }
}
