@extends('layouts.app')

@section('title', 'Detail Pekerjaan ' . $user->name)

@section('content')
<div class="space-y-8">
    <!-- Back Button & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <a href="{{ route('monitoring.index', ['month' => $month, 'year' => $year]) }}" 
               class="p-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-all duration-200 cursor-pointer shadow-sm"
               title="Kembali ke Ringkasan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $user->name }}</h1>
                <p class="text-slate-500 text-sm mt-0.5">{{ $user->email }} &bull; Detail Pekerjaan</p>
            </div>
        </div>
        
        <span class="self-start sm:self-center text-xs bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl font-bold text-slate-500 shadow-sm">
            Periode: {{ [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'][$month] }} {{ $year }}
        </span>
    </div>

    <!-- Summary Widgets for Specific Technician -->
    @php
        $totalBerhasil = $logs->sum('jumlah_berhasil');
        $totalGagal = $logs->sum('jumlah_gagal');
        $totalGaji = $logs->sum(function($log) { return $log->estimasi_gaji; });
        $totalJobs = $totalBerhasil + $totalGagal;
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Gaji Bulanan -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pendapatan Bulan Ini</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($totalGaji, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Card 2: Total Pekerjaan -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 12.408l-2.25-2.25m0 0l2.25-2.25m-2.25 2.25h10.5M4.5 19.5A2.25 2.25 0 012.25 17.25V6.108c0-1.135.845-2.098 1.976-2.192a48.424 48.424 0 011.123-.08"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Pekerjaan</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalJobs, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Card 3: Pekerjaan Berhasil -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Berhasil</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalBerhasil, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Card 4: Pekerjaan Gagal -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Gagal</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalGagal, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Daily Log Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg">Log Pekerjaan Harian</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-450 text-[10px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 text-center w-16">No</th>
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6">Kategori Pekerjaan / Tarif</th>
                        <th class="py-4 px-6 text-center">Berhasil</th>
                        <th class="py-4 px-6 text-center">Gagal</th>
                        <th class="py-4 px-6 text-right">Estimasi Gaji Harian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($logs as $index => $log)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            <td class="py-4.5 px-6 text-center font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-4.5 px-6 font-semibold text-slate-850">
                                {{ $log->tanggal->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-4.5 px-6">
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $log->kategoriTarif->nama_kategori }}</p>
                                    <p class="text-xs text-slate-400 mt-1">
                                        (Berhasil: Rp {{ number_format($log->kategoriTarif->tarif_berhasil, 0, ',', '.') }} &bull; Gagal: Rp {{ number_format($log->kategoriTarif->tarif_gagal, 0, ',', '.') }})
                                    </p>
                                </div>
                            </td>
                            <td class="py-4.5 px-6 text-center font-semibold text-indigo-600">{{ number_format($log->jumlah_berhasil, 0, ',', '.') }}</td>
                            <td class="py-4.5 px-6 text-center font-medium text-slate-500">{{ number_format($log->jumlah_gagal, 0, ',', '.') }}</td>
                            <td class="py-4.5 px-6 text-right font-extrabold text-emerald-600">Rp {{ number_format($log->estimasi_gaji, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"></path></svg>
                                    <p class="font-medium">Tidak ada log pekerjaan untuk periode ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
