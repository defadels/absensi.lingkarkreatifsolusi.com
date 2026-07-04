<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    
    protected $fillable = [
        'pegawai_id','lokasi_kerja_id','tanggal','jam_masuk','jam_pulang','latitude_masuk','longitude_masuk','latitude_pulang','longitude_pulang',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class,'pegawai_id');
    }

    public function lokasi_kerja()
    {
        return $this->belongsTo(LokasiKerja::class,'lokasi_kerja_id');
    }
}
