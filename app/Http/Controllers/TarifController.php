<?php

namespace App\Http\Controllers;

use App\Models\KategoriTarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarifController extends Controller
{
    /**
     * Show the tariff configuration form.
     */
    public function index()
    {
        // Require administrator role
        if (!Auth::user()->is_admin) {
            return redirect()->route('harian')->with('error', 'Hanya administrator yang dapat mengubah pengaturan tarif.');
        }

        $tariffs = KategoriTarif::all();
        return view('tarif.index', compact('tariffs'));
    }

    /**
     * Update the tariffs.
     */
    public function update(Request $request)
    {
        // Require administrator role
        if (!Auth::user()->is_admin) {
            return redirect()->route('harian')->with('error', 'Hanya administrator yang dapat mengubah pengaturan tarif.');
        }

        $request->validate([
            'tarif' => 'required|array',
            'tarif.*.berhasil' => 'required|integer|min:0',
            'tarif.*.gagal' => 'required|integer|min:0',
        ], [
            'tarif.*.berhasil.min' => 'Tarif berhasil tidak boleh kurang dari 0.',
            'tarif.*.gagal.min' => 'Tarif gagal tidak boleh kurang dari 0.',
            'tarif.*.berhasil.integer' => 'Tarif berhasil harus berupa angka.',
            'tarif.*.gagal.integer' => 'Tarif gagal harus berupa angka.',
        ]);

        foreach ($request->input('tarif') as $id => $values) {
            KategoriTarif::where('id', $id)->update([
                'tarif_berhasil' => $values['berhasil'],
                'tarif_gagal' => $values['gagal'],
            ]);
        }

        return redirect()->route('tarif.index')
            ->with('success', 'Daftar tarif kategori berhasil diperbarui!');
    }
}
