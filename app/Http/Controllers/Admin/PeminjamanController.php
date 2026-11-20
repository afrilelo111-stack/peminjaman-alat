<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogger;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $peminjamans = Peminjaman::with(['peminjam', 'penyetuju', 'detailPeminjaman.alat'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('peminjam', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.peminjamans.index', compact('peminjamans'));
    }

    public function create()
    {
        $peminjams = User::where('role', 'user')
            ->orderBy('name')
            ->get();

        $alats = Alat::with('kategori')
            ->orderBy('nama_alat')
            ->get();

        return view('admin.peminjamans.create', compact('peminjams', 'alats'));
    }

    private function periksaStok(array $details): void
    {
        $alatIds = collect($details)
            ->pluck('alat_id')
            ->unique()
            ->sort()
            ->values();

        $alats = Alat::whereIn('id', $alatIds)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($details as $index => $detail) {
            $alat = $alats->get($detail['alat_id']);

            if (!$alat) {
                throw ValidationException::withMessages([
                    "details.$index.alat_id" => 'Alat tidak ditemukan.',
                ]);
            }

            if ((int) $alat->stok <= 0) {
                throw ValidationException::withMessages([
                    "details.$index.alat_id" =>
                        "Maaf, {$alat->nama_alat} sedang habis dan tidak dapat dipinjam.",
                ]);
            }

            if ((int) $detail['jumlah'] > (int) $alat->stok) {
                throw ValidationException::withMessages([
                    "details.$index.jumlah" =>
                        "Jumlah {$alat->nama_alat} melebihi stok tersedia. Stok saat ini: {$alat->stok}.",
                ]);
            }
        }
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjam_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'user'),
            ],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_rencana_kembali' => [
                'required',
                'date',
                'after_or_equal:tanggal_pinjam',
            ],
            'keterangan' => ['nullable', 'string'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.alat_id' => [
                'required',
                'distinct',
                'exists:alats,id',
            ],
            'details.*.jumlah' => [
                'required',
                'integer',
                'min:1',
                'max:10000',
            ],
        ]);

        $peminjaman = DB::transaction(function () use ($validated) {

            // Periksa dan kunci stok alat
            $this->periksaStok($validated['details']);

            $peminjaman = Peminjaman::create([
                'peminjam_id' => $validated['peminjam_id'],
                'disetujui_oleh' => null,
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_rencana_kembali' => $validated['tanggal_rencana_kembali'],
                'status' => 'Menunggu',
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            foreach ($validated['details'] as $detail) {

                $peminjaman->detailPeminjaman()->create([
                    'alat_id' => $detail['alat_id'],
                    'jumlah' => $detail['jumlah'],
                ]);

                // Kurangi stok
                Alat::whereKey($detail['alat_id'])
                    ->decrement('stok', $detail['jumlah']);
            }

            return $peminjaman;
        });

        // Catat aktivitas setelah transaksi berhasil
        ActivityLogger::log(
            'Tambah Peminjaman',
            'Membuat peminjaman baru dengan ID #' . $peminjaman->id,
            $peminjaman
        );
        
        return redirect()
            ->route('admin.peminjamans.index')
            ->with('success', 'Data peminjaman berhasil ditambahkan dan stok alat otomatis dikurangi.');
    }


    public function show(Peminjaman $peminjaman)
    {
        return redirect()->route('admin.peminjamans.edit', $peminjaman);
    }

    public function edit(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'Menunggu') {
            return redirect()
                ->route('admin.peminjamans.index')
                ->with('error', 'Hanya peminjaman berstatus Menunggu yang dapat diubah.');
        }

        $peminjaman->load('detailPeminjaman.alat');

        $peminjams = User::where('role', 'user')
            ->orderBy('name')
            ->get();

        $alats = Alat::with('kategori')
            ->orderBy('nama_alat')
            ->get();

        return view(
            'admin.peminjamans.edit',
            compact('peminjaman', 'peminjams', 'alats')
        );
    }



    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'peminjam_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'user'),
            ],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_rencana_kembali' => [
                'required',
                'date',
                'after_or_equal:tanggal_pinjam',
            ],
            'keterangan' => ['nullable', 'string'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.alat_id' => [
                'required',
                'distinct',
                'exists:alats,id',
            ],
            'details.*.jumlah' => [
                'required',
                'integer',
                'min:1',
                'max:10000',
            ],
        ]);

        $peminjaman = DB::transaction(function () use ($validated, $peminjaman) {

            $lockedPeminjaman = Peminjaman::whereKey($peminjaman->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedPeminjaman->status !== 'Menunggu') {
                abort(403, 'Peminjaman sudah diproses dan tidak dapat diubah.');
            }

            // Ambil detail lama
            $detailLama = DB::table('detail_peminjamans')
                ->where('peminjaman_id', $lockedPeminjaman->id)
                ->lockForUpdate()
                ->get();

            $jumlahLama = $detailLama
                ->groupBy('alat_id')
                ->map(fn ($items) => $items->sum('jumlah'));

            // Hitung detail baru
            $jumlahBaru = collect($validated['details'])
                ->groupBy('alat_id')
                ->map(fn ($items) => $items->sum('jumlah'));

            // Kunci semua alat yang terlibat
            $alatIds = $detailLama->pluck('alat_id')
                ->merge($jumlahBaru->keys())
                ->unique()
                ->sort()
                ->values();

            foreach ($alatIds as $alatId) {

                $alat = Alat::whereKey($alatId)
                    ->lockForUpdate()
                    ->first();

                if (!$alat) {
                    throw ValidationException::withMessages([
                        'details' => 'Data alat tidak ditemukan.',
                    ]);
                }

                $stokLama = (int) $jumlahLama->get($alatId, 0);
                $stokBaru = (int) $jumlahBaru->get($alatId, 0);

                // Tambahkan kembali stok yang sebelumnya dipesan
                $stokTersedia = (int) $alat->stok + $stokLama;

                if ($stokBaru > $stokTersedia) {
                    throw ValidationException::withMessages([
                        'details' => "Stok {$alat->nama_alat} tidak mencukupi. Stok tersedia: {$stokTersedia}.",
                    ]);
                }

                // Sesuaikan stok
                $alat->stok = $stokTersedia - $stokBaru;
                $alat->save();
            }

            $lockedPeminjaman->update([
                'peminjam_id' => $validated['peminjam_id'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_rencana_kembali' => $validated['tanggal_rencana_kembali'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Ganti detail lama dengan detail baru
            $lockedPeminjaman->detailPeminjaman()->delete();

            foreach ($validated['details'] as $detail) {
                $lockedPeminjaman->detailPeminjaman()->create([
                    'alat_id' => $detail['alat_id'],
                    'jumlah' => $detail['jumlah'],
                ]);
            }

            return $lockedPeminjaman;
        });

        ActivityLogger::log(
            'Ubah Peminjaman',
            'Memperbarui data peminjaman dengan ID #' . $peminjaman->id,
            $peminjaman
        );

        return redirect()
            ->route('admin.peminjamans.index')
            ->with('success', 'Data peminjaman berhasil diperbarui dan stok berhasil disesuaikan.');
    }




    public function destroy(Peminjaman $peminjaman)
    {
        $gambarBukti = DB::transaction(function () use ($peminjaman) {

            $lockedPeminjaman = Peminjaman::whereKey($peminjaman->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Ambil detail peminjaman
            $details = DB::table('detail_peminjamans')
                ->where('peminjaman_id', $lockedPeminjaman->id)
                ->orderBy('alat_id')
                ->lockForUpdate()
                ->get();

            $detailIds = $details->pluck('id');

            // Ambil detail pengembalian
            $detailPengembalians = DB::table('detail_pengembalians')
                ->whereIn('detail_peminjaman_id', $detailIds)
                ->lockForUpdate()
                ->get();

            // Simpan gambar bukti
            $gambarBukti = $detailPengembalians
                ->pluck('gambar_bukti')
                ->filter()
                ->values()
                ->all();

            // Kembalikan stok jika status Menunggu atau Disetujui
            if (in_array($lockedPeminjaman->status, [
                'Menunggu',
                'Disetujui',
            ])) {

                $alatIds = $details->pluck('alat_id')
                    ->unique()
                    ->sort()
                    ->values();

                $alats = Alat::whereIn('id', $alatIds)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($details as $detail) {

                    // Hitung seluruh unit yang sudah dikembalikan
                    $jumlahSudahKembali = $detailPengembalians
                        ->where('detail_peminjaman_id', $detail->id)
                        ->sum('jumlah_dikembalikan');

                    // Hitung unit yang belum dikembalikan
                    $jumlahBelumKembali =
                        (int) $detail->jumlah - (int) $jumlahSudahKembali;

                    if ($jumlahBelumKembali < 0) {
                        throw ValidationException::withMessages([
                            'peminjaman' => 'Jumlah pengembalian melebihi jumlah peminjaman.',
                        ]);
                    }

                    $alat = $alats->get($detail->alat_id);

                    if (!$alat) {
                        throw ValidationException::withMessages([
                            'peminjaman' => 'Data alat tidak ditemukan.',
                        ]);
                    }

                    // Kembalikan stok yang belum dikembalikan
                    if ($jumlahBelumKembali > 0) {
                        DB::table('alats')
                            ->where('id', $alat->id)
                            ->increment('stok', $jumlahBelumKembali);
                    }
                }
            }

            // Catat aktivitas sebelum data dihapus
            ActivityLogger::log(
                'Hapus Peminjaman',
                'Menghapus peminjaman dengan ID #' . $lockedPeminjaman->id,
                $lockedPeminjaman
            );

            // Hapus detail pengembalian
            DB::table('detail_pengembalians')
                ->whereIn('detail_peminjaman_id', $detailIds)
                ->delete();

            // Hapus data pengembalian
            DB::table('pengembalians')
                ->where('peminjaman_id', $lockedPeminjaman->id)
                ->delete();

            // Hapus detail peminjaman
            DB::table('detail_peminjamans')
                ->where('peminjaman_id', $lockedPeminjaman->id)
                ->delete();

            // Hapus peminjaman
            $lockedPeminjaman->delete();

            return $gambarBukti;
        });

        // Hapus gambar dari storage setelah transaksi berhasil
        foreach ($gambarBukti as $gambar) {
            Storage::disk('public')->delete($gambar);
        }

        return redirect()
            ->route('admin.peminjamans.index')
            ->with('success', 'Data peminjaman beserta riwayat pengembaliannya berhasil dihapus.');
    }

}