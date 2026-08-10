@extends('layouts.app')

@section('title', 'Monitoring Gaji Teknisi')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-indigo-900 to-slate-900 text-white rounded-3xl p-6 md:p-8 shadow-xl flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Monitoring Gaji Teknisi</h1>
            <p class="text-indigo-200 text-sm mt-2">Pantau pencapaian kerja harian dan total gaji yang diperoleh seluruh teknisi lapangan.</p>
        </div>
        
        <!-- Month & Year Filter Form -->
        <form action="{{ route('monitoring.index') }}" method="GET" class="flex flex-wrap items-center gap-3 bg-white/10 p-3 rounded-2xl border border-white/15 backdrop-blur-md">
            <div>
                <select name="month" class="bg-slate-800 text-white text-xs font-semibold px-3 py-2 rounded-xl border border-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="year" class="bg-slate-800 text-white text-xs font-semibold px-3 py-2 rounded-xl border border-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 px-4 py-2 rounded-xl text-xs font-bold transition duration-250 active:scale-95 shadow-lg shadow-indigo-600/20 cursor-pointer">
                Filter
            </button>
        </form>
    </div>

    <!-- Technicians Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 21H9.91A11.386 11.386 0 015 19.237v-.109m0 .109a11.386 11.386 0 004.912 1.763h.178A11.386 11.386 0 0015 19.237m-10-.109V19c0-1.113.285-2.16.786-3.07M5 19.237a9.38 9.38 0 01-2.625-.372 9.337 9.337 0 01-4.121-.952 4.125 4.125 0 017.533-2.493M10 5a3.5 3.5 0 11-7 0 3.5 3.5 0 017 0zm6.5 1.5a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Teknisi</p>
                <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ $technicians->count() }} Orang</p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Pekerjaan Berhasil</p>
                <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ number_format($technicians->sum('total_berhasil'), 0, ',', '.') }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.75 12 12 12c-.725 0-1.55-.22-2.121-.777-.572-.557-.572-1.457 0-2.014 1.171-.879 3.07-.879 4.242 0c.266.2.46.471.58.777M12 21V3"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Estimasi Total Pengeluaran</p>
                <p class="text-2xl font-bold text-indigo-900 mt-0.5">Rp {{ number_format($technicians->sum('total_gaji'), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Technicians List Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-800">Daftar Penghasilan Teknisi Lapangan</h3>
            <span class="text-xs text-slate-500 font-medium">Periode: <strong>{{ $months[$month] }} {{ $year }}</strong></span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 text-[11px] uppercase tracking-wider font-semibold bg-slate-50">
                        <th class="py-4 px-6">Nama Teknisi</th>
                        <th class="py-4 px-6 text-center">Kerja Berhasil</th>
                        <th class="py-4 px-6 text-center">Kerja Gagal</th>
                        <th class="py-4 px-6 text-center">Total Volume</th>
                        <th class="py-4 px-6 text-right">Estimasi Gaji</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($technicians as $tech)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <!-- Name / Email -->
                            <td class="py-4.5 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-xs">
                                        {{ strtoupper(substr($tech->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-slate-800 block">{{ $tech->name }}</span>
                                        <span class="text-xs text-slate-400 block mt-0.5">{{ $tech->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <!-- Success Jobs -->
                            <td class="py-4.5 px-6 text-center font-semibold text-emerald-600">
                                {{ number_format($tech->total_berhasil, 0, ',', '.') }}
                            </td>
                            <!-- Failed Jobs -->
                            <td class="py-4.5 px-6 text-center font-semibold text-rose-500">
                                {{ number_format($tech->total_gagal, 0, ',', '.') }}
                            </td>
                            <!-- Total Volume -->
                            <td class="py-4.5 px-6 text-center font-semibold text-slate-700">
                                {{ number_format($tech->total_pekerjaan, 0, ',', '.') }}
                            </td>
                            <!-- Total Salary -->
                            <td class="py-4.5 px-6 text-right font-extrabold text-indigo-900">
                                Rp {{ number_format($tech->total_gaji, 0, ',', '.') }}
                            </td>
                            <!-- Action Detail Link -->
                            <td class="py-4.5 px-6 text-center">
                                <a href="{{ route('monitoring.show', ['user' => $tech->id, 'month' => $month, 'year' => $year]) }}" 
                                   class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition duration-200 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span>Detail Rekap</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Tidak ada data teknisi terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
