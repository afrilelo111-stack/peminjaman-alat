<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengembalian extends Model
{
    protected $table = 'detail_pengembalians';

    public $timestamps = false;

    protected $fillable = [
        'pengembalian_id',
        'detail_peminjaman_id',
        'jumlah_dikembalikan',
        'kondisi_alat',
        'gambar_bukti',
    ];

    public function pengembalian(): BelongsTo
    {
        return $this->belongsTo(Pengembalian::class);
    }

    public function detailPeminjaman(): BelongsTo
    {
        return $this->belongsTo(DetailPeminjaman::class);
    }
}
