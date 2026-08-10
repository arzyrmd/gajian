@extends('layouts.app')

@section('title', 'Rekapitulasi Bulanan')

@section('content')
<div class="max-w-7xl mx-auto print-container">
    
    <!-- Page Header (hidden during printing to use custom print header) -->
    <div class="no-print flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Rekap Bulanan</h1>
            <p class="text-slate-500 mt-1">Akumulasi estimasi gajian dan volume pekerjaan Anda dalam satu bulan</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Selector Form -->
            <form action="{{ route('bulanan') }}" method="GET" class="bg-white p-3 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 pl-2">Periode:</span>
                
                <!-- Month Dropdown -->
                <select name="month" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 rounded-xl px-2 py-1.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer">
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $num === $selectedMonth ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>

                <!-- Year Dropdown -->
                <select name="year" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 rounded-xl px-2 py-1.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer">
                    @foreach($years as $yr)
                        <option value="{{ $yr }}" {{ $yr === $selectedYear ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Print-only Title Header -->
    <div class="hidden print:block mb-8 border-b-2 border-slate-900 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900">REKAP ESTIMASI GAJIAN TEKNISI LAPANGAN</h1>
        <div class="mt-4 grid grid-cols-2 text-sm gap-2">
            <div><strong>Nama Teknisi:</strong> {{ Auth::user()->name }}</div>
            <div><strong>Email:</strong> {{ Auth::user()->email }}</div>
            <div><strong>Periode:</strong> {{ $months[$selectedMonth] }} {{ $selectedYear }}</div>
            <div><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</div>
        </div>
    </div>

    <!-- Overall Summary Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
        
        <!-- Widget: Grand Total Salary -->
        <div class="bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-3xl p-6 text-white shadow-xl shadow-blue-500/10">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-200">Total Estimasi Gaji</span>
            <div class="text-3xl font-extrabold mt-1 tracking-tight">
                Rp{{ number_format($totalGaji, 0, ',', '.') }}
            </div>
            <div class="text-[10px] text-blue-100/80 mt-3 font-semibold uppercase tracking-wider">
                Periode {{ $months[$selectedMonth] }} {{ $selectedYear }}
            </div>
        </div>

        <!-- Widget: Total Jobs -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pekerjaan</span>
                <div class="text-3xl font-extrabold text-slate-800 mt-1 tracking-tight">
                    {{ $totalPekerjaan }}
                </div>
            </div>
            <div class="text-xs text-slate-500 mt-3 font-medium">
                Akumulasi seluruh kategori
            </div>
        </div>

        <!-- Widget: Successful Jobs -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Sukses</span>
                <div class="text-3xl font-extrabold text-emerald-600 mt-1 tracking-tight">
                    {{ $totalBerhasil }}
                </div>
            </div>
            <div class="text-xs text-emerald-600/80 mt-3 font-medium flex items-center">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Tugas selesai</span>
            </div>
        </div>

        <!-- Widget: Failed Jobs -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Gagal</span>
                <div class="text-3xl font-extrabold text-rose-600 mt-1 tracking-tight">
                    {{ $totalGagal }}
                </div>
            </div>
            <div class="text-xs text-rose-600/80 mt-3 font-medium flex items-center">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>Tugas gagal / dibatalkan</span>
            </div>
        </div>

    </div>

    <!-- Export & Print Action Buttons (hidden during printing) -->
    <div class="no-print mb-6 flex flex-wrap gap-3">
        <a href="{{ route('bulanan.export', ['month' => $selectedMonth, 'year' => $selectedYear]) }}" 
           class="flex items-center space-x-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-4 py-3 rounded-2xl shadow-sm hover:shadow active:scale-95 transition-all cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776"></path></svg>
            <span>Ekspor ke Excel (CSV)</span>
        </a>
        
        <button onclick="window.print()" 
                class="flex items-center space-x-2 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs px-4 py-3 rounded-2xl shadow-sm hover:shadow active:scale-95 transition-all cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0a2.25 2.25 0 01-2.227 1.932H8.567a2.25 2.25 0 01-2.227-1.932m11.32 0a2.25 2.25 0 002.227-1.932V9.674c0-1.18-.834-2.174-1.997-2.314a41.3 41.3 0 00-12.016 0A2.25 2.25 0 003 9.674v6.394a2.25 2.25 0 002.227 1.932M16.5 9.75V4.5a1.5 1.5 0 00-1.5-1.5H9A1.5 1.5 0 007.5 4.5v5.25"></path></svg>
            <span>Cetak PDF / Print</span>
        </button>
    </div>

    <div class="space-y-8">
        
        <!-- Table Card: Categories Breakdown -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center no-print">
                <h3 class="font-bold text-slate-800 text-base">Rekap Detail Per Kategori</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Kategori</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Tarif Sukses</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Tarif Gagal</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Jumlah Sukses</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Jumlah Gagal</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-slate-400 uppercase tracking-wider font-bold">Total Tugas</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold text-slate-400 uppercase tracking-wider font-bold">Subtotal Gaji</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150">
                        @forelse($rekap as $item)
                        <tr class="hover:bg-slate-50/50 transition-all duration-150">
                            <td class="px-6 py-4.5 whitespace-nowrap text-sm font-bold text-slate-800">{{ $item->nama_kategori }}</td>
                            <td class="px-6 py-4.5 whitespace-nowrap text-sm text-right text-slate-600">Rp{{ number_format($item->tarif_berhasil, 0, ',', '.') }}</td>
                            <td class="px-6 py-4.5 whitespace-nowrap text-sm text-right text-slate-600">
                                @if($item->tarif_gagal > 0)
                                    Rp{{ number_format($item->tarif_gagal, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-400">Rp0</span>
                                @endif
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap text-sm text-center font-semibold text-slate-700">{{ $item->total_berhasil }}</td>
                            <td class="px-6 py-4.5 whitespace-nowrap text-sm text-center font-semibold text-slate-700">{{ $item->total_gagal }}</td>
                            <td class="px-6 py-4.5 whitespace-nowrap text-sm text-center font-bold text-slate-800 bg-slate-50/20">{{ $item->total_pekerjaan }}</td>
                            <td class="px-6 py-4.5 whitespace-nowrap text-sm text-right font-extrabold text-slate-900 bg-slate-50/30">
                                Rp{{ number_format($item->total_gaji, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-sm">
                                Belum ada data pekerjaan di bulan ini.
                            </td>
                        </tr>
                        @endforelse
                        
                        <!-- Grand Total Footer Row -->
                        <tr class="bg-slate-100 font-extrabold">
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-slate-850 uppercase tracking-wider">TOTAL KESELURUHAN</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-slate-850">{{ $rekap->sum('total_berhasil') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-slate-850">{{ $rekap->sum('total_gagal') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-slate-900 bg-slate-200/50">{{ $totalPekerjaan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-blue-700 bg-slate-200/80">
                                Rp{{ number_format($totalGaji, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table Card: Daily List Drill-down -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center no-print">
                <h3 class="font-bold text-slate-800 text-base">Rincian Per Tanggal</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Tanggal Kerja</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Jumlah Sukses</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Jumlah Gagal</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Total Volume Pekerjaan</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Gaji Harian</th>
                            <th scope="col" class="no-print px-6 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150">
                        @forelse($dailyBreakdown as $day)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <td class="px-6 py-4.5 whitespace-nowrap text-sm font-bold text-slate-800">
                                {{ $day->tanggal->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap text-sm text-center font-semibold text-emerald-600">
                                {{ $day->total_berhasil }}
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap text-sm text-center font-semibold text-rose-600">
                                {{ $day->total_gagal }}
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap text-sm text-center font-bold text-slate-800">
                                {{ $day->total_pekerjaan }}
                            </td>
                            <td class="px-6 py-4.5 whitespace-nowrap text-sm text-right font-extrabold text-slate-900">
                                Rp{{ number_format($day->total_gaji, 0, ',', '.') }}
                            </td>
                            <td class="no-print px-6 py-4.5 whitespace-nowrap text-center text-sm font-semibold">
                                <a href="{{ route('harian', ['tanggal' => $day->tanggal->toDateString()]) }}" 
                                   class="text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path></svg>
                                    <span>Buka / Edit</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">
                                Belum ada rincian pengerjaan harian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
