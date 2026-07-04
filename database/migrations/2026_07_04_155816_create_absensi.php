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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('lokasi_kerja_id');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->decimal('latitude_masuk')->nullable();
            $table->decimal('longitude_masuk')->nullable();
            $table->decimal('latitude_pulang')->nullable();
            $table->decimal('longitude_pulang')->nullable();
             $table->foreign('pegawai_id')->references('id')->on('pegawai');
             $table->foreign('lokasi_kerja_id')->references('id')->on('lokasi_kerja');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
