<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            
            // Kapan transaksi ini dilakukan
            $table->date('tanggal_pembayaran');

            $table->integer('jumlah');

            // Total uang yang ditransfer (akumulasi rincian + uang saku)
            $table->integer('total_bayar');

            // Status transaksi secara keseluruhan
            $table->enum('status', ['lunas', 'belum lunas', 'menunggu','ditolak'])->default('menunggu');

            $table->text('keterangan_bendahara')->nullable();

            // File bukti transfer
            $table->string('bukti')->nullable();

            // Relasi ke Santri
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
        Schema::dropIfExists('pembayaran');
    }
};