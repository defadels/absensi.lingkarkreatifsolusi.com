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
        Schema::table('pegawai', function (Blueprint $table) {
            $table->unsignedBigInteger('lokasi_kerja_id')->nullable()->after('user_id');
            $table->foreign('lokasi_kerja_id')
                  ->references('id')
                  ->on('lokasi_kerja')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropForeign(['lokasi_kerja_id']);
            $table->dropColumn('lokasi_kerja_id');
        });
    }
};
