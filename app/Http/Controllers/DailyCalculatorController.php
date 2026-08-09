<?php

namespace App\Http\Controllers;

use App\Models\KategoriTarif;
use App\Models\PekerjaanHarian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyCalculatorController extends Controller
{
    /**
     * Show daily inputs and rekap for a given date.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Determine the date, default to today
        $dateInput = $request->input('tanggal', Carbon::today()->toDateString());
        $selectedDate = Carbon::parse($dateInput)->toDateString();

        // Fetch all categories
        $categories = KategoriTarif::all();

        // Fetch existing jobs for this date and user, indexed by kategori_tarif_id
        $existingJobs = PekerjaanHarian::where('user_id', $userId)
            ->where('tanggal', $selectedDate)
            ->get()
            ->keyBy('kategori_tarif_id');

        // Calculate breakdown and totals for the selected date using database aggregate (SUM)
        $summary = PekerjaanHarian::join('kategori_tarif', 'pekerjaan_harian.kategori_tarif_id', '=', 'kategori_tarif.id')
            ->where('pekerjaan_harian.user_id', $userId)
            ->where('pekerjaan_harian.tanggal', $selectedDate)
            ->selectRaw('
                SUM(jumlah_berhasil) as total_berhasil,
                SUM(jumlah_gagal) as total_gagal,
                SUM(jumlah_berhasil + jumlah_gagal) as total_pekerjaan,
                SUM((jumlah_berhasil * tarif_berhasil) + (jumlah_gagal * tarif_gagal)) as total_gaji
            ')
            ->first();

        // Build categories list with inputs
        $categoryDetails = $categories->map(function ($cat) use ($existingJobs) {
            $job = $existingJobs->get($cat->id);
            $berhasil = $job ? $job->jumlah_berhasil : 0;
            $gagal = $job ? $job->jumlah_gagal : 0;
            
            $subtotal = ($berhasil * $cat->tarif_berhasil) + ($gagal * $cat->tarif_gagal);

            return (object) [
                'id' => $cat->id,
                'nama_kategori' => $cat->nama_kategori,
                'tarif_berhasil' => $cat->tarif_berhasil,
                'tarif_gagal' => $cat->tarif_gagal,
                'berhasil' => $berhasil,
                'gagal' => $gagal,
                'subtotal' => $subtotal,
            ];
        });

        // Date history query
        $historyQuery = PekerjaanHarian::join('kategori_tarif', 'pekerjaan_harian.kategori_tarif_id', '=', 'kategori_tarif.id')
            ->where('pekerjaan_harian.user_id', $userId);

        // Apply history filters if present
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate) {
            $historyQuery->where('pekerjaan_harian.tanggal', '>=', $startDate);
        }
        if ($endDate) {
            $historyQuery->where('pekerjaan_harian.tanggal', '<=', $endDate);
        }

        // Aggregate history per date
        $history = $historyQuery->groupBy('pekerjaan_harian.tanggal')
            ->orderBy('pekerjaan_harian.tanggal', 'desc')
            ->selectRaw('
                pekerjaan_harian.tanggal,
                SUM(jumlah_berhasil + jumlah_gagal) as total_pekerjaan,
                SUM((jumlah_berhasil * tarif_berhasil) + (jumlah_gagal * tarif_gagal)) as total_gaji
            ')
            ->get();

        return view('harian.index', [
            'selectedDate' => $selectedDate,
            'categoryDetails' => $categoryDetails,
            'totalBerhasil' => $summary->total_berhasil ?? 0,
            'totalGagal' => $summary->total_gagal ?? 0,
            'totalPekerjaan' => $summary->total_pekerjaan ?? 0,
            'totalGaji' => $summary->total_gaji ?? 0,
            'history' => $history,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Store or update job logs for a date.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        $dateInput = $request->input('tanggal');

        $request->validate([
            'tanggal' => 'required|date',
            'pekerjaan' => 'required|array',
            'pekerjaan.*.berhasil' => 'required|integer|min:0',
            'pekerjaan.*.gagal' => 'required|integer|min:0',
        ], [
            'pekerjaan.*.berhasil.min' => 'Jumlah berhasil tidak boleh kurang dari 0.',
            'pekerjaan.*.gagal.min' => 'Jumlah gagal tidak boleh kurang dari 0.',
            'pekerjaan.*.berhasil.integer' => 'Jumlah berhasil harus berupa angka.',
            'pekerjaan.*.gagal.integer' => 'Jumlah gagal harus berupa angka.',
        ]);

        foreach ($request->input('pekerjaan') as $categoryId => $counts) {
            // Only update if values are supplied (should be, due to validation)
            PekerjaanHarian::updateOrCreate(
                [
                    'user_id' => $userId,
                    'tanggal' => $dateInput,
                    'kategori_tarif_id' => $categoryId,
                ],
                [
                    'jumlah_berhasil' => $counts['berhasil'],
                    'jumlah_gagal' => $counts['gagal'],
                ]
            );
        }

        return redirect()->route('harian', ['tanggal' => $dateInput])
            ->with('success', 'Data pekerjaan tanggal ' . Carbon::parse($dateInput)->translatedFormat('d F Y') . ' berhasil disimpan!');
    }
}
