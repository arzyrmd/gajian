@extends('layouts.app')

@section('title', 'Pengaturan Tarif Kategori')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('harian') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 uppercase tracking-wider mb-2.5 transition duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path></svg>
            <span>Kembali ke Dashboard</span>
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengaturan Tarif Kategori</h1>
        <p class="text-slate-500 mt-1">Ubah nominal tarif dasar pekerjaan berhasil dan gagal per kategori tugas</p>
    </div>

    <!-- Alert Alert for non-admins (Double-safety fallback) -->
    @if(!Auth::user()->is_admin)
    <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-3xl p-6 mb-8 flex items-start gap-4">
        <svg class="w-6 h-6 text-rose-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <h4 class="font-bold text-base">Akses Ditolak!</h4>
            <p class="text-xs text-rose-700 mt-1">Hanya pengguna dengan peran Administrator yang memiliki otorisasi untuk mengubah konfigurasi tarif di dalam database.</p>
        </div>
    </div>
    @else

    <!-- Settings Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-base">Daftar Tarif Dasar Pekerjaan</h3>
        </div>

        <form action="{{ route('tarif.update') }}" method="POST" class="p-6">
            @csrf

            <!-- Form Table -->
            <div class="overflow-x-auto -mx-6 mb-6">
                <table class="min-w-full divide-y divide-slate-150">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Kategori</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-slate-400 uppercase tracking-wider w-44">Tarif Berhasil (Rp)</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-slate-400 uppercase tracking-wider w-44">Tarif Gagal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150">
                        @foreach($tariffs as $tariff)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <!-- Category Name -->
                            <td class="px-6 py-4.5">
                                <span class="font-bold text-sm text-slate-800 block">{{ $tariff->nama_kategori }}</span>
                                <span class="text-slate-400 text-xs mt-1 block">ID: #{{ $tariff->id }}</span>
                            </td>

                            <!-- Tarif Berhasil Input -->
                            <td class="px-6 py-4.5 text-center whitespace-nowrap">
                                <div class="relative rounded-2xl shadow-sm max-w-xs mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-semibold text-slate-400">
                                        Rp
                                    </div>
                                    <input type="number" 
                                           name="tarif[{{ $tariff->id }}][berhasil]" 
                                           value="{{ old('tarif.'.$tariff->id.'.berhasil', $tariff->tarif_berhasil) }}" 
                                           min="0" 
                                           required
                                           class="pl-9.5 text-right w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 transition duration-150">
                                </div>
                                @error("tarif.{$tariff->id}.berhasil")
                                    <span class="text-[10px] text-rose-650 block mt-1">{{ $message }}</span>
                                @enderror
                            </td>

                            <!-- Tarif Gagal Input -->
                            <td class="px-6 py-4.5 text-center whitespace-nowrap">
                                <div class="relative rounded-2xl shadow-sm max-w-xs mx-auto">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-semibold text-slate-400">
                                        Rp
                                    </div>
                                    <input type="number" 
                                           name="tarif[{{ $tariff->id }}][gagal]" 
                                           value="{{ old('tarif.'.$tariff->id.'.gagal', $tariff->tarif_gagal) }}" 
                                           min="0" 
                                           required
                                           class="pl-9.5 text-right w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 transition duration-150">
                                </div>
                                @error("tarif.{$tariff->id}.gagal")
                                    <span class="text-[10px] text-rose-650 block mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('harian') }}" 
                   class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-3 rounded-2xl font-bold text-sm transition duration-150 cursor-pointer">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-md hover:shadow-lg active:scale-95 transition-all duration-150 cursor-pointer">
                    Simpan Perubahan Tarif
                </button>
            </div>

        </form>
    </div>
    @endif
</div>
@endsection
