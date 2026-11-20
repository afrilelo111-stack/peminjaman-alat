<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan daftar peminjaman.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string'],
            'tanggal' => ['nullable', 'date'],
        ]);

        $peminjamans = Peminjaman::with([
            'peminjam',
            'penyetuju',
            'detailPeminjaman.alat',
        ])
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('peminjam', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
                });
            })
            ->when($validated['tanggal'] ?? null, function ($query, $tanggal) {
                $query->whereDate('tanggal_pinjam', $tanggal);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('petugas.peminjamans.index', compact('peminjamans'));
    }

    /**
     * Menampilkan detail peminjaman.
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load([
            'peminjam',
            'penyetuju',
            'detailPeminjaman.alat',
        ]);

        return view('petugas.peminjamans.show', compact('peminjaman'));
    }

    /**
     * Mengubah status peminjaman.
     */
    public function updateStatus(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Disetujui,Ditolak'],
        ]);

        DB::transaction(function () use ($peminjaman, $validated) {
            $lockedPeminjaman = Peminjaman::whereKey($peminjaman->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedPeminjaman->status !== 'Menunggu') {
                throw ValidationException::withMessages([
                    'status' => 'Peminjaman ini sudah diproses.',
                ]);
            }

            if ($validated['status'] === 'Ditolak') {
                foreach ($lockedPeminjaman->detailPeminjaman as $detail) {
                    $detail->alat()->lockForUpdate()->firstOrFail()
                        ->increment('stok', $detail->jumlah);
                }
            }

            $lockedPeminjaman->update([
                'status' => $validated['status'],
                'disetujui_oleh' => Auth::id(),
            ]);

            ActivityLogger::log(
                $validated['status'] === 'Disetujui'
                    ? 'Setujui Peminjaman'
                    : 'Tolak Peminjaman',
                'Mengubah status peminjaman ID #' . $lockedPeminjaman->id
                    . ' menjadi ' . $validated['status'],
                $lockedPeminjaman
            );
        });

        return redirect()
            ->route('petugas.peminjamans.index')
            ->with('success', 'Status peminjaman berhasil diperbarui.');
    }
}
