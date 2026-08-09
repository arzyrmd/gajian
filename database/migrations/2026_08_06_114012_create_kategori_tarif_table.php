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
        Schema::create('kategori_tarif', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori')->unique();
            $table->integer('tarif_berhasil')->default(0);
            $table->integer('tarif_gagal')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_tarif');
    }
};
