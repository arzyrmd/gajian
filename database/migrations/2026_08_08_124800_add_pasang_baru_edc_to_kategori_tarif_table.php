<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('kategori_tarif')->updateOrInsert(
            ['nama_kategori' => 'Pasang Baru EDC'],
            [
                'tarif_berhasil' => 15000,
                'tarif_gagal' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('kategori_tarif')->where('nama_kategori', 'Pasang Baru EDC')->delete();
    }
};
