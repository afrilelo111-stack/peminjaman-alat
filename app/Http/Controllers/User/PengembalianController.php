<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\DetailPengembalian;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\Kategori;

class PengembalianController extends Controller
{
    /**
     * Menampilkan riwayat peminjaman user.
     */

public function index(Request $request)
{
    // Validasi parameter filter
    $filters = $request->validate([
        'search' => ['nullable', 'string', 'max:100'],
        'kategori_id' => ['nullable', 'integer', 'exists:kategoris,id'],
        'tanggal' => ['nullable', 'date'],
        'status' => [
            'nullable',
            'in:semua,Menunggu,Disetujui,Ditolak,Dikembalikan',
        ],
    ]);

    // Query dasar: hanya transaksi milik pengguna yang login
    $query = Peminjaman::with([
        'detailPeminjaman.alat',
        'pengembalians',
    ])->where('peminjam_id', Auth::id());

    // Pencarian berdasarkan nama atau kode alat
    if (!empty($filters['search'])) {
        $search = $filters['search'];

        $query->whereHas('detailPeminjaman.alat', function ($q) use ($search) {
            $q->where('nama_alat', 'like', "%{$search}%")
                ->orWhere('kode_alat', 'like', "%{$search}%");
        });
    }

    // Filter berdasarkan kategori alat
    if (!empty($filters['kategori_id'])) {
        $kategoriId = $filters['kategori_id'];

        $query->whereHas('detailPeminjaman.alat', function ($q) use ($kategoriId) {
            $q->where('kategori_id', $kategoriId);
        });
    }

    // Filter berdasarkan tanggal peminjaman
    if (!empty($filters['tanggal'])) {
        $query->whereDate('tanggal_pinjam', $filters['tanggal']);
    }

    // Filter berdasarkan status
    if (!empty($filters['status']) && $filters['status'] !== 'semua') {
        $query->where('status', $filters['status']);
    }

    // Ambil data dengan pagination
    $peminjamans = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    // Periksa keterlambatan setiap peminjaman
    $peminjamans->getCollection()->transform(function ($peminjaman) {
        $peminjaman->hari_terlambat = 0;

        if ($peminjaman->status === 'Disetujui') {
            $tanggalKembali = Carbon::parse(
                $peminjaman->tanggal_rencana_kembali
            )->startOfDay();

            if ($tanggalKembali->lt(today())) {
                $peminjaman->hari_terlambat = (int) $tanggalKembali
                    ->diffInDays(today());
            }
        }

        return $peminjaman;
    });

    // Data kategori untuk dropdown filter
    $kategoris = Kategori::orderBy('nama_kategori')->get();

    return view(
        'user.riwayat-peminjamans.index',
        compact('peminjamans', 'kategoris')
    );
}

    /**
     * Menampilkan detail peminjaman.
     */
    public function show(Peminjaman $peminjaman)
    {
        $this->authorizePeminjaman($peminjaman);

        $peminjaman->load('detailPeminjaman.alat');

        $sisaPerDetail = [];

        foreach ($peminjaman->detailPeminjaman as $detail) {
            $sudahDikembalikan = DetailPengembalian::where(
                'detail_peminjaman_id',
                $detail->id
            )
                ->whereHas('pengembalian', function ($query) {
                    $query->whereIn('status', ['Menunggu', 'Disetujui']);
                })
                ->sum('jumlah_dikembalikan');

            $sisaPerDetail[$detail->id] = max(
                0,
                $detail->jumlah - $sudahDikembalikan
            );
        }

        return view(
            'user.riwayat-peminjamans.show',
            compact('peminjaman', 'sisaPerDetail')
        );
    }

    /**
     * Menampilkan formulir pengembalian.
     */
    public function create(Peminjaman $peminjaman)
    {
        $this->authorizePeminjaman($peminjaman);

        if ($peminjaman->status !== 'Disetujui') {
            return redirect()
                ->route('user.riwayat-peminjamans.show', $peminjaman->id)
                ->with('error', 'Peminjaman ini belum dapat dikembalikan.');
        }

        $peminjaman->load('detailPeminjaman.alat');

        $sisaPerDetail = [];

        foreach ($peminjaman->detailPeminjaman as $detail) {
            $sudahDikembalikan = DetailPengembalian::where(
                'detail_peminjaman_id',
                $detail->id
            )
                ->whereHas('pengembalian', function ($query) {
                    $query->whereIn('status', ['Menunggu', 'Disetujui']);
                })
                ->sum('jumlah_dikembalikan');

            $sisaPerDetail[$detail->id] = max(
                0,
                $detail->jumlah - $sudahDikembalikan
            );
        }

        if (array_sum($sisaPerDetail) < 1) {
            return redirect()
                ->route('user.riwayat-peminjamans.show', $peminjaman->id)
                ->with('error', 'Semua alat sudah dikembalikan.');
        }

        return view(
            'user.riwayat-peminjamans.create',
            compact('peminjaman', 'sisaPerDetail')
        );
    }

