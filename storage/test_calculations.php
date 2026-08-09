<?php

// Bootstrapping Laravel environment
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\KategoriTarif;
use App\Models\PekerjaanHarian;
use Illuminate\Support\Facades\DB;

try {
    DB::beginTransaction();

    echo "Running test suite for Salary Calculator...\n";

    // 1. Fetch the default seeded technician
    $user = User::where('email', 'teknisi@gajian.com')->first();
    if (!$user) {
        throw new Exception("Default technician 'teknisi@gajian.com' not found.");
    }
    echo "- Default technician found: {$user->name} (ID: {$user->id})\n";

    // 2. Fetch some seeded categories
    $catKirimFaktur = KategoriTarif::where('nama_kategori', 'Kirim Faktur')->first();
    $catKunjungan = KategoriTarif::where('nama_kategori', 'Kunjungan')->first();
    $catProaktifMall = KategoriTarif::where('nama_kategori', 'Proaktif Maintenance (dalam Mall)')->first();

    if (!$catKirimFaktur || !$catKunjungan || !$catProaktifMall) {
        throw new Exception("Seeded categories are missing in the database.");
    }
    echo "- Seeded categories fetched correctly.\n";

    // Clean up any existing logs for the test date
    $testDate = '2026-08-06';
    PekerjaanHarian::where('user_id', $user->id)->where('tanggal', $testDate)->delete();

    // 3. Insert test data for selected date
    // Kirim Faktur: 5 Berhasil, 2 Gagal (Tarif: 15.000 / 10.000)
    // - Gaji: (5 * 15.000) + (2 * 10.000) = 75.000 + 20.000 = 95.000
    PekerjaanHarian::create([
        'user_id' => $user->id,
        'tanggal' => $testDate,
        'kategori_tarif_id' => $catKirimFaktur->id,
        'jumlah_berhasil' => 5,
        'jumlah_gagal' => 2,
    ]);

    // Kunjungan: 3 Berhasil, 1 Gagal (Tarif: 15.000 / 10.000)
    // - Gaji: (3 * 15.000) + (1 * 10.000) = 45.000 + 10.000 = 55.000
    PekerjaanHarian::create([
        'user_id' => $user->id,
        'tanggal' => $testDate,
        'kategori_tarif_id' => $catKunjungan->id,
        'jumlah_berhasil' => 3,
        'jumlah_gagal' => 1,
    ]);

    // Proaktif Mall: 2 Berhasil, 4 Gagal (Tarif: 10.000 / 0)
    // - Gaji: (2 * 10.000) + (4 * 0) = 20.000
    PekerjaanHarian::create([
        'user_id' => $user->id,
        'tanggal' => $testDate,
        'kategori_tarif_id' => $catProaktifMall->id,
        'jumlah_berhasil' => 2,
        'jumlah_gagal' => 4,
    ]);

    echo "- Test data inserted successfully.\n";

    // 4. Run aggregate SQL calculations
    $summary = PekerjaanHarian::join('kategori_tarif', 'pekerjaan_harian.kategori_tarif_id', '=', 'kategori_tarif.id')
        ->where('pekerjaan_harian.user_id', $user->id)
        ->where('pekerjaan_harian.tanggal', $testDate)
        ->selectRaw('
            SUM(jumlah_berhasil) as total_berhasil,
            SUM(jumlah_gagal) as total_gagal,
            SUM(jumlah_berhasil + jumlah_gagal) as total_pekerjaan,
            SUM((jumlah_berhasil * tarif_berhasil) + (jumlah_gagal * tarif_gagal)) as total_gaji
        ')
        ->first();

    // Theoretical values:
    // Total Berhasil = 5 + 3 + 2 = 10
    // Total Gagal = 2 + 1 + 4 = 7
    // Total Pekerjaan = 10 + 7 = 17
    // Total Gaji = 95.000 + 55.000 + 20.000 = 170.000

    echo "\nResults:\n";
    echo "- Total Berhasil: {$summary->total_berhasil} (Expected: 10)\n";
    echo "- Total Gagal: {$summary->total_gagal} (Expected: 7)\n";
    echo "- Total Pekerjaan: {$summary->total_pekerjaan} (Expected: 17)\n";
    echo "- Total Gaji: Rp" . number_format($summary->total_gaji) . " (Expected: Rp170,000)\n";

    // Asserts
    if ((int)$summary->total_berhasil !== 10) throw new Exception("Assert failed: Total Berhasil != 10");
    if ((int)$summary->total_gagal !== 7) throw new Exception("Assert failed: Total Gagal != 7");
    if ((int)$summary->total_pekerjaan !== 17) throw new Exception("Assert failed: Total Pekerjaan != 17");
    if ((int)$summary->total_gaji !== 170000) throw new Exception("Assert failed: Total Gaji != 170000");

    echo "\nAll calculations verified and correct! SUCCESS\n";

    DB::rollBack(); // Don't persist test calculations in DB
} catch (Exception $e) {
    DB::rollBack();
    echo "\nTEST SUITE FAILED: " . $e->getMessage() . "\n";
    exit(1);
}
