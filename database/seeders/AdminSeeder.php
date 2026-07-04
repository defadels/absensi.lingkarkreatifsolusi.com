<?php

namespace Database\Seeders;

use App\Models\LokasiKerja;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user
        $admin = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@lingkarkreatif.com',
            'no_hp'    => '081234567890',
            'role'     => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        Pegawai::create([
            'user_id' => $admin->id,
            'nip'     => 'ADM-001',
            'jabatan' => 'Administrator',
            'divisi'  => 'IT',
            'no_hp'   => '081234567890',
        ]);

        // Create HRD user
        $hrd = User::create([
            'name'     => 'HRD Manager',
            'email'    => 'hrd@lingkarkreatif.com',
            'no_hp'    => '081234567891',
            'role'     => 'hrd',
            'password' => Hash::make('hrd12345'),
        ]);

        Pegawai::create([
            'user_id' => $hrd->id,
            'nip'     => 'HRD-001',
            'jabatan' => 'HRD Manager',
            'divisi'  => 'Human Resources',
            'no_hp'   => '081234567891',
        ]);

        // Create sample Pegawai user
        $pegawai = User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@lingkarkreatif.com',
            'no_hp'    => '081234567892',
            'role'     => 'pegawai',
            'password' => Hash::make('pegawai123'),
        ]);

        Pegawai::create([
            'user_id' => $pegawai->id,
            'nip'     => 'EMP-001',
            'jabatan' => 'Staff',
            'divisi'  => 'IT',
            'no_hp'   => '081234567892',
        ]);

        // Create default lokasi kerja (coordinates: PT Lingkar Kreatif Solusi - example)
        LokasiKerja::create([
            'nama_lokasi'      => 'Kantor Pusat PT Lingkar Kreatif Solusi',
            'latitude'         => -6.2088000,
            'longitude'        => 106.8456000,
            'radius_meter'     => 100,
            'jam_masuk_standar' => '08:00:00',
            'toleransi_menit'  => 15,
            'is_active'        => true,
        ]);
    }
}
