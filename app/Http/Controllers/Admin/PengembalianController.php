<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\DetailPeminjaman;
use App\Models\DetailPengembalian;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $pengembalians = Pengembalian::with([
            'peminjaman.peminjam',
            'petugas',
            'detailPengembalian.detailPeminjaman.alat',
        ])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('peminjaman.peminjam', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengembalians.index', compact('pengembalians'));
    }

    public function create()
    {
        $peminjamans = Peminjaman::with([
            'peminjam',
            'detailPeminjaman.alat',
            'pengembalians.detailPengembalian',
        ])
            ->where('status', 'Disetujui')
            ->get()
            ->filter(function ($peminjaman) {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $sudahDikembalikan = $peminjaman->pengembalians
                        ->flatMap->detailPengembalian
                        ->where('detail_peminjaman_id', $detail->id)
                        ->sum('jumlah_dikembalikan');

                    if ($sudahDikembalikan < $detail->jumlah) {
                        return true;
                    }
                }

                return false;
            });

        $petugas = User::where('role', 'petugas')
            ->orderBy('name')
            ->get();

        return view('admin.pengembalians.create', compact(
            'peminjamans',
            'petugas'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjaman_id' => [
                'required',
                'exists:peminjamans,id',
            ],
            'petugas_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'petugas'),
            ],
            'tanggal_pengembalian' => [
                'required',
                'date',
            ],
            'catatan' => [
                'nullable',
                'string',
            ],
            'details' => [
                'required',
                'array',
                'min:1',
            ],
            'details.*.detail_peminjaman_id' => [
                'required',
                'distinct',
                'exists:detail_peminjamans,id',
            ],
            'details.*.jumlah_dikembalikan' => [
                'required',
                'integer',
                'min:1',
            ],
            'details.*.kondisi_alat' => [
                'required',
                Rule::in(['Baik', 'Rusak', 'Perlu Perbaikan']),
            ],
            'details.*.gambar_bukti' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048', // Maksimal 2MB
            ],
        ]);

        DB::transaction(function () use ($validated) {
            $peminjaman = Peminjaman::with('detailPeminjaman')
                ->lockForUpdate()
                ->findOrFail($validated['peminjaman_id']);

            if ($peminjaman->status !== 'Disetujui') {
                throw ValidationException::withMessages([
                    'peminjaman_id' => 'Peminjaman ini tidak dapat diproses untuk pengembalian.',
                ]);
            }

            $detailIds = collect($validated['details'])
                ->pluck('detail_peminjaman_id')
                ->sort()
                ->values();

            $detailPeminjamans = DetailPeminjaman::whereIn('id', $detailIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $alatIds = $detailPeminjamans->pluck('alat_id')
                ->unique()
                ->sort()
                ->values();

            $alats = Alat::whereIn('id', $alatIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($validated['details'] as $index => $detail) {
                $detailPinjam = $detailPeminjamans->get(
                    $detail['detail_peminjaman_id']
                );

                if (!$detailPinjam ||
                    (int) $detailPinjam->peminjaman_id !== (int) $peminjaman->id) {
                    throw ValidationException::withMessages([
                        "details.$index.detail_peminjaman_id" =>
                            'Detail alat tidak sesuai dengan data peminjaman.',
                    ]);
                }

                $sudahDikembalikan = DB::table('detail_pengembalians')
                    ->join(
                        'pengembalians',
                        'detail_pengembalians.pengembalian_id',
                        '=',
                        'pengembalians.id'
                    )
                    ->where('pengembalians.peminjaman_id', $peminjaman->id)
                    ->where(
                        'detail_pengembalians.detail_peminjaman_id',
                        $detailPinjam->id
                    )
                    ->sum('detail_pengembalians.jumlah_dikembalikan');

                $sisa = (int) $detailPinjam->jumlah - (int) $sudahDikembalikan;

                if ((int) $detail['jumlah_dikembalikan'] > $sisa) {
                    throw ValidationException::withMessages([
                        "details.$index.jumlah_dikembalikan" =>
                            "Jumlah pengembalian melebihi sisa pinjaman. Sisa: {$sisa} unit.",
                    ]);
                }
            }

            $pengembalian = Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'petugas_id' => $validated['petugas_id'],
                'tanggal_pengembalian' => $validated['tanggal_pengembalian'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            foreach ($validated['details'] as $detail) {
                $detailPinjam = $detailPeminjamans->get(
                    $detail['detail_peminjaman_id']
                );

                $gambar = $detail['gambar_bukti']->store(
                    'pengembalian',
                    'public'
                );

                $pengembalian->detailPengembalian()->create([
                    'detail_peminjaman_id' => $detailPinjam->id,
                    'jumlah_dikembalikan' => $detail['jumlah_dikembalikan'],
                    'kondisi_alat' => $detail['kondisi_alat'],
                    'gambar_bukti' => $gambar,
                ]);
                // Hanya alat dengan kondisi Baik yang masuk stok tersedia.
                if ($detail['kondisi_alat'] === 'Baik') {
                    Alat::whereKey($detailPinjam->alat_id)
                        ->increment('stok', $detail['jumlah_dikembalikan']);
                }
            }

            // Periksa apakah semua jumlah pinjaman sudah dikembalikan.
            

            $semuaSudahKembali = true;

            foreach ($peminjaman->detailPeminjaman as $detailPinjam) {
                $jumlahKembali = DB::table('detail_pengembalians')
                    ->join(
                        'pengembalians',
                        'detail_pengembalians.pengembalian_id',
                        '=',
                        'pengembalians.id'
                    )
                    ->where('pengembalians.peminjaman_id', $peminjaman->id)
                    ->where(
                        'detail_pengembalians.detail_peminjaman_id',
                        $detailPinjam->id
                    )
                    ->sum('detail_pengembalians.jumlah_dikembalikan');

                if ($jumlahKembali < $detailPinjam->jumlah) {
                    $semuaSudahKembali = false;
                    break;
                }
            }

            if ($semuaSudahKembali) {
                $peminjaman->update([
                    'status' => 'Dikembalikan',
                ]);
            }
        });

        return redirect()
            ->route('admin.pengembalians.index')
            ->with('success', 'Data pengembalian berhasil ditambahkan.');
    }

    public function destroy(Pengembalian $pengembalian)
    {
        // Simpan ID sebelum proses transaksi.
        $pengembalianId = $pengembalian->id;
        $peminjamanId = $pengembalian->peminjaman_id;

        $gambarBukti = DB::transaction(function () use (
            $pengembalianId,
            $peminjamanId
        ) {
            // Kunci data peminjaman agar tidak berbenturan
            // dengan proses pengembalian lainnya.
            $peminjaman = Peminjaman::whereKey($peminjamanId)
                ->lockForUpdate()
                ->firstOrFail();

            // Kunci data pengembalian.
            $pengembalian = Pengembalian::whereKey($pengembalianId)
                ->where('peminjaman_id', $peminjaman->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Ambil detail pengembalian.
            $details = DetailPengembalian::with('detailPeminjaman')
                ->where('pengembalian_id', $pengembalian->id)
                ->lockForUpdate()
                ->get();

            // Ambil ID alat yang kondisinya Baik.
            $alatIds = $details
                ->where('kondisi_alat', 'Baik')
                ->map(fn ($detail) => $detail->detailPeminjaman->alat_id)
                ->unique()
                ->sort()
                ->values();

            // Kunci data alat.
            $alats = Alat::whereIn('id', $alatIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Kumpulkan foto untuk dihapus setelah transaksi berhasil.
            $gambarBukti = $details
                ->pluck('gambar_bukti')
                ->filter()
                ->values()
                ->all();

            // Batalkan penambahan stok dari pengembalian sebelumnya.
            foreach ($details as $detail) {
                if ($detail->kondisi_alat !== 'Baik') {
                    continue;
                }

                $alatId = $detail->detailPeminjaman->alat_id;
                $alat = Alat::whereKey($alatId)
                    ->lockForUpdate()
                    ->first();

                if (!$alat || $alat->stok < $detail->jumlah_dikembalikan) {
                    throw ValidationException::withMessages([
                        'pengembalian' =>
                            'Pengembalian tidak dapat dihapus karena stok alat tidak mencukupi untuk penyesuaian.',
                    ]);
                }

                $alat->decrement(
                    'stok',
                    $detail->jumlah_dikembalikan
                );
            }

            // Hapus data pengembalian.
            // Detail pengembalian ikut terhapus melalui cascadeOnDelete.
            $pengembalian->delete();

            // Hitung ulang status peminjaman.
            $peminjaman->load('detailPeminjaman');

            $semuaDikembalikan = $peminjaman->detailPeminjaman->isNotEmpty()
                && $peminjaman->detailPeminjaman->every(function ($detail) {
                    $totalDikembalikan = DetailPengembalian::where(
                        'detail_peminjaman_id',
                        $detail->id
                    )->sum('jumlah_dikembalikan');

                    return $totalDikembalikan >= $detail->jumlah;
                });

            $peminjaman->status = $semuaDikembalikan
                ? 'Dikembalikan'
                : 'Disetujui';

            $peminjaman->save();

            return $gambarBukti;
        });

        // Hapus foto setelah transaksi database berhasil.
        foreach ($gambarBukti as $gambar) {
            Storage::disk('public')->delete($gambar);
        }

        return redirect()
            ->route('admin.pengembalians.index')
            ->with('success', 'Data pengembalian berhasil dihapus.');
    }
}
