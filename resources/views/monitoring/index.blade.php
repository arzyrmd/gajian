@extends('layouts.app')

@section('title', 'Monitoring Gaji Teknisi')

@section('content')
<div class="space-y-8">
    <!-- Page Header & Filter -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Monitoring Gaji</h1>
            <p class="text-slate-500 mt-1 text-sm">Lihat estimasi gaji bulanan dan ringkasan pekerjaan seluruh teknisi lapangan</p>
        </div>
        
        <!-- Filter Form -->
        <form action="{{ route('monitoring.index') }}" method="GET" class="flex items-center gap-3 bg-white p-2 rounded-2xl border border-slate-200 shadow-sm">
            <select name="month" class="bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer">
                @foreach([
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ] as $mNum => $mName)
                    <option value="{{ $mNum }}" {{ $month == $mNum ? 'selected' : '' }}>{{ $mName }}</option>
                @endforeach
            </select>
            
            <select name="year" class="bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer">
                @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all duration-200 cursor-pointer shadow-md shadow-blue-500/10">
                Filter
            </button>
        </form>
    </div>

    <!-- Summary Stats Widgets -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Teknisi -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Teknisi</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total_technicians'] }}</p>
            </div>
        </div>

        <!-- Card 2: Total Gaji -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Payout Gaji</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($stats['total_gaji'], 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Card 3: Total Berhasil -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pekerjaan Berhasil</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['total_berhasil'], 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Card 4: Total Gagal -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pekerjaan Gagal</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['total_gagal'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Technicians Salary List Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-lg">Daftar Gaji Bulanan Teknisi</h3>
            <span class="text-xs bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-full font-bold text-slate-500">
                Periode: {{ [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'][$month] }} {{ $year }}
            </span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-450 text-[10px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 text-center w-16">No</th>
                        <th class="py-4 px-6">Teknisi</th>
                        <th class="py-4 px-6 text-center">Pekerjaan Berhasil</th>
                        <th class="py-4 px-6 text-center">Pekerjaan Gagal</th>
                        <th class="py-4 px-6 text-right">Estimasi Payout</th>
                        <th class="py-4 px-6 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($technicians as $index => $tech)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            <td class="py-4.5 px-6 text-center font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-4.5 px-6">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-700 font-extrabold shadow-sm">
                                        {{ strtoupper(substr($tech->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 leading-none">{{ $tech->name }}</p>
                                        <p class="text-xs text-slate-400 mt-1.5 leading-none">{{ $tech->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4.5 px-6 text-center font-bold text-indigo-600">{{ number_format($tech->total_berhasil, 0, ',', '.') }}</td>
                            <td class="py-4.5 px-6 text-center font-semibold text-slate-500">{{ number_format($tech->total_gagal, 0, ',', '.') }}</td>
                            <td class="py-4.5 px-6 text-right font-extrabold text-emerald-600">Rp {{ number_format($tech->total_gaji, 0, ',', '.') }}</td>
                            <td class="py-4.5 px-6 text-center">
                                <a href="{{ route('monitoring.show', ['user' => $tech->id, 'month' => $month, 'year' => $year]) }}" 
                                   class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white border border-blue-100 hover:border-blue-600 text-xs font-bold transition-all duration-200 cursor-pointer shadow-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                                    <p class="font-medium">Tidak ada data teknisi terdaftar</p>
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
