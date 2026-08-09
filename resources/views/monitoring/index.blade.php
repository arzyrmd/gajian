@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-2 sm:px-4">
    <!-- Header Block -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Monitoring Gaji Teknisi</h1>
            <p class="text-sm text-slate-500 mt-1">Lacak pencapaian, jumlah pekerjaan, dan estimasi gaji seluruh teknisi.</p>
        </div>
        
        <!-- Month/Year Filter Form -->
        <form action="{{ route('monitoring') }}" method="GET" class="bg-white p-2.5 rounded-2xl border border-slate-200 shadow-sm flex flex-wrap items-center gap-2">
            <select name="month" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl px-3 py-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                @foreach($months as $mNum => $mName)
                    <option value="{{ $mNum }}" {{ $selectedMonth == $mNum ? 'selected' : '' }}>
                        {{ $mName }}
                    </option>
                @endforeach
            </select>
            
            <select name="year" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl px-3 py-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                @foreach($years as $yr)
                    <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>
                        {{ $yr }}
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl px-4 py-2 transition duration-150 cursor-pointer">
                Filter
            </button>
        </form>
    </div>

    <!-- Technician Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($data as $tech)
            <div class="bg-white rounded-3xl border border-slate-150 shadow-sm hover:shadow-md hover:border-slate-200 transition-all duration-350 flex flex-col justify-between overflow-hidden">
                <!-- Card Header -->
                <div class="p-5 border-b border-slate-100 flex items-center space-x-3.5">
                    <!-- Avatar Initials -->
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-slate-100 to-slate-200 flex items-center justify-center text-slate-700 font-bold shadow-inner">
                        {{ strtoupper(substr($tech->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-base font-semibold text-slate-800 truncate leading-snug">{{ $tech->name }}</h3>
                        <p class="text-xs text-slate-500 truncate leading-none mt-1">{{ $tech->email }}</p>
                    </div>
                </div>

                <!-- Stats breakdown -->
                <div class="p-5 grid grid-cols-3 gap-4 text-center bg-slate-50/50">
                    <div>
                        <span class="block text-xs text-slate-400 font-medium">Berhasil</span>
                        <span class="block text-sm font-bold text-emerald-600 mt-1">{{ number_format($tech->total_berhasil) }}</span>
                    </div>
                    <div class="border-x border-slate-200/60">
                        <span class="block text-xs text-slate-400 font-medium">Gagal</span>
                        <span class="block text-sm font-bold text-rose-600 mt-1">{{ number_format($tech->total_gagal) }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-400 font-medium">Pekerjaan</span>
                        <span class="block text-sm font-bold text-slate-700 mt-1">{{ number_format($tech->total_pekerjaan) }}</span>
                    </div>
                </div>

                <!-- Gaji & Action Section -->
                <div class="p-5 flex items-center justify-between gap-4 mt-auto border-t border-slate-100 bg-white">
                    <div>
                        <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Estimasi Gaji</span>
                        <span class="block text-base font-extrabold text-blue-600 mt-0.5">
                            Rp {{ number_format($tech->total_gaji, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <a href="{{ route('bulanan', ['user_id' => $tech->id, 'month' => $selectedMonth, 'year' => $selectedYear]) }}" 
                       class="inline-flex items-center space-x-1.5 px-3 py-2 rounded-xl text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-200 shadow-sm shadow-blue-500/5 cursor-pointer">
                        <span>Detail</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-slate-50 border border-dashed border-slate-300 rounded-3xl p-12 text-center">
                <svg class="mx-auto w-12 h-12 text-slate-400 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                <h3 class="text-sm font-semibold text-slate-800">Tidak ada teknisi</h3>
                <p class="text-xs text-slate-500 mt-1">Belum ada akun teknisi terdaftar dalam sistem.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
