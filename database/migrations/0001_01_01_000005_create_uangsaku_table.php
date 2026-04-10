<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uang_saku', function (Blueprint $table) {
            $table->id('id_uangsaku');

            // Saldo akhir setelah transaksi
            $table->integer('saldo');

            // Jumlah uang masuk / keluar
            $table->integer('jumlah');
            $table->enum('jenis', ['masuk', 'keluar']); 
            $table->string('keterangan')->nullable();

            $table->date('tanggal');

            // Foreign Key ke tabel santri
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
        Schema::dropIfExists('uang_saku');
    }
};
