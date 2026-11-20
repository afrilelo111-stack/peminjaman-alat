<?php

namespace App\Exports;

use App\Models\Pengembalian;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PengembalianExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
        return Pengembalian::query()
            ->with([
                'peminjaman.peminjam',
                'petugas',
                'detailPengembalian.detailPeminjaman.alat',
            ])
            ->when($this->search, function ($query, $search) {
                $query->whereHas('peminjaman.peminjam', function ($user) use ($search) {
                    $user->where('name', 'like', "%{$search}%");
                });
            })
            ->when($this->tanggal, function ($query, $tanggal) {
                $query->whereDate('tanggal_pengembalian', $tanggal);
            })
            ->latest();
    }

    public function headings(): array
    {
        return [
            'ID Pengembalian',
            'ID Peminjaman',
            'Nama Peminjam',
            'Nama Petugas',
            'Nama Alat',
            'Jumlah Dikembalikan',
            'Kondisi Alat',
            'Tanggal Pengembalian',
        ];
    }

    public function map($pengembalian): array
    {
        return [
            $pengembalian->id,
            $pengembalian->peminjaman_id,
            $pengembalian->peminjaman->peminjam->name ?? '-',
            $pengembalian->petugas->name ?? '-',
            $pengembalian->detailPengembalian
                ->map(fn ($detail) => $detail->detailPeminjaman->alat->nama_alat ?? '-')
                ->implode(', '),
            $pengembalian->detailPengembalian->sum('jumlah_dikembalikan'),
            $pengembalian->detailPengembalian
                ->map(fn ($detail) => $detail->kondisi_alat)
                ->unique()
                ->implode(', '),
            $pengembalian->tanggal_pengembalian?->format('d-m-Y'),
        ];
    }
}
