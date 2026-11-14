<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPraktikTerapis extends Model
{
    public const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    protected $table = 'jadwal_praktik_terapis';

    protected $fillable = ['terapis_id', 'hari', 'jam_mulai', 'jam_selesai', 'kuota_harian'];

    protected function casts(): array
    {
        return ['kuota_harian' => 'integer'];
    }

    public function terapis(): BelongsTo
    {
        return $this->belongsTo(Terapis::class, 'terapis_id');
    }
}
