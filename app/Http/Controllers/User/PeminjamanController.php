<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PeminjamanController extends Controller
{
    public function create(Alat $alat)
    {
        $alat->load('kategori');

        if ($alat->stok < 1 || $alat->kondisi !== 'Baik') {
            return redirect()
                ->route('user.alats.show', $alat->id)
                ->with('error', 'Alat ini sedang tidak tersedia untuk dipinjam.');
        }

        return view('user.peminjamans.index', compact('alat'));
    }

    public function store(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
            'tanggal_pinjam' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_rencana_kembali' => [
                'required',
                'date',
                'after_or_equal:tanggal_pinjam',
            ],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $alat) {
            $alat = Alat::whereKey($alat->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($alat->kondisi !== 'Baik' || $alat->stok < $validated['jumlah']) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Stok alat tidak mencukupi atau alat tidak tersedia.',
                ]);
            }

            $peminjaman = Peminjaman::create([
                'peminjam_id' => Auth::id(),
                'disetujui_oleh' => null,
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_rencana_kembali' => $validated['tanggal_rencana_kembali'],
                'status' => 'Menunggu',
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            $peminjaman->detailPeminjaman()->create([
                'alat_id' => $alat->id,
                'jumlah' => $validated['jumlah'],
            ]);

            $alat->decrement('stok', $validated['jumlah']);

            ActivityLogger::log(
                'Ajukan Peminjaman',
                'User mengajukan peminjaman alat ' . $alat->nama_alat .
                    ' sebanyak ' . $validated['jumlah'] . ' unit.',
                $peminjaman
            );
        });

        return redirect()
            ->route('user.alats.index')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim dan menunggu persetujuan petugas.');
    }
}
