<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $fillable = [
        'user_id', 'lokasi_kerja_id', 'nip', 'jabatan', 'divisi', 'no_hp', 'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lokasiKerja()
    {
        return $this->belongsTo(LokasiKerja::class, 'lokasi_kerja_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'pegawai_id');
    }

    public function izin()
    {
        return $this->hasMany(Izin::class, 'pegawai_id');
    }
}
