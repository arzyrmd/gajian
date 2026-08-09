<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KategoriTarifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Tariffs
        $tariffs = [
            [
                'nama_kategori' => 'Kirim Faktur',
                'tarif_berhasil' => 15000,
                'tarif_gagal' => 10000,
            ],
            [
                'nama_kategori' => 'Kunjungan',
                'tarif_berhasil' => 15000,
                'tarif_gagal' => 10000,
            ],
            [
                'nama_kategori' => 'Pasang Baru QRIS',
                'tarif_berhasil' => 15000,
                'tarif_gagal' => 10000,
            ],
            [
                'nama_kategori' => 'Pasang Baru EDC',
                'tarif_berhasil' => 15000,
                'tarif_gagal' => 10000,
            ],
            [
                'nama_kategori' => 'Init',
                'tarif_berhasil' => 15000,
                'tarif_gagal' => 10000,
            ],
            [
                'nama_kategori' => 'Penarikan EDC',
                'tarif_berhasil' => 15000,
                'tarif_gagal' => 10000,
            ],
            [
                'nama_kategori' => 'Proaktif Maintenance (dalam Mall)',
                'tarif_berhasil' => 10000,
                'tarif_gagal' => 0,
            ],
            [
                'nama_kategori' => 'Proaktif Maintenance (luar Mall)',
                'tarif_berhasil' => 10000,
                'tarif_gagal' => 5000,
            ],
        ];

        foreach ($tariffs as $tariff) {
            DB::table('kategori_tarif')->updateOrInsert(
                ['nama_kategori' => $tariff['nama_kategori']],
                [
                    'tarif_berhasil' => $tariff['tarif_berhasil'],
                    'tarif_gagal' => $tariff['tarif_gagal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Seed Users
        DB::table('users')->updateOrInsert(
            ['email' => 'teknisi@gajian.com'],
            [
                'name' => 'Teknisi Lapangan',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'admin@gajian.com'],
            [
                'name' => 'Admin Gajian',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
