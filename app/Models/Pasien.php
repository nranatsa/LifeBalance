<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    protected $table = 'pasien';

    protected $fillable = [
        'user_id', 'foto_profil', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'catatan_medis',
    ];

    protected function casts(): array
    {
        return ['tanggal_lahir' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reservasi(): HasMany
    {
        return $this->hasMany(ReservasiAntrean::class, 'pasien_id');
    }

    public function getUmurAttribute(): ?int
    {
        return $this->tanggal_lahir?->age;
    }
}
