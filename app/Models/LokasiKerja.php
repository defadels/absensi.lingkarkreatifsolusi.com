<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiKerja extends Model
{
    protected $table = 'lokasi_kerja';
    protected $fillable = [
        'nama_lokasi',
        'latitude',
        'longitude',
        'radius_meter',
        'jam_masuk_standar',
        'toleransi_menit',
        'is_active',
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'lokasi_kerja_id');
    }
}
