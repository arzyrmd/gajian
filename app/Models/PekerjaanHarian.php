<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PekerjaanHarian extends Model
{
    use HasFactory;

    protected $table = 'pekerjaan_harian';

    protected $fillable = [
        'user_id',
        'tanggal',
        'kategori_tarif_id',
        'jumlah_berhasil',
        'jumlah_gagal',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_berhasil' => 'integer',
        'jumlah_gagal' => 'integer',
    ];

    /**
     * Get the user (technician) who recorded this task log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tariff category for this task log.
     */
    public function kategoriTarif(): BelongsTo
    {
        return $this->belongsTo(KategoriTarif::class, 'kategori_tarif_id');
    }

    /**
     * Accessor for estimated salary of this single entry.
     * Note: Access with $log->estimasi_gaji (make sure kategoriTarif relationship is eager-loaded to avoid N+1 issues).
     */
    public function getEstimasiGajiAttribute(): int
    {
        if (array_key_exists('estimasi_gaji', $this->attributes)) {
            return (int) $this->attributes['estimasi_gaji'];
        }
        
        $tarifBerhasil = $this->kategoriTarif?->tarif_berhasil ?? 0;
        $tarifGagal = $this->kategoriTarif?->tarif_gagal ?? 0;

        return ($this->jumlah_berhasil * $tarifBerhasil) + ($this->jumlah_gagal * $tarifGagal);
    }

    /**
     * Accessor for total job counts (berhasil + gagal).
     */
    public function getTotalPekerjaanAttribute(): int
    {
        if (array_key_exists('total_pekerjaan', $this->attributes)) {
            return (int) $this->attributes['total_pekerjaan'];
        }
        
        return $this->jumlah_berhasil + $this->jumlah_gagal;
    }
}
