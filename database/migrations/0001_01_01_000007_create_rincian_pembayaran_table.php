<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rincian_pembayaran', function (Blueprint $table) {
            $table->id('id_rincian');

            // Relasi ke tabel induk pembayaran
            $table->unsignedBigInteger('id_pembayaran');
            
            // Jenis: SPP, Makan, atau 'Uang Saku'
            $table->string('jenis');
            $table->integer('jumlah');

            // Periode Tagihan (Bulan & Tahun dipindah ke sini)
            // Dibuat nullable karena 'Uang Saku' tidak terikat bulan/tahun tagihan
            $table->enum('bulan', [
                'januari','februari','maret','april','mei','juni',
                'juli','agustus','september','oktober','november','desember'
            ])->nullable();
            
            $table->year('tahun')->nullable();

            // Membedakan tagihan rutin vs titipan uang saku
            $table->enum('kategori', ['pembayaran', 'uang_saku'])->default('pembayaran');

            // Foreign Key
            $table->foreign('id_pembayaran')
                  ->references('id_pembayaran')
                  ->on('pembayaran')
                  ->onDelete('cascade');

            $table->timestamps();

            // Menambahkan Index agar pengecekan status lunas per bulan-tahun lebih cepat
            $table->index(['bulan', 'tahun', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rincian_pembayaran');
    }
};