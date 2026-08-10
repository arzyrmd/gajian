<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KategoriTarif;
use App\Models\PekerjaanHarian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    /**
     * Display a listing of technicians and their monthly earnings.
     */
    public function index(Request $request)
    {
        $month = (int) $request->input('month', Carbon::now()->month);
        $year = (int) $request->input('year', Carbon::now()->year);

        $technicians = User::where('is_admin', false)
            ->leftJoin('pekerjaan_harian', function ($join) use ($year, $month) {
                $join->on('users.id', '=', 'pekerjaan_harian.user_id')
                    ->whereYear('pekerjaan_harian.tanggal', $year)
                    ->whereMonth('pekerjaan_harian.tanggal', $month);
            })
            ->leftJoin('kategori_tarif', 'pekerjaan_harian.kategori_tarif_id', '=', 'kategori_tarif.id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->selectRaw('
                users.id,
                users.name,
                users.email,
                COALESCE(SUM(pekerjaan_harian.jumlah_berhasil), 0) as total_berhasil,
                COALESCE(SUM(pekerjaan_harian.jumlah_gagal), 0) as total_gagal,
                COALESCE(SUM(pekerjaan_harian.jumlah_berhasil + pekerjaan_harian.jumlah_gagal), 0) as total_pekerjaan,
                COALESCE(SUM((pekerjaan_harian.jumlah_berhasil * kategori_tarif.tarif_berhasil) + (pekerjaan_harian.jumlah_gagal * kategori_tarif.tarif_gagal)), 0) as total_gaji
            ')
            ->orderBy('users.name')
            ->get();

        $currentYear = Carbon::now()->year;
        $years = range($currentYear - 3, $currentYear + 2);

        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = Carbon::create(null, $m, 1)->translatedFormat('F');
        }

        return view('monitoring.index', compact('technicians', 'month', 'year', 'years', 'months'));
    }

    /**
     * Show details of a technician's monthly work.
     */
    public function show(Request $request, User $user)
    {
        if ($user->is_admin) {
            return redirect()->route('monitoring.index')->with('error', 'Tidak dapat memantau data pengguna Admin.');
        }

        $month = (int) $request->input('month', Carbon::now()->month);
        $year = (int) $request->input('year', Carbon::now()->year);

        // Fetch rekap data using aggregate query for this technician
        $monthlyCategoryRekap = PekerjaanHarian::join('kategori_tarif', 'pekerjaan_harian.kategori_tarif_id', '=', 'kategori_tarif.id')
            ->where('pekerjaan_harian.user_id', $user->id)
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
            ->where('pekerjaan_harian.user_id', $user->id)
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
            ->where('pekerjaan_harian.user_id', $user->id)
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

        $currentYear = Carbon::now()->year;
        $years = range($currentYear - 3, $currentYear + 2);

        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = Carbon::create(null, $m, 1)->translatedFormat('F');
        }

        return view('monitoring.show', compact('user', 'rekap', 'summary', 'dailyBreakdown', 'month', 'year', 'years', 'months'));
    }
}
