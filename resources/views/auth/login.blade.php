@extends('layouts.app')

@section('title', 'Masuk Ke Akun Anda')

@section('content')
<div class="min-h-[70vh] flex flex-col items-center justify-center py-6 px-4">
    <div class="w-full max-w-md">
        <!-- Logo / Intro -->
        <div class="text-center mb-8">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 items-center justify-center text-white font-extrabold text-2xl shadow-xl shadow-blue-500/25 mb-4">
                <span>G</span>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
            <p class="text-slate-500 mt-2 text-sm">Masuk untuk mengelola dan melihat kalkulasi estimasi gajian Anda</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-8 md:p-10 transition-all duration-300 hover:shadow-2xl hover:shadow-slate-200/50">
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 10-2.636 6.364M16.5 12V8.25"></path></svg>
                        </div>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               value="{{ old('email') }}" 
                               class="pl-10.5 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm text-slate-900 placeholder:text-slate-450 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all duration-200" 
                               placeholder="nama@email.com">
                    </div>
                    @error('email')
                    <p class="text-xs text-rose-600 mt-2 font-medium flex items-center"><svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path></svg>
                        </div>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               required 
                               class="pl-10.5 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm text-slate-900 placeholder:text-slate-450 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all duration-200" 
                               placeholder="••••••••">
                    </div>
                    @error('password')
                    <p class="text-xs text-rose-600 mt-2 font-medium flex items-center"><svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember" 
                           name="remember" 
                           type="checkbox" 
                           class="w-4.5 h-4.5 text-blue-600 border-slate-350 bg-slate-50 rounded-lg focus:ring-blue-500 focus:ring-offset-0 cursor-pointer">
                    <label for="remember" class="ml-2 text-sm text-slate-600 font-medium select-none cursor-pointer">Ingat saya di perangkat ini</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm px-4 py-4 rounded-2xl shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/30 active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-200 cursor-pointer">
                    Masuk Ke Dashboard
                </button>
            </form>

            <!-- Registration Link -->
            <div class="text-center mt-6 pt-6 border-t border-slate-100">
                <span class="text-sm text-slate-500">Belum punya akun?</span>
                <a href="{{ route('register') }}" class="ml-1 text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                    Daftar Sekarang
                </a>
            </div>
        </div>

        <!-- Seeder Info Card -->
        <div class="mt-8 bg-blue-50/50 rounded-2xl border border-blue-100 p-4.5 text-center">
            <h4 class="text-xs font-bold uppercase tracking-wider text-blue-800 mb-1">Akun Demo Default</h4>
            <p class="text-xs text-blue-700 leading-normal mb-2">Gunakan akun berikut untuk pengujian cepat:</p>
            <div class="grid grid-cols-2 gap-3 text-left">
                <div class="bg-white rounded-xl p-2.5 border border-blue-100/50 text-[11px] shadow-sm">
                    <span class="font-bold text-slate-700 block mb-0.5">Teknisi Lapangan</span>
                    <span class="text-slate-500 block">Email: <strong class="text-blue-900 select-all">teknisi@gajian.com</strong></span>
                    <span class="text-slate-500 block">Pass: <strong class="text-blue-900 select-all">password</strong></span>
                </div>
                <div class="bg-white rounded-xl p-2.5 border border-blue-100/50 text-[11px] shadow-sm">
                    <span class="font-bold text-slate-700 block mb-0.5">Admin Gajian</span>
                    <span class="text-slate-500 block">Email: <strong class="text-blue-900 select-all">admin@gajian.com</strong></span>
                    <span class="text-slate-500 block">Pass: <strong class="text-blue-900 select-all">password</strong></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
