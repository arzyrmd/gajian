<?php

namespace App\Http\Controllers;

use App\Models\KategoriTarif;
use App\Models\PekerjaanHarian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MonthlyCalculatorController extends Controller
{
    /**
     * Display monthly rekap list and selector.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Defaults to current month and year
        $month = (int) $request->input('month', Carbon::now()->month);
        $year = (int) $request->input('year', Carbon::now()->year);

        // Fetch rekap data using aggregate query
        $monthlyCategoryRekap = PekerjaanHarian::join('kategori_tarif', 'pekerjaan_harian.kategori_tarif_id', '=', 'kategori_tarif.id')
            ->where('pekerjaan_harian.user_id', $userId)
            ->whereYear('pekerjaan_harian.tanggal', $year)
            ->whereMonth('pekerjaan_harian.tanggal', $month)
            ->groupBy('kategori_tarif.id', 'kategori_tarif.nama_kategori', 'kategori_tarif.tarif_berhasil', 'kategori_tarif.tarif_gagal')
            ->selectRaw('
                kategori_tarif.id as category_id,
                kategori_tarif.nama_kategori,
                kategori_tarif.tarif_berhasil,
                kategori_tarif.tarif_gagal,
                SUM(jumlah_berhasil) as total_berhasil,
                SUM(jumlah_gagal) as total_gagal,
                SUM(jumlah_berhasil + jumlah_gagal) as total_pekerjaan,
                SUM((jumlah_berhasil * tarif_berhasil) + (jumlah_gagal * tarif_gagal)) as total_gaji
            ')
            ->get();

        // Merge with all categories to ensure categories with zero jobs are shown
        $allCategories = KategoriTarif::all();
        $rekapMap = $monthlyCategoryRekap->keyBy('category_id');

        $rekap = $allCategories->map(function ($cat) use ($rekapMap) {
            $item = $rekapMap->get($cat->id);
            return (object) [
                'id' => $cat->id,
                'nama_kategori' => $cat->nama_kategori,
                'tarif_berhasil' => $cat->tarif_berhasil,
                'tarif_gagal' => $cat->tarif_gagal,
                'total_berhasil' => $item ? (int) $item->total_berhasil : 0,
                'total_gagal' => $item ? (int) $item->total_gagal : 0,
                'total_pekerjaan' => $item ? (int) $item->total_pekerjaan : 0,
                'total_gaji' => $item ? (int) $item->total_gaji : 0,
            ];
        });

        // Overall Monthly Summary
        $summary = PekerjaanHarian::join('kategori_tarif', 'pekerjaan_harian.kategori_tarif_id', '=', 'kategori_tarif.id')
            ->where('pekerjaan_harian.user_id', $userId)
            ->whereYear('pekerjaan_harian.tanggal', $year)
            ->whereMonth('pekerjaan_harian.tanggal', $month)
            ->selectRaw('
                SUM(jumlah_berhasil) as total_berhasil,
                SUM(jumlah_gagal) as total_gagal,
                SUM(jumlah_berhasil + jumlah_gagal) as total_pekerjaan,
                SUM((jumlah_berhasil * tarif_berhasil) + (jumlah_gagal * tarif_gagal)) as total_gaji
            ')
            ->first();

        // Daily list inside this month
        $dailyBreakdown = PekerjaanHarian::join('kategori_tarif', 'pekerjaan_harian.kategori_tarif_id', '=', 'kategori_tarif.id')
            ->where('pekerjaan_harian.user_id', $userId)
            ->whereYear('pekerjaan_harian.tanggal', $year)
            ->whereMonth('pekerjaan_harian.tanggal', $month)
            ->groupBy('pekerjaan_harian.tanggal')
            ->orderBy('pekerjaan_harian.tanggal', 'asc')
            ->selectRaw('
                pekerjaan_harian.tanggal,
                SUM(jumlah_berhasil) as total_berhasil,
                SUM(jumlah_gagal) as total_gagal,
                SUM(jumlah_berhasil + jumlah_gagal) as total_pekerjaan,
                SUM((jumlah_berhasil * tarif_berhasil) + (jumlah_gagal * tarif_gagal)) as total_gaji
            ')
            ->get();

        // Generate years list for dropdown (e.g. 5 years back and 5 years forward)
        $currentYear = Carbon::now()->year;
        $years = range($currentYear - 3, $currentYear + 2);

        // Generate months list
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = Carbon::create(null, $m, 1)->translatedFormat('F');
        }

        return view('bulanan.index', [
            'rekap' => $rekap,
            'totalBerhasil' => $summary->total_berhasil ?? 0,
            'totalGagal' => $summary->total_gagal ?? 0,
            'totalPekerjaan' => $summary->total_pekerjaan ?? 0,
            'totalGaji' => $summary->total_gaji ?? 0,
            'dailyBreakdown' => $dailyBreakdown,
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'years' => $years,
            'months' => $months,
        ]);
    }

    /**
     * Export monthly summary data to CSV.
     */
    public function exportCsv(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $month = (int) $request->input('month', Carbon::now()->month);
        $year = (int) $request->input('year', Carbon::now()->year);

        $monthName = Carbon::create($year, $month, 1)->translatedFormat('F');
        $fileName = "Rekap_Gajian_{$user->name}_{$monthName}_{$year}.csv";

        // Query category summary
        $monthlyCategoryRekap = PekerjaanHarian::join('kategori_tarif', 'pekerjaan_harian.kategori_tarif_id', '=', 'kategori_tarif.id')
            ->where('pekerjaan_harian.user_id', $userId)
            ->whereYear('pekerjaan_harian.tanggal', $year)
            ->whereMonth('pekerjaan_harian.tanggal', $month)
            ->groupBy('kategori_tarif.id', 'kategori_tarif.nama_kategori', 'kategori_tarif.tarif_berhasil', 'kategori_tarif.tarif_gagal')
            ->selectRaw('
                kategori_tarif.nama_kategori,
                kategori_tarif.tarif_berhasil,
                kategori_tarif.tarif_gagal,
                SUM(jumlah_berhasil) as total_berhasil,
                SUM(jumlah_gagal) as total_gagal,
                SUM(jumlah_berhasil + jumlah_gagal) as total_pekerjaan,
                SUM((jumlah_berhasil * tarif_berhasil) + (jumlah_gagal * tarif_gagal)) as total_gaji
            ')
            ->get();

        // Query daily breakdowns
        $dailyBreakdown = PekerjaanHarian::join('kategori_tarif', 'pekerjaan_harian.kategori_tarif_id', '=', 'kategori_tarif.id')
            ->where('pekerjaan_harian.user_id', $userId)
            ->whereYear('pekerjaan_harian.tanggal', $year)
            ->whereMonth('pekerjaan_harian.tanggal', $month)
            ->groupBy('pekerjaan_harian.tanggal')
            ->orderBy('pekerjaan_harian.tanggal', 'asc')
            ->selectRaw('
                pekerjaan_harian.tanggal,
                SUM(jumlah_berhasil) as total_berhasil,
                SUM(jumlah_gagal) as total_gagal,
                SUM(jumlah_berhasil + jumlah_gagal) as total_pekerjaan,
                SUM((jumlah_berhasil * tarif_berhasil) + (jumlah_gagal * tarif_gagal)) as total_gaji
            ')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($user, $monthName, $year, $monthlyCategoryRekap, $dailyBreakdown) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM to prevent Excel encoding issues
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Title block
            fputcsv($file, ['REKAP ESTIMASI GAJIAN TEKNISI LAPANGAN']);
            fputcsv($file, ['Nama Teknisi', $user->name]);
            fputcsv($file, ['Email', $user->email]);
            fputcsv($file, ['Periode', "{$monthName} {$year}"]);
            fputcsv($file, []);

            // Summary Table header
            fputcsv($file, ['RINGKASAN PER KATEGORI']);
            fputcsv($file, [
                'Nama Kategori',
                'Tarif Berhasil',
                'Tarif Gagal',
                'Jumlah Berhasil',
                'Jumlah Gagal',
                'Total Pekerjaan',
                'Subtotal (Rp)',
            ]);

            $grandTotalJobs = 0;
            $grandTotalSalary = 0;

            foreach ($monthlyCategoryRekap as $item) {
                fputcsv($file, [
                    $item->nama_kategori,
                    $item->tarif_berhasil,
                    $item->tarif_gagal,
                    $item->total_berhasil,
                    $item->total_gagal,
                    $item->total_pekerjaan,
                    $item->total_gaji,
                ]);
                $grandTotalJobs += $item->total_pekerjaan;
                $grandTotalSalary += $item->total_gaji;
            }

            fputcsv($file, ['TOTAL', '', '', '', '', $grandTotalJobs, $grandTotalSalary]);
            fputcsv($file, []);
            fputcsv($file, []);

            // Daily breakdowns table
            fputcsv($file, ['RINCIAN HARIAN']);
            fputcsv($file, [
                'Tanggal',
                'Jumlah Berhasil',
                'Jumlah Gagal',
                'Total Pekerjaan',
                'Total Gaji Harian (Rp)',
            ]);

            foreach ($dailyBreakdown as $day) {
                fputcsv($file, [
                    $day->tanggal->format('Y-m-d'),
                    $day->total_berhasil,
                    $day->total_gagal,
                    $day->total_pekerjaan,
                    $day->total_gaji,
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
