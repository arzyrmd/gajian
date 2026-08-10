<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PekerjaanHarian;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    /**
     * Show overview of all technician salaries.
     */
    public function index(Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));

        // Get technicians only (is_admin = false)
        $technicians = User::where('is_admin', false)
            ->with(['pekerjaanHarian' => function ($query) use ($month, $year) {
                $query->whereMonth('tanggal', $month)
                    ->whereYear('tanggal', $year)
                    ->with('kategoriTarif');
            }])
            ->get()
            ->map(function ($user) {
                $totalBerhasil = 0;
                $totalGagal = 0;
                $totalGaji = 0;

                foreach ($user->pekerjaanHarian as $job) {
                    $totalBerhasil += $job->jumlah_berhasil;
                    $totalGagal += $job->jumlah_gagal;
                    $totalGaji += $job->estimasi_gaji;
                }

                $user->total_berhasil = $totalBerhasil;
                $user->total_gagal = $totalGagal;
                $user->total_gaji = $totalGaji;
                return $user;
            });

        // Summary Stats
        $stats = [
            'total_technicians' => $technicians->count(),
            'total_gaji' => $technicians->sum('total_gaji'),
            'total_berhasil' => $technicians->sum('total_berhasil'),
            'total_gagal' => $technicians->sum('total_gagal'),
        ];

        return view('monitoring.index', compact('technicians', 'stats', 'month', 'year'));
    }

    /**
     * Show detailed daily logs of a single technician.
     */
    public function show(Request $request, User $user)
    {
        if ($user->is_admin) {
            abort(404);
        }

        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));

        $logs = PekerjaanHarian::where('user_id', $user->id)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->with('kategoriTarif')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('monitoring.show', compact('user', 'logs', 'month', 'year'));
    }
}
