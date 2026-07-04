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
        Schema::create('lokasi_kerja', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude', 10.8);
            $table->decimal('longitude', 11.8);
            $table->integer('radius_meter', 100);
            $table->time('jam_masuk_standar')->default('08:00:00');
            $table->integer('toleransi_menit')->default(15);
            $table->bool('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_kerja');
    }
};
