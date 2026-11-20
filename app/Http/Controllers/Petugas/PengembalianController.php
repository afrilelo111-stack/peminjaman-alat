<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PengembalianController extends Controller
{
    /**
     * Menampilkan daftar pengembalian.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string'],
            'tanggal_selesai' => ['nullable', 'date'],
        ]);

        $peminjamans = Peminjaman::with([
            'peminjam',
            'detailPeminjaman.alat',
            'detailPeminjaman.detailPengembalians.pengembalian',
        ])
            ->whereIn('status', ['Disetujui', 'Dikembalikan'])
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('peminjam', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('id', 'like', "%{$search}%");
                });
            })
            ->when($validated['tanggal_selesai'] ?? null, function ($query, $tanggal) {
                $query->whereHas('pengembalians', function ($pengembalian) use ($tanggal) {
                    $pengembalian->whereDate('tanggal_pengembalian', $tanggal);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('petugas.pengembalians.index', compact('peminjamans'));
    }

    /**
     * Menampilkan detail pengembalian.
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load([
            'peminjam',
            'detailPeminjaman.alat',
            'pengembalians.petugas',
            'pengembalians.detailPengembalian.detailPeminjaman.alat',
        ]);

        return view('petugas.pengembalians.show', compact('peminjaman'));
    }

    /**
     * Menyetujui atau menolak pengembalian.
     */
    public function updateStatus(Request $request, Pengembalian $pengembalian)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Disetujui,Ditolak'],
        ]);

        $peminjamanId = DB::transaction(function () use ($validated, $pengembalian) {

            $pengembalian = Pengembalian::whereKey($pengembalian->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($pengembalian->status !== 'Menunggu') {
                throw ValidationException::withMessages([
                    'status' => 'Pengembalian ini sudah diproses sebelumnya.',
                ]);
            }

            $peminjaman = Peminjaman::whereKey($pengembalian->peminjaman_id)
                ->lockForUpdate()
                ->firstOrFail();

            $details = $pengembalian->detailPengembalian()
                ->with('detailPeminjaman')
                ->get();

            // Stok hanya bertambah jika pengembalian disetujui
            // dan kondisi alat adalah Baik.
            if ($validated['status'] === 'Disetujui') {
                foreach ($details as $detail) {
                    if ($detail->kondisi_alat === 'Baik') {
                        $alat = Alat::whereKey(
                            $detail->detailPeminjaman->alat_id
                        )
                            ->lockForUpdate()
                            ->firstOrFail();

                        $alat->increment(
                            'stok',
                            $detail->jumlah_dikembalikan
                        );
                    }
                }
            }

            $pengembalian->update([
                'status' => $validated['status'],
                'petugas_id' => Auth::id(),
            ]);

            // Periksa apakah seluruh barang sudah dikembalikan
            // dan seluruh jumlahnya sudah disetujui petugas.
            if ($validated['status'] === 'Disetujui') {
                $semuaSudahDikembalikan = true;

                $detailPeminjamans = $peminjaman->detailPeminjaman()
                    ->get();

                foreach ($detailPeminjamans as $detailPeminjaman) {
                    $jumlahDisetujui = $detailPeminjaman
                        ->detailPengembalians()
                        ->whereHas('pengembalian', function ($query) {
                            $query->where('status', 'Disetujui');
                        })
                        ->sum('jumlah_dikembalikan');

                    if ($jumlahDisetujui < $detailPeminjaman->jumlah) {
                        $semuaSudahDikembalikan = false;
                        break;
                    }
                }

                if ($semuaSudahDikembalikan) {
                    $peminjaman->update([
                        'status' => 'Dikembalikan',
                    ]);
                }
            }

            ActivityLogger::log(
                $validated['status'] === 'Disetujui'
                    ? 'Setujui Pengembalian'
                    : 'Tolak Pengembalian',
                'Petugas ' . strtolower($validated['status'])
                    . ' pengembalian #' . $pengembalian->id,
                $pengembalian
            );

            return $peminjaman->id;
        });

        return redirect()
            ->route('petugas.pengembalians.show', $peminjamanId)
            ->with(
                'success',
                $validated['status'] === 'Disetujui'
                    ? 'Pengembalian berhasil disetujui dan stok telah diperbarui.'
                    : 'Pengembalian berhasil ditolak.'
            );
    }
}
