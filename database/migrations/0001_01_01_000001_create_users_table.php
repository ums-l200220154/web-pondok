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
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password'); // password hash
            $table->enum('role', ['admin', 'user', 'bendahara', 'keamanan']);
            $table->string('nis_FK')->nullable();

            $table->timestamps();

            // Foreign key ke tabel santri
            $table->foreign('nis_FK')
                  ->references('nis')
                  ->on('santri')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
