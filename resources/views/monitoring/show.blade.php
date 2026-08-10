@extends('layouts.app')

@section('title', 'Detail Rekap Gaji - ' . $user->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-8 print-container">
    <!-- Back & Header Section -->
    <div class="no-print flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('monitoring.index', ['month' => $month, 'year' => $year]) }}" 
               class="p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition duration-200 shadow-sm text-slate-600 hover:text-slate-800 cursor-pointer"
               title="Kembali ke Monitoring">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Detail Rekap Gaji</h1>
                <p class="text-slate-500 mt-1">Pemantauan rincian kerja dan estimasi gaji untuk <strong>{{ $user->name }}</strong></p>
            </div>
        </div>
        
        <!-- Month Selector Filter -->
        <form action="{{ route('monitoring.show', ['user' => $user->id]) }}" method="GET" class="bg-white p-3 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 pl-2">Periode:</span>
            <select name="month" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 rounded-xl px-2 py-1.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer">
                @foreach($months as $num => $name)
                    <option value="{{ $num }}" {{ $num === $month ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            <select name="year" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 rounded-xl px-2 py-1.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer">
                @foreach($years as $yr)
                    <option value="{{ $yr }}" {{ $yr === $year ? 'selected' : '' }}>{{ $yr }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Print Header -->
    <div class="hidden print:block mb-8 border-b-2 border-slate-900 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900">DETAIL REKAP GAJI TEKNISI LAPANGAN</h1>
        <div class="mt-4 grid grid-cols-2 text-sm gap-2">
            <div><strong>Nama Teknisi:</strong> {{ $user->name }}</div>
            <div><strong>Email:</strong> {{ $user->email }}</div>
            <div><strong>Periode:</strong> {{ $months[$month] }} {{ $year }}</div>
            <div><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</div>
        </div>
    </div>

    <!-- Overall Summary Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <!-- Grand Total Salary -->
        <div class="bg-gradient-to-tr from-indigo-900 to-slate-900 rounded-3xl p-6 text-white shadow-xl shadow-indigo-950/20">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-300">Total Gaji Diperoleh</span>
            <div class="text-3xl font-extrabold mt-1.5 tracking-tight">
                Rp{{ number_format($summary->total_gaji ?? 0, 0, ',', '.') }}
            </div>
            <div class="text-[10px] text-indigo-200 mt-3 font-semibold uppercase tracking-wider">
                Periode {{ $months[$month] }} {{ $year }}
            </div>
        </div>

        <!-- Total Jobs -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pekerjaan</span>
                <div class="text-3xl font-extrabold text-slate-800 mt-1 tracking-tight">
                    {{ $summary->total_pekerjaan ?? 0 }}
                </div>
            </div>
            <div class="text-xs text-slate-500 mt-3 font-medium">
                Akumulasi seluruh kategori
            </div>
        </div>

        <!-- Successful Jobs -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Sukses</span>
                <div class="text-3xl font-extrabold text-emerald-600 mt-1 tracking-tight">
                    {{ $summary->total_berhasil ?? 0 }}
                </div>
            </div>
            <div class="text-xs text-emerald-600/80 mt-3 font-medium flex items-center">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Tugas selesai</span>
            </div>
        </div>

        <!-- Failed Jobs -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Gagal</span>
                <div class="text-3xl font-extrabold text-rose-600 mt-1 tracking-tight">
                    {{ $summary->total_gagal ?? 0 }}
                </div>
            </div>
            <div class="text-xs text-rose-600/80 mt-3 font-medium flex items-center">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>Tugas gagal / dibatalkan</span>
            </div>
        </div>
    </div>

    <!-- Actions (Print/Export) -->
    <div class="no-print flex items-center gap-3">
        <button onclick="window.print()" 
                class="flex items-center space-x-2 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs px-4 py-3 rounded-2xl shadow-sm hover:shadow active:scale-95 transition-all cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0a2.25 2.25 0 01-2.227 1.932H8.567a2.25 2.25 0 01-2.227-1.932m11.32 0a2.25 2.25 0 002.227-1.932V9.674c0-1.18-.834-2.174-1.997-2.314a41.3 41.3 0 00-12.016 0A2.25 2.25 0 003 9.674v6.394a2.25 2.25 0 002.227 1.932M16.5 9.75V4.5a1.5 1.5 0 00-1.5-1.5H9A1.5 1.5 0 007.5 4.5v5.25"></path></svg>
            <span>Cetak PDF / Print Rekap</span>
        </button>
    </div>

    <!-- Table Card: Category Details -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-base">Rekap Detail Per Kategori</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 text-xs uppercase tracking-wider font-semibold bg-slate-50">
                        <th class="py-4 px-6">Nama Kategori</th>
                        <th class="py-4 px-6 text-right">Tarif Sukses</th>
                        <th class="py-4 px-6 text-right">Tarif Gagal</th>
                        <th class="py-4 px-6 text-center">Jumlah Sukses</th>
                        <th class="py-4 px-6 text-center">Jumlah Gagal</th>
                        <th class="py-4 px-6 text-center font-bold">Total Tugas</th>
                        <th class="py-4 px-6 text-right font-bold">Subtotal Gaji</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($rekap as $item)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <td class="py-4 px-6 font-bold text-slate-800">{{ $item->nama_kategori }}</td>
                            <td class="py-4 px-6 text-right text-slate-600">Rp{{ number_format($item->tarif_berhasil, 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-right text-slate-600">
                                @if($item->tarif_gagal > 0)
                                    Rp{{ number_format($item->tarif_gagal, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-400">Rp0</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center font-semibold text-emerald-600">{{ $item->total_berhasil }}</td>
                            <td class="py-4 px-6 text-center font-semibold text-rose-500">{{ $item->total_gagal }}</td>
                            <td class="py-4 px-6 text-center font-bold text-slate-700 bg-slate-50/30">{{ $item->total_pekerjaan }}</td>
                            <td class="py-4 px-6 text-right font-extrabold text-indigo-900 bg-slate-50/30">
                                Rp{{ number_format($item->total_gaji, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Tidak ada data pekerjaan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Table Card: Daily Details -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-base">Rincian Pekerjaan Harian</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 text-xs uppercase tracking-wider font-semibold bg-slate-50">
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6 text-center">Tugas Sukses</th>
                        <th class="py-4 px-6 text-center">Tugas Gagal</th>
                        <th class="py-4 px-6 text-center">Total Volume</th>
                        <th class="py-4 px-6 text-right">Pendapatan Harian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($dailyBreakdown as $day)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <td class="py-4 px-6 font-semibold text-slate-700">
                                {{ \Carbon\Carbon::parse($day->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-4 px-6 text-center font-semibold text-emerald-600">{{ $day->total_berhasil }}</td>
                            <td class="py-4 px-6 text-center font-semibold text-rose-500">{{ $day->total_gagal }}</td>
                            <td class="py-4 px-6 text-center font-bold text-slate-700 bg-slate-50/30">{{ $day->total_pekerjaan }}</td>
                            <td class="py-4 px-6 text-right font-extrabold text-indigo-900 bg-slate-50/30">
                                Rp{{ number_format($day->total_gaji, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                Tidak ada catatan pekerjaan harian untuk bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
