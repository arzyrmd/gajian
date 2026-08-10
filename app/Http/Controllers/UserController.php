<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Toggle is_admin role for the specified user.
     */
    public function toggleRole(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah peran Anda sendiri.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        $roleName = $user->is_admin ? 'Admin' : 'Teknisi';
        return back()->with('success', "Peran {$user->name} berhasil diubah menjadi {$roleName}.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return back()->with('success', "Akun {$user->name} berhasil dihapus.");
    }
}
