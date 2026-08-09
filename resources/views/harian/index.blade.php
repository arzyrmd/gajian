@extends('layouts.app')

@section('title', 'Pencatatan Harian')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header & Date Picker -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pekerjaan Harian</h1>
            <p class="text-slate-500 mt-1">Catat hasil kerja lapangan dan estimasikan pendapatan Anda hari ini</p>
        </div>
        
        <!-- Date Selector Form -->
        <form action="{{ route('harian') }}" method="GET" class="bg-white p-3 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 pl-2">Pilih Tanggal:</span>
            <input type="date" 
                   name="tanggal" 
                   value="{{ $selectedDate }}" 
                   onchange="this.form.submit()" 
                   class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 cursor-pointer">
        </form>
    </div>

    <!-- Main Content Grid (Form Left, History/Summary Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Input Form (Spans 2 cols on desktop) -->
        <div class="lg:col-span-2 space-y-6">
            <form id="daily-form" action="{{ route('harian.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $selectedDate }}">

                <!-- Grid of Category Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    @foreach($categoryDetails as $cat)
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 hover:shadow-md transition-all duration-200 flex flex-col justify-between" 
                         data-category-id="{{ $cat->id }}"
                         data-tarif-berhasil="{{ $cat->tarif_berhasil }}"
                         data-tarif-gagal="{{ $cat->tarif_gagal }}">
                        <div>
                            <!-- Header / Badge -->
                            <div class="flex justify-between items-start gap-2 mb-4">
                                <h3 class="font-bold text-slate-800 text-base leading-tight">{{ $cat->nama_kategori }}</h3>
                            </div>
                            
                            <!-- Tariff Information Box -->
                            <div class="bg-slate-50/80 rounded-2xl p-3 mb-5 border border-slate-100 flex justify-between text-xs">
                                <div>
                                    <span class="text-slate-400 block mb-0.5 uppercase tracking-wide">Tarif Berhasil</span>
                                    <span class="font-bold text-emerald-600">Rp{{ number_format($cat->tarif_berhasil, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-right border-l border-slate-200 pl-4">
                                    <span class="text-slate-400 block mb-0.5 uppercase tracking-wide">Tarif Gagal</span>
                                    <span class="font-bold text-slate-500">
                                        @if($cat->tarif_gagal > 0)
                                            Rp{{ number_format($cat->tarif_gagal, 0, ',', '.') }}
                                        @else
                                            Rp0 (Tidak dibayar)
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Quantities Input fields -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Berhasil</label>
                                    <input type="number" 
                                           name="pekerjaan[{{ $cat->id }}][berhasil]" 
                                           value="{{ old('pekerjaan.'.$cat->id.'.berhasil', $cat->berhasil) }}" 
                                           min="0" 
                                           required
                                           data-input-type="berhasil"
                                           class="w-full text-center bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm font-semibold text-slate-800 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all duration-150">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Gagal</label>
                                    <input type="number" 
                                           name="pekerjaan[{{ $cat->id }}][gagal]" 
                                           value="{{ old('pekerjaan.'.$cat->id.'.gagal', $cat->gagal) }}" 
                                           min="0" 
                                           required
                                           data-input-type="gagal"
                                           class="w-full text-center bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-sm font-semibold text-slate-800 focus:outline-none focus:bg-white focus:ring-2 focus:ring-rose-500/10 focus:border-rose-500 transition-all duration-150">
                                </div>
                            </div>
                            
                            <!-- Subtotal display inside card -->
                            <div class="pt-3 border-t border-slate-100 flex justify-between items-center text-sm font-medium">
                                <span class="text-slate-400">Estimasi Kategori:</span>
                                <span class="font-bold text-slate-700 category-subtotal">Rp{{ number_format($cat->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Submit Banner -->
                <div class="bg-slate-900 rounded-3xl p-6 text-white flex flex-col md:flex-row items-center justify-between gap-4 shadow-lg shadow-slate-900/10">
                    <div>
                        <h4 class="font-bold text-lg">Simpan Laporan Hari Ini</h4>
                        <p class="text-slate-400 text-xs mt-0.5">Pastikan data jumlah pekerjaan berhasil dan gagal telah diisi dengan benar.</p>
                    </div>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-md hover:shadow-lg active:scale-95 transition-all duration-150 w-full md:w-auto cursor-pointer">
                        Simpan & Hitung Gaji
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Side: Live Calculations & History list -->
        <div class="space-y-6">
            
            <!-- Card 1: Estimated Daily Salary Summary (Live/Updated by JS) -->
            <div class="bg-gradient-to-tr from-slate-900 to-indigo-950 rounded-3xl border border-slate-850 shadow-xl p-6 text-white relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-36 h-36 rounded-full bg-blue-500/10 blur-xl"></div>
                <div class="absolute -left-16 -bottom-16 w-36 h-36 rounded-full bg-indigo-500/10 blur-xl"></div>

                <div class="relative z-10">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Estimasi Gaji Hari Ini</span>
                    <!-- Big Salary Display -->
                    <div id="live-total-salary" class="text-4xl font-extrabold text-white mt-2 tracking-tight">
                        Rp{{ number_format($totalGaji, 0, ',', '.') }}
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mt-6 pt-5 border-t border-white/10 text-xs">
                        <div>
                            <span class="text-slate-400 block mb-0.5">Total Pekerjaan</span>
                            <span id="live-total-jobs" class="font-bold text-base text-slate-100">{{ $totalPekerjaan }} Tugas</span>
                        </div>
                        <div class="border-l border-white/10 pl-4">
                            <span class="text-slate-400 block mb-0.5">Tanggal Laporan</span>
                            <span class="font-bold text-base text-slate-100">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: History List with Filter -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-900 text-lg mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                    <span>Riwayat Pengisian</span>
                </h3>

                <!-- Filter form -->
                <form action="{{ route('harian') }}" method="GET" class="space-y-3 mb-6 p-4 bg-slate-50 rounded-2xl border border-slate-150">
                    <input type="hidden" name="tanggal" value="{{ $selectedDate }}">
                    
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Mulai</label>
                            <input type="date" 
                                   name="start_date" 
                                   value="{{ $startDate }}" 
                                   class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 focus:outline-none focus:border-blue-500 transition duration-150">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Sampai</label>
                            <input type="date" 
                                   name="end_date" 
                                   value="{{ $endDate }}" 
                                   class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 focus:outline-none focus:border-blue-500 transition duration-150">
                        </div>
                    </div>
                    
                    <div class="flex gap-2 pt-1">
                        <button type="submit" 
                                class="flex-1 bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs py-2 rounded-xl transition duration-150 cursor-pointer text-center">
                            Filter
                        </button>
                        @if($startDate || $endDate)
                        <a href="{{ route('harian', ['tanggal' => $selectedDate]) }}" 
                           class="flex-1 bg-slate-200 hover:bg-slate-350 text-slate-700 font-semibold text-xs py-2 rounded-xl transition duration-150 text-center">
                            Reset
                        </a>
                        @endif
                    </div>
                </form>

                <!-- History Dates Scrollable List -->
                <div class="space-y-3 max-h-[350px] overflow-y-auto pr-1">
                    @forelse($history as $hist)
                    <a href="{{ route('harian', ['tanggal' => $hist->tanggal->toDateString(), 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                       class="block p-4 rounded-2xl border transition-all duration-200 {{ $hist->tanggal->toDateString() === $selectedDate ? 'bg-blue-50 border-blue-200 shadow-sm' : 'bg-slate-50 border-slate-100 hover:border-slate-300 hover:bg-slate-100/50' }}">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-bold text-sm text-slate-800 block">{{ $hist->tanggal->translatedFormat('d F Y') }}</span>
                                <span class="text-xs text-slate-500 mt-1 block font-medium">{{ $hist->total_pekerjaan }} pekerjaan terdata</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-sm block {{ $hist->tanggal->toDateString() === $selectedDate ? 'text-blue-700' : 'text-slate-850' }}">
                                    Rp{{ number_format($hist->total_gaji, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] text-slate-400 uppercase font-semibold">Estimasi Gaji</span>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-8 text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-xs">Belum ada riwayat pengisian.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<!-- JS Calculator Logic -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('daily-form');
        const cards = form.querySelectorAll('[data-category-id]');
        const liveSalaryEl = document.getElementById('live-total-salary');
        const liveJobsEl = document.getElementById('live-total-jobs');

        // Format integer to IDR Currency format
        function formatRupiah(value) {
            return 'Rp' + value.toLocaleString('id-ID', { minimumFractionDigits: 0 });
        }

        // Live calculation logic
        function calculateLiveTotals() {
            let totalGaji = 0;
            let totalPekerjaan = 0;

            cards.forEach(card => {
                const tarifBerhasil = parseInt(card.getAttribute('data-tarif-berhasil')) || 0;
                const tarifGagal = parseInt(card.getAttribute('data-tarif-gagal')) || 0;

                const berhasilInput = card.querySelector('[data-input-type="berhasil"]');
                const gagalInput = card.querySelector('[data-input-type="gagal"]');

                const berhasil = Math.max(0, parseInt(berhasilInput.value) || 0);
                const gagal = Math.max(0, parseInt(gagalInput.value) || 0);

                // Calculate subtotal
                const subtotal = (berhasil * tarifBerhasil) + (gagal * tarifGagal);
                
                // Update Card Subtotal HTML
                const subtotalEl = card.querySelector('.category-subtotal');
                if (subtotalEl) {
                    subtotalEl.textContent = formatRupiah(subtotal);
                }

                totalGaji += subtotal;
                totalPekerjaan += (berhasil + gagal);
            });

            // Update main summary panel
            if (liveSalaryEl) {
                liveSalaryEl.textContent = formatRupiah(totalGaji);
            }
            if (liveJobsEl) {
                liveJobsEl.textContent = totalPekerjaan + ' Tugas';
            }
        }

        // Attach event listeners to all input boxes
        cards.forEach(card => {
            const inputs = card.querySelectorAll('input[type="number"]');
            inputs.forEach(input => {
                // Perform calculations on type or blur
                input.addEventListener('input', calculateLiveTotals);
                input.addEventListener('keyup', calculateLiveTotals);
                input.addEventListener('change', calculateLiveTotals);
                
                // Enforce integer >= 0 on blur
                input.addEventListener('blur', function() {
                    let val = parseInt(this.value) || 0;
                    if (val < 0) val = 0;
                    this.value = val;
                    calculateLiveTotals();
                });
            });
        });

        // Initialize calculations on page load
        calculateLiveTotals();
    });
</script>
@endsection
