<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Exports\PeminjamanExport;
use App\Exports\PengembalianExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        return view('petugas.laporan.index');
    }


    public function exportPeminjaman(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string'],
            'tanggal' => ['nullable', 'date'],
        ]);

        return Excel::download(
            new PeminjamanExport(
                $validated['search'] ?? null,
                $validated['tanggal'] ?? null
            ),
            'laporan-peminjaman-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }


    
    public function exportPengembalian(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string'],
            'tanggal_selesai' => ['nullable', 'date'],
        ]);

        return Excel::download(
            new PengembalianExport(
                $validated['search'] ?? null,
                $validated['tanggal_selesai'] ?? null
            ),
            'laporan-pengembalian-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }

}
