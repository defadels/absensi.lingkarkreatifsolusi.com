<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $table = 'izin';
    protected $fillable = [
        'pegawai_id','tanggal_mulai','tanggal_selesai','jenis_izin','keterangan','bukti_file','status_persetujuan','diverifikasi_oleh','catatan_hrd',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class,'pegawai_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'diverifikasi_oleh');
    }
}
