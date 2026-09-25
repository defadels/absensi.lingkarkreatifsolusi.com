<?php

namespace Database\Seeders;

use App\Models\LokasiKerja;
use App\Models\Pegawai;
use Illuminate\Database\Seeder;
use RuntimeException;

class AbsensiSeeder extends Seeder
{
    /**
     * Seed a completed attendance record for the demo employee.
     */
    public function run(): void
    {
        $pegawai = Pegawai::whereHas('user', function ($query) {
            $query->where('email', 'budi@lingkarkreatif.com')
                ->where('role', 'pegawai');
        })->first();

        if (!$pegawai) {
            throw new RuntimeException('Demo employee is missing. Run AdminSeeder before AbsensiSeeder.');
        }

        $lokasi = $pegawai->lokasiKerja()->where('is_active', true)->first()
            ?? LokasiKerja::where('is_active', true)->orderBy('id')->first();

        if (!$lokasi) {
            throw new RuntimeException('An active work location is required before seeding attendance.');
        }

        if ($pegawai->lokasi_kerja_id !== $lokasi->id) {
            $pegawai->update(['lokasi_kerja_id' => $lokasi->id]);
        }

        $tanggal = today()->toDateString();

        $pegawai->absensi()->updateOrCreate(
            ['tanggal' => $tanggal],
            [
                'lokasi_kerja_id'   => $lokasi->id,
                'jam_masuk'         => '08:00:00',
                'jam_pulang'        => '17:00:00',
                'latitude_masuk'    => $lokasi->latitude,
                'longitude_masuk'   => $lokasi->longitude,
                'latitude_pulang'   => $lokasi->latitude,
                'longitude_pulang'  => $lokasi->longitude,
                'jarak_masuk_meter' => 0,
                'jarak_pulang_meter' => 0,
                'foto_masuk'        => null,
                'foto_pulang'       => null,
                'status'            => 'hadir',
            ]
        );
    }
}
