<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('izin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('jenis_izin', ['sakit','cuti','dinas']);
            $table->text('keterangan');
            $table->string('bukti_file', 255)->nullable();
            $table->enum('status_persetujuan', ['pending','diterima','ditolak'])->default('pending');
            $table->unsignedBigInteger('diverifikasi_oleh')->nullable();
            $table->text('catatan_hrd')->nullable();
            $table->foreign('diverifikasi_oleh')->references('id')->on('users');
             $table->foreign('pegawai_id')->references('id')->on('pegawai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin');
    }
};
