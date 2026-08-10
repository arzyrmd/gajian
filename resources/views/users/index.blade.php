@extends('layouts.app')

@section('title', 'Manage User')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white rounded-3xl p-6 md:p-8 shadow-xl">
        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Manajemen Pengguna (User)</h1>
        <p class="text-indigo-200 text-sm mt-2">Daftar seluruh akun terdaftar. Anda dapat mengubah peran pengguna atau menghapus akun teknisi/admin lainnya.</p>
    </div>

    <!-- Users List Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Daftar Akun Terdaftar</h3>
            <span class="text-xs text-slate-500 font-bold bg-white px-2.5 py-1 border border-slate-250 rounded-xl shadow-xs">Total: {{ $users->count() }} Pengguna</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 text-xs uppercase tracking-wider font-semibold bg-slate-50">
                        <th class="py-4 px-6">Informasi Akun</th>
                        <th class="py-4 px-6 text-center">Peran</th>
                        <th class="py-4 px-6 text-center">Tanggal Terdaftar</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <!-- Name / Email -->
                            <td class="py-4.5 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block">
                                            {{ $user->name }}
                                            @if($user->id === Auth::id())
                                                <span class="ml-1 text-[10px] bg-slate-100 text-slate-600 font-bold px-1.5 py-0.5 rounded border border-slate-200">Anda</span>
                                            @endif
                                        </span>
                                        <span class="text-xs text-slate-400 block mt-0.5">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Role Badge -->
                            <td class="py-4.5 px-6 text-center">
                                @if($user->is_admin)
                                    <span class="inline-flex text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-150 px-2.5 py-0.5 rounded-full">Admin</span>
                                @else
                                    <span class="inline-flex text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-150 px-2.5 py-0.5 rounded-full">Teknisi</span>
                                @endif
                            </td>
                            
                            <!-- Registration Date -->
                            <td class="py-4.5 px-6 text-center text-slate-500 text-xs">
                                {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}
                            </td>
                            
                            <!-- Actions (Toggle Role / Delete) -->
                            <td class="py-4.5 px-6 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    @if($user->id !== Auth::id())
                                        <!-- Toggle Role Button -->
                                        <form action="{{ route('users.toggle-role', ['user' => $user->id]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Apakah Anda yakin ingin mengubah peran {{ $user->name }}?')" 
                                                    class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition duration-150 cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"></path></svg>
                                                <span>Ubah Peran</span>
                                            </button>
                                        </form>

                                        <!-- Delete Account Button -->
                                        <form action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }} secara permanen? Seluruh riwayat pekerjaan harian milik pengguna ini juga akan ikut dihapus.')" 
                                                    class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 transition duration-150 cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium italic">Tidak ada tindakan</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
