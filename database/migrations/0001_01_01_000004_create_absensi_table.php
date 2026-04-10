<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->date('tanggal');

            // Waktu absensi
            $table->enum('waktu', ['pagi', 'malam']);

            // Keterangan kehadiran
            $table->enum('keterangan', ['hadir', 'sakit', 'tidak hadir']);

            // Foreign key ke tabel santri
            $table->string('nis');
            $table->foreign('nis')
                  ->references('nis')
                  ->on('santri')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
