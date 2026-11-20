<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PeminjamanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $search;
    protected $tanggal;

    public function __construct($search = null, $tanggal = null)
    {
        $this->search = $search;
        $this->tanggal = $tanggal;
    }

    public function query(): Builder
    {
        return Peminjaman::query()
            ->with(['peminjam', 'detailPeminjaman.alat'])
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('peminjam', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
                });
            })
            ->when($this->tanggal, function ($query, $tanggal) {
                $query->whereDate('tanggal_pinjam', $tanggal);
            })
            ->latest();
    }

    public function headings(): array
    {
        return [
            'ID Peminjaman',
            'Nama Peminjam',
            'Nama Alat',
            'Jumlah',
            'Tanggal Pinjam',
            'Rencana Kembali',
            'Status',
        ];
    }

    public function map($peminjaman): array
    {
        return [
            $peminjaman->id,
            $peminjaman->peminjam->name ?? '-',
            $peminjaman->detailPeminjaman
                ->map(fn ($detail) => $detail->alat->nama_alat . ' (' . $detail->jumlah . ')')
                ->implode(', '),
            $peminjaman->detailPeminjaman->sum('jumlah'),
            $peminjaman->tanggal_pinjam?->format('d-m-Y'),
            $peminjaman->tanggal_rencana_kembali?->format('d-m-Y'),
            $peminjaman->status,
        ];
    }
}
