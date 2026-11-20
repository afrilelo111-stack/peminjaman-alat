@extends('layouts.admin')

@section('content')

{{-- ============ PAGE HEADER ============ --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        {{-- Breadcrumb Pill --}}
        <div class="mb-2 inline-flex items-center gap-1.5 rounded-md border-2 border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-0.5 font-mono text-[11px] font-bold text-[#1A1D2B]">
            <a href="{{ route('admin.peminjamans.index') }}" class="hover:text-[#237DE1] underline decoration-1 underline-offset-2">Peminjaman</a>
            <span>/</span>
            <span class="text-[#EC5928]">create</span>
        </div>

        <h1 class="text-[22px] sm:text-[26px] font-extrabold tracking-tight text-[#1A1D2B] leading-tight uppercase">
            Tambah Transaksi Peminjaman
        </h1>
        <p class="mt-1 font-mono text-[12px] font-medium text-[#5C6475]">
            // catat transaksi sirkulasi peminjaman alat baru ke dalam sistem
        </p>
    </div>

    <a href="{{ route('admin.peminjamans.index') }}"
       class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2 font-mono text-[12px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9]">
        <span>← Kembali</span>
    </a>
</div>

{{-- ============ ERROR GLOBAL ============ --}}
@if($errors->any())
    <div class="mb-6 flex items-start gap-3 rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] px-4 py-3.5 text-white shadow-[4px_4px_0px_#1A1D2B]">
        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-white text-[#EC5928]">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <div>
            <p class="font-extrabold tracking-wider text-[13px] uppercase">Terjadi {{ $errors->count() }} kesalahan input:</p>
            <ul class="mt-1 list-inside list-disc font-mono text-[11px] font-bold opacity-90">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- ============ MAIN GRID ============ --}}
<div class="grid gap-6 lg:grid-cols-[1fr_320px]">

    {{-- ===== FORM CARD ===== --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center justify-between border-b-2 border-[#1A1D2B] bg-[#F2E7C9]/60 px-5 py-3">
            <div class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full border border-[#1A1D2B] bg-[#EC5928]"></span>
                <p class="font-mono text-[11px] font-bold uppercase tracking-widest text-[#1A1D2B]">
                    Formulir Transaksi Peminjaman
                </p>
            </div>
            <span class="font-mono-tech rounded border border-[#1A1D2B] bg-white px-2 py-0.5 text-[10px] font-extrabold text-[#1A1D2B]">
                METHOD: POST
            </span>
        </div>

        <form action="{{ route('admin.peminjamans.store') }}" method="POST" class="p-5 sm:p-7">
            @csrf

            <div class="space-y-5">

                {{-- 01. PEMINJAM --}}
                <div>
                    <label for="peminjam_id" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#EC5928] px-1.5 py-0.5 text-[9px] text-white">01</span>
                        Pilih Akun Peminjam
                        <span class="text-[#EC5928]">*</span>
                    </label>

                    <select
                        id="peminjam_id"
                        name="peminjam_id"
                        required
                        class="w-full rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5
                               font-mono text-[13px] font-bold text-[#1A1D2B]
                               focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow cursor-pointer"
                    >
                        <option value="">-- Pilih Peminjam Terdaftar --</option>
                        @foreach ($peminjams as $peminjam)
                            <option value="{{ $peminjam->id }}" @selected(old('peminjam_id') == $peminjam->id)>
                                {{ $peminjam->name }} ({{ $peminjam->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 02 & 03. TANGGAL PINJAM & RENCANA KEMBALI --}}
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="tanggal_pinjam" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                            <span class="rounded border border-[#1A1D2B] bg-[#237DE1] px-1.5 py-0.5 text-[9px] text-white">02</span>
                            Tanggal Pinjam
                            <span class="text-[#EC5928]">*</span>
                        </label>

                        <input
                            type="date"
                            id="tanggal_pinjam"
                            name="tanggal_pinjam"
                            value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                            required
                            class="w-full rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5
                                   font-mono text-[13px] font-bold text-[#1A1D2B]
                                   focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow"
                        >
                    </div>

                    <div>
                        <label for="tanggal_rencana_kembali" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                            <span class="rounded border border-[#1A1D2B] bg-[#237DE1] px-1.5 py-0.5 text-[9px] text-white">03</span>
                            Rencana Tanggal Kembali
                            <span class="text-[#EC5928]">*</span>
                        </label>

                        <input
                            type="date"
                            id="tanggal_rencana_kembali"
                            name="tanggal_rencana_kembali"
                            value="{{ old('tanggal_rencana_kembali') }}"
                            required
                            class="w-full rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5
                                   font-mono text-[13px] font-bold text-[#1A1D2B]
                                   focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow"
                        >
                    </div>
                </div>

                {{-- 04. KETERANGAN --}}
                <div>
                    <label for="keterangan" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#5C6475] px-1.5 py-0.5 text-[9px] text-white">04</span>
                        Keterangan Keperluan (Opsional)
                    </label>

                    <textarea
                        id="keterangan"
                        name="keterangan"
                        rows="3"
                        placeholder="Contoh: Keperluan praktikum elektronika dasar..."
                        class="w-full rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5
                               font-mono text-[13.5px] font-medium text-[#1A1D2B] placeholder:text-[#5C6475]
                               focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow"
                    >{{ old('keterangan') }}</textarea>
                </div>

            </div>

            <hr class="my-6 border-2 border-dashed border-[#1A1D2B]/30">

            {{-- DAFTAR ALAT (DYNAMIC BUILDER) --}}
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="font-mono text-[13px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        // Rincian Alat yang Dipinjam
                    </h3>
                    <p class="font-mono text-[11px] text-[#5C6475]">
                        Pilih satu atau beberapa jenis alat beserta jumlah unitnya.
                    </p>
                </div>

                <button type="button"
                        id="tambah-alat"
                        class="tactile-btn inline-flex items-center justify-center gap-1.5 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-2 font-mono text-[11.5px] font-bold text-white shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#1d69bf]">
                    <span>+ Tambah Baris Alat</span>
                </button>
            </div>

            <div id="daftar-alat" class="space-y-3"></div>

            {{-- ==== ACTIONS ==== --}}
            <div class="mt-8 flex flex-col-reverse items-stretch gap-3 border-t-2 border-[#1A1D2B] pt-5 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('admin.peminjamans.index') }}"
                   class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5 font-mono text-[12px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9]">
                    ← Batal
                </a>

                <button type="submit" id="submit-btn"
                    class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] px-5 py-2.5 font-mono text-[12px] font-extrabold uppercase tracking-wider text-white shadow-[3px_3px_0px_#1A1D2B] hover:bg-[#d94b1c]">
                    <span>Simpan Transaksi Peminjaman</span>
                    <span>→</span>
                </button>
            </div>
        </form>
    </div>

    {{-- ===== SIDE PANEL (Panduan Sistem) ===== --}}
    <aside class="space-y-4">
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-[#F2E7C9] shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">
            <div class="border-b-2 border-[#1A1D2B] bg-[#1A1D2B] px-4 py-2.5 text-white">
                <p class="font-mono text-[11px] font-extrabold uppercase tracking-widest">
                    // Aturan Transaksi
                </p>
            </div>
            <div class="space-y-3 p-4">
                <div class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded border border-[#1A1D2B] bg-[#237DE1] font-mono text-[10px] font-extrabold text-white">1</span>
                    <p class="font-mono text-[11px] font-medium text-[#1A1D2B] leading-tight">
                        Hanya alat dengan stok <strong class="text-[#EC5928]">tersedia (&gt;0)</strong> yang akan muncul dalam daftar pilihan.
                    </p>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded border border-[#1A1D2B] bg-[#237DE1] font-mono text-[10px] font-extrabold text-white">2</span>
                    <p class="font-mono text-[11px] font-medium text-[#1A1D2B] leading-tight">
                        Jumlah unit pinjaman tidak boleh melebihi sisa stok fisik alat di gudang.
                    </p>
                </div>
            </div>
        </div>
    </aside>
</div>

{{-- ============ JSON DATA & BUILDER SCRIPT ============ --}}
<script id="alat-data" type="application/json">{!! Illuminate\Support\Js::encode($alats->map(fn ($alat) => [
    'id' => $alat->id,
    'nama' => $alat->nama_alat,
    'kode' => $alat->kode_alat,
    'kategori' => $alat->kategori->nama_kategori ?? '-',
    'stok' => $alat->stok,
])->values()) !!}</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alatData = JSON.parse(document.getElementById('alat-data').textContent);
        const daftarAlat = document.getElementById('daftar-alat');
        const tombolTambah = document.getElementById('tambah-alat');
        const submitBtn = document.getElementById('submit-btn');

        let nomor = 0;

        function buatBarisAlat() {
            const baris = document.createElement('div');
            baris.className = 'grid grid-cols-1 gap-2.5 rounded-xl border-2 border-[#1A1D2B] bg-white p-3.5 sm:grid-cols-12 shadow-[2px_2px_0px_#1A1D2B]';

            const pilihan = document.createElement('select');
            pilihan.name = `details[${nomor}][alat_id]`;
            pilihan.required = true;
            pilihan.className = 'w-full rounded-lg border-2 border-[#1A1D2B] bg-white px-3 py-2 font-mono text-[12.5px] font-bold text-[#1A1D2B] sm:col-span-7 focus:outline-none';

            const opsiAwal = document.createElement('option');
            opsiAwal.value = '';
            opsiAwal.textContent = '-- Pilih Jenis Alat --';
            pilihan.appendChild(opsiAwal);

            const alatTersedia = alatData.filter(alat => Number(alat.stok) > 0);

            alatTersedia.forEach(alat => {
                const opsi = document.createElement('option');
                opsi.value = alat.id;
                opsi.textContent = `${alat.nama} (${alat.kode}) — Stok: ${alat.stok}`;
                pilihan.appendChild(opsi);
            });

            const jumlah = document.createElement('input');
            jumlah.type = 'number';
            jumlah.name = `details[${nomor}][jumlah]`;
            jumlah.min = 1;
            jumlah.value = 1;
            jumlah.required = true;
            jumlah.placeholder = 'Jumlah';
            jumlah.className = 'w-full rounded-lg border-2 border-[#1A1D2B] bg-white px-3 py-2 font-mono text-[12.5px] font-bold text-[#1A1D2B] sm:col-span-3 focus:outline-none';

            const tombolHapus = document.createElement('button');
            tombolHapus.type = 'button';
            tombolHapus.textContent = 'Hapus';
            tombolHapus.className = 'rounded-lg border-2 border-[#1A1D2B] bg-[#EC5928] px-3 py-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-white shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#d94b1c] sm:col-span-2 transition';

            tombolHapus.addEventListener('click', () => {
                baris.remove();
            });

            baris.append(pilihan, jumlah, tombolHapus);
            daftarAlat.appendChild(baris);

            nomor++;
        }

        if (alatData.every(alat => Number(alat.stok) <= 0)) {
            const pesan = document.createElement('div');
            pesan.className = 'rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] p-4 font-mono text-[12px] font-bold text-white shadow-[2px_2px_0px_#1A1D2B]';
            pesan.textContent = 'Perhatian: Seluruh stok unit alat saat ini sedang habis. Transaksi peminjaman tidak dapat dibuat.';
            daftarAlat.appendChild(pesan);

            tombolTambah.disabled = true;
            tombolTambah.classList.add('cursor-not-allowed', 'opacity-50');

            submitBtn.disabled = true;
            submitBtn.classList.add('cursor-not-allowed', 'opacity-50');
        } else {
            tombolTambah.addEventListener('click', buatBarisAlat);
            buatBarisAlat(); // Buat 1 baris default awal
        }
    });
</script>

@endsection