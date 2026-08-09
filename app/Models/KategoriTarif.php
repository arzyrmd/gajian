<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriTarif extends Model
{
    use HasFactory;

    protected $table = 'kategori_tarif';

    protected $fillable = [
        'nama_kategori',
        'tarif_berhasil',
        'tarif_gagal',
    ];

    /**
     * Get the logs associated with this tariff category.
     */
    public function pekerjaanHarian(): HasMany
    {
        return $this->hasMany(PekerjaanHarian::class, 'kategori_tarif_id');
    }
}
