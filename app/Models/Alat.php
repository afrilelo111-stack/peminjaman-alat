<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Alat extends Model
{
    protected $table = 'alats';

    protected $fillable = [
        'kategori_id',
        'nama_alat',
        'deskripsi',
        'kode_alat',
        'gambar',
        'stok',
        'kondisi',
    ];
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }
}