    /**
     * Menyimpan pengembalian alat.
     */
    public function store(Request $request, Peminjaman $peminjaman)
    {
        $this->authorizePeminjaman($peminjaman);

        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:1000'],
            'details' => ['required', 'array'],
            'details.*.jumlah_dikembalikan' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'details.*.kondisi_alat' => [
                'nullable',
                'in:Baik,Rusak,Perlu Perbaikan',
            ],
            'details.*.gambar_bukti' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $detailsInput = $validated['details'];

        $adaDetailDipilih = false;

        foreach ($detailsInput as $detailInput) {
            if (!empty($detailInput['jumlah_dikembalikan'])) {
                $adaDetailDipilih = true;

                if (
                    empty($detailInput['kondisi_alat']) ||
                    empty($detailInput['gambar_bukti'])
                ) {
                    throw ValidationException::withMessages([
                        'details' => 'Kondisi alat dan foto bukti wajib diisi untuk setiap alat yang dikembalikan.',
                    ]);
                }
            }
        }

        if (!$adaDetailDipilih) {
            throw ValidationException::withMessages([
                'details' => 'Pilih minimal satu alat yang ingin dikembalikan.',
            ]);
        }

        DB::transaction(function () use (
            $peminjaman,
            $detailsInput,
            $validated
        ) {
            $peminjaman = Peminjaman::whereKey($peminjaman->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $peminjaman->peminjam_id !== Auth::id() ||
                $peminjaman->status !== 'Disetujui'
            ) {
                throw ValidationException::withMessages([
                    'peminjaman' => 'Peminjaman tidak dapat diproses.',
                ]);
            }

            $detailPeminjamans = $peminjaman->detailPeminjaman()
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $detailMap = $detailPeminjamans->keyBy('id');

            $pengembalian = $peminjaman->pengembalians()->create([
                'user_id' => Auth::id(),
                'petugas_id' => null,
                'tanggal_pengembalian' => now()->toDateString(),
                'catatan' => $validated['catatan'] ?? null,
                'status' => 'Menunggu',
            ]);

            foreach ($detailsInput as $detailId => $detailInput) {
                $jumlah = $detailInput['jumlah_dikembalikan'] ?? null;

                if (!$jumlah) {
                    continue;
                }

                $detailPeminjaman = $detailMap->get($detailId);

                if (!$detailPeminjaman) {
                    throw ValidationException::withMessages([
                        'details' => 'Data alat yang dikembalikan tidak valid.',
                    ]);
                }

                $sudahDikembalikan = DetailPengembalian::where(
                    'detail_peminjaman_id',
                    $detailPeminjaman->id
                )
                    ->whereHas('pengembalian', function ($query) {
                        $query->whereIn('status', ['Menunggu', 'Disetujui']);
                    })
                    ->sum('jumlah_dikembalikan');

                $sisa = $detailPeminjaman->jumlah - $sudahDikembalikan;

                if ($jumlah > $sisa) {
                    throw ValidationException::withMessages([
                        "details.{$detailId}.jumlah_dikembalikan" =>
                            "Jumlah pengembalian melebihi sisa alat yang belum dikembalikan ({$sisa}).",
                    ]);
                }

                $file = $detailInput['gambar_bukti'];
                $path = $file->store(
                    'pengembalian/bukti',
                    'public'
                );

                $pengembalian->detailPengembalian()->create([
                    'detail_peminjaman_id' => $detailPeminjaman->id,
                    'jumlah_dikembalikan' => $jumlah,
                    'kondisi_alat' => $detailInput['kondisi_alat'],
                    'gambar_bukti' => $path,
                ]);


            }

            ActivityLogger::log(
                'Pengembalian Alat',
                'User mengembalikan alat pada peminjaman #' .
                    $peminjaman->id . '.',
                $peminjaman
            );
        });

        return redirect()
            ->route('user.riwayat-peminjamans.index')
            ->with('success', 'Pengembalian alat berhasil dicatat.');
    }

    /**
     * Memastikan peminjaman milik user yang sedang login.
     */
    private function authorizePeminjaman(Peminjaman $peminjaman): void
    {
        abort_if(
            $peminjaman->peminjam_id !== Auth::id(),
            403,
            'Anda tidak memiliki akses ke peminjaman ini.'
        );
    }
}
