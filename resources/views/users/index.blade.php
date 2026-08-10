@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="space-y-8">
    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen User</h1>
            <p class="text-slate-500 mt-1 text-sm">Tambahkan, perbarui, atau hapus akun administrator dan teknisi lapangan</p>
        </div>
        
        <button type="button" 
                onclick="openModal('add-user-modal')"
                class="self-start sm:self-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-4.5 py-3 rounded-2xl transition-all duration-200 cursor-pointer shadow-lg shadow-blue-500/15 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
            <span>Tambah User</span>
        </button>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-lg">Daftar Pengguna Aplikasi</h3>
            <span class="text-xs bg-slate-50 border border-slate-100 px-3.5 py-1.5 rounded-full font-bold text-slate-500">
                Total: {{ $users->count() }} Pengguna
            </span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-450 text-[10px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 text-center w-16">No</th>
                        <th class="py-4 px-6">Pengguna</th>
                        <th class="py-4 px-6">Peran / Hak Akses</th>
                        <th class="py-4 px-6">Tanggal Terdaftar</th>
                        <th class="py-4 px-6 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @foreach($users as $index => $u)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            <td class="py-4 px-6 text-center font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-10 h-10 rounded-full {{ $u->is_admin ? 'bg-indigo-50 border-indigo-100 text-indigo-700' : 'bg-blue-50 border-blue-100 text-blue-700' }} border flex items-center justify-center font-extrabold shadow-sm">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 leading-none">{{ $u->name }}</p>
                                        <p class="text-xs text-slate-400 mt-1.5 leading-none">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($u->is_admin)
                                    <span class="inline-flex items-center text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-100">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100">
                                        Teknisi
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-500 font-medium">
                                {{ $u->created_at->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Edit Button -->
                                    <button type="button" 
                                            onclick="openEditModal({{ json_encode($u) }})"
                                            class="inline-flex items-center justify-center p-2 rounded-xl border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 transition-all duration-200 cursor-pointer shadow-sm"
                                            title="Edit User">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path></svg>
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    @if($u->id !== Auth::id())
                                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini? Semua data log terkait user ini akan ikut terhapus.')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center p-2 rounded-xl border border-slate-200 text-slate-500 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-all duration-200 cursor-pointer shadow-sm"
                                                title="Hapus User">
                                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
                                        </button>
                                    </form>
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

<!-- Modal 1: Tambah User -->
<div id="add-user-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Backdrop Backdrop -->
        <div onclick="closeModal('add-user-modal')" class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm"></div>

        <!-- Modal content card -->
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl border border-slate-200 shadow-2xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-lg">Tambah User Baru</h3>
                <button type="button" onclick="closeModal('add-user-modal')" class="text-slate-400 hover:text-slate-600 transition cursor-pointer">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <!-- Name -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-950 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all duration-200" placeholder="Contoh: Budi Santoso">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Alamat Email</label>
                    <input type="email" name="email" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-950 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all duration-200" placeholder="nama@email.com">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Kata Sandi</label>
                    <input type="password" name="password" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-950 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all duration-200" placeholder="Min. 8 Karakter">
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Peran / Hak Akses</label>
                    <select name="is_admin" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-950 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all duration-200 cursor-pointer">
                        <option value="0" selected>Teknisi Lapangan</option>
                        <option value="1">Administrator</option>
                    </select>
                </div>

                <!-- Actions Button -->
                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" 
                            onclick="closeModal('add-user-modal')"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-bold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4.5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition cursor-pointer shadow-md shadow-blue-500/10">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Edit User -->
<div id="edit-user-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Backdrop -->
        <div onclick="closeModal('edit-user-modal')" class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm"></div>

        <!-- Modal content card -->
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl border border-slate-200 shadow-2xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-lg">Edit User</h3>
                <button type="button" onclick="closeModal('edit-user-modal')" class="text-slate-400 hover:text-slate-600 transition cursor-pointer">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="edit-user-form" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')
                <!-- Name -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Nama Lengkap</label>
                    <input type="text" id="edit-name" name="name" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-950 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all duration-200" placeholder="Nama Lengkap">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Alamat Email</label>
                    <input type="email" id="edit-email" name="email" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-950 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all duration-200" placeholder="nama@email.com">
                </div>

                <!-- Password (Optional) -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Kata Sandi Baru (Opsional)</label>
                    <input type="password" name="password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-950 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all duration-200" placeholder="Biarkan kosong jika tidak diganti">
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Peran / Hak Akses</label>
                    <select id="edit-role" name="is_admin" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-950 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all duration-200 cursor-pointer">
                        <option value="0">Teknisi Lapangan</option>
                        <option value="1">Administrator</option>
                    </select>
                </div>

                <!-- Actions Button -->
                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" 
                            onclick="closeModal('edit-user-modal')"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-bold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4.5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition cursor-pointer shadow-md shadow-blue-500/10">
                        Perbarui User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function openEditModal(user) {
        // Set action form dynamically
        const form = document.getElementById('edit-user-form');
        form.action = `/users/${user.id}`;
        
        // Fill fields
        document.getElementById('edit-name').value = user.name;
        document.getElementById('edit-email').value = user.email;
        document.getElementById('edit-role').value = user.is_admin ? "1" : "0";
        
        // Open
        openModal('edit-user-modal');
    }
</script>
@endsection
