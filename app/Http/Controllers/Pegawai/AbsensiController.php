<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\LokasiKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    /**
     * Calculate Haversine distance between two coordinates (in meters).
     */
    private function hitungJarak(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
           * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Dapatkan lokasi kerja yang ditetapkan untuk karyawan login.
     * Mengembalikan null jika belum ditetapkan.
     */
    private function getLokasiPegawai(): ?LokasiKerja
    {
        $pegawai = auth()->user()->pegawai;

        if (!$pegawai || !$pegawai->lokasi_kerja_id) {
            return null;
        }

        return $pegawai->lokasiKerja;
    }

    public function dashboard()
    {
        $user    = auth()->user();
        $pegawai = $user->pegawai;

        if (!$pegawai) {
            return view('pegawai.dashboard', [
                'absensiHariIni' => null,
                'pegawai'        => null,
                'lokasiAktif'    => null,
            ]);
        }

        $absensiHariIni = Absensi::where('pegawai_id', $pegawai->id)
            ->where('tanggal', now()->toDateString())
            ->first();

        // Lokasi yang ditetapkan untuk karyawan ini
        $lokasiAktif = $this->getLokasiPegawai();

        return view('pegawai.dashboard', compact('absensiHariIni', 'pegawai', 'lokasiAktif'));
    }

    public function masukForm()
    {
        $pegawai     = auth()->user()->pegawai;
        $lokasiAktif = $this->getLokasiPegawai();

        if (!$lokasiAktif) {
            return back()->with('error', 'Anda belum ditetapkan lokasi kerja. Silakan hubungi Admin.');
        }

        $absensiHariIni = Absensi::where('pegawai_id', $pegawai->id)
            ->where('tanggal', now()->toDateString())->first();

        if ($absensiHariIni && $absensiHariIni->jam_masuk) {
            return redirect()->route('pegawai.dashboard')
                ->with('info', 'Anda sudah melakukan absen masuk hari ini.');
        }

        return view('pegawai.absensi.masuk', compact('lokasiAktif'));
    }

    public function masukStore(Request $request)
    {
        $request->validate([
            'latitude'  => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'foto'      => ['required', 'string'], // base64 image
        ]);

        $pegawai     = auth()->user()->pegawai;
        $lokasiAktif = $this->getLokasiPegawai();

        if (!$lokasiAktif) {
            return response()->json([
                'error' => 'Anda belum ditetapkan lokasi kerja. Silakan hubungi Admin.'
            ], 422);
        }

        // Server-side Haversine validation against assigned location
        $jarak = $this->hitungJarak(
            $request->latitude, $request->longitude,
            $lokasiAktif->latitude, $lokasiAktif->longitude
        );

        if ($jarak > $lokasiAktif->radius_meter) {
            return response()->json([
                'error' => "Anda berada di luar area {$lokasiAktif->nama_lokasi}. " .
                           "Jarak Anda: " . round($jarak) . " meter (maks: {$lokasiAktif->radius_meter} meter)."
            ], 422);
        }

        // Check if already checked in today
        $existing = Absensi::where('pegawai_id', $pegawai->id)
            ->where('tanggal', now()->toDateString())->first();

        if ($existing && $existing->jam_masuk) {
            return response()->json(['error' => 'Anda sudah absen masuk hari ini.'], 422);
        }

        // Save photo from base64
        $fotoPath = $this->simpanFotoBase64($request->foto, 'masuk');

        // Determine status: hadir or terlambat
        $batasWaktu = now()->setTimeFromTimeString($lokasiAktif->jam_masuk_standar)
            ->addMinutes((int) $lokasiAktif->toleransi_menit);
        $status = now()->greaterThan($batasWaktu) ? 'terlambat' : 'hadir';

        Absensi::create([
            'pegawai_id'        => $pegawai->id,
            'lokasi_kerja_id'   => $lokasiAktif->id,
            'tanggal'           => now()->toDateString(),
            'jam_masuk'         => now()->format('H:i:s'),
            'latitude_masuk'    => $request->latitude,
            'longitude_masuk'   => $request->longitude,
            'jarak_masuk_meter' => round($jarak),
            'foto_masuk'        => $fotoPath,
            'status'            => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Absen masuk berhasil! Status: " . ucfirst($status),
            'status'  => $status,
            'jam'     => now()->format('H:i'),
        ]);
    }

    public function pulangForm()
    {
        $pegawai        = auth()->user()->pegawai;
        $absensiHariIni = Absensi::where('pegawai_id', $pegawai->id)
            ->where('tanggal', now()->toDateString())->first();

        if (!$absensiHariIni || !$absensiHariIni->jam_masuk) {
            return redirect()->route('pegawai.dashboard')
                ->with('error', 'Anda belum melakukan absen masuk hari ini.');
        }

        if ($absensiHariIni->jam_pulang) {
            return redirect()->route('pegawai.dashboard')
                ->with('info', 'Anda sudah melakukan absen pulang hari ini.');
        }

        $lokasiAktif = $this->getLokasiPegawai();

        return view('pegawai.absensi.pulang', compact('absensiHariIni', 'lokasiAktif'));
    }

    public function pulangStore(Request $request)
    {
        $request->validate([
            'latitude'  => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'foto'      => ['required', 'string'],
        ]);

        $pegawai     = auth()->user()->pegawai;
        $lokasiAktif = $this->getLokasiPegawai();

        if (!$lokasiAktif) {
            return response()->json([
                'error' => 'Anda belum ditetapkan lokasi kerja. Silakan hubungi Admin.'
            ], 422);
        }

        // Server-side Haversine validation against assigned location
        $jarak = $this->hitungJarak(
            $request->latitude, $request->longitude,
            $lokasiAktif->latitude, $lokasiAktif->longitude
        );

        if ($jarak > $lokasiAktif->radius_meter) {
            return response()->json([
                'error' => "Anda berada di luar area {$lokasiAktif->nama_lokasi}. " .
                           "Jarak Anda: " . round($jarak) . " meter (maks: {$lokasiAktif->radius_meter} meter)."
            ], 422);
        }

        $absensi = Absensi::where('pegawai_id', $pegawai->id)
            ->where('tanggal', now()->toDateString())
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_pulang')
            ->firstOrFail();

        $fotoPath = $this->simpanFotoBase64($request->foto, 'pulang');

        $absensi->update([
            'jam_pulang'         => now()->format('H:i:s'),
            'latitude_pulang'    => $request->latitude,
            'longitude_pulang'   => $request->longitude,
            'jarak_pulang_meter' => round($jarak),
            'foto_pulang'        => $fotoPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absen pulang berhasil!',
            'jam'     => now()->format('H:i'),
        ]);
    }

    public function riwayat(Request $request)
    {
        $pegawai = auth()->user()->pegawai;

        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $absensi = Absensi::with('lokasi_kerja')
            ->where('pegawai_id', $pegawai->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'desc')
            ->paginate(20)->withQueryString();

        $izin = \App\Models\Izin::where('pegawai_id', $pegawai->id)
            ->whereYear('tanggal_mulai', $tahun)
            ->whereMonth('tanggal_mulai', $bulan)
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('pegawai.riwayat.index', compact('absensi', 'izin', 'bulan', 'tahun'));
    }

    /**
     * Save base64 encoded photo to storage.
     */
    private function simpanFotoBase64(string $base64, string $tipe): string
    {
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $decoded   = base64_decode($imageData);
        $filename  = 'absensi-foto/' . date('Y/m/d') . '/' . $tipe . '_' . auth()->id() . '_' . time() . '.jpg';

        Storage::disk('public')->put($filename, $decoded);

        return $filename;
    }
}
