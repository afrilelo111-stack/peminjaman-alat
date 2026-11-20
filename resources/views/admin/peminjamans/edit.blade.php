@extends('layouts.admin')

@section('content')

{{-- ============ PAGE HEADER ============ --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        {{-- Breadcrumb Pill --}}
        <div class="mb-2 inline-flex items-center gap-1.5 rounded-md border-2 border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-0.5 font-mono text-[11px] font-bold text-[#1A1D2B]">
            <a href="{{ route('admin.peminjamans.index') }}" class="hover:text-[#237DE1] underline decoration-1 underline-offset-2">Peminjaman</a>
            <span>/</span>
            <span class="text-[#EC5928]">edit</span>
        </div>

        <h1 class="text-[22px] sm:text-[26px] font-extrabold tracking-tight text-[#1A1D2B] leading-tight uppercase">
            Edit Transaksi Peminjaman
        </h1>
        <p class="mt-1 font-mono text-[12px] font-medium text-[#5C6475]">
            // perbarui rincian tanggal, keterangan, atau daftar alat yang dipinjam
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
                <span class="h-2.5 w-2.5 rounded-full border border-[#1A1D2B] bg-[#237DE1]"></span>
                <p class="font-mono text-[11px] font-bold uppercase tracking-widest text-[#1A1D2B]">
                    Formulir Edit Peminjaman [ID: #{{ $peminjaman->id }}]
                </p>
            </div>
            <span class="font-mono-tech rounded border border-[#1A1D2B] bg-white px-2 py-0.5 text-[10px] font-extrabold text-[#1A1D2B]">
                METHOD: PUT
            </span>
        </div>

        <form action="{{ route('admin.peminjamans.update', $peminjaman) }}" method="POST" class="p-5 sm:p-7">
            @csrf
            @method('PUT')

            <div class="space-y-5">

                {{-- 01. PEMINJAM --}}
                <div>
                    <label for="peminjam_id" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#EC5928] px-1.5 py-0.5 text-[9px] text-white">01</span>
                        Nama Peminjam
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
                        @foreach ($peminjams as $peminjam)
                            <option value="{{ $peminjam->id }}" @selected(old('peminjam_id', $peminjaman->peminjam_id) == $peminjam->id)>
                                {{ $peminjam->name }} ({{ $peminjam->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 02 & 03. TANGGAL PINJAM & KEMBALI --}}
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
                            value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam?->format('Y-m-d')) }}"
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
                            value="{{ old('tanggal_rencana_kembali', $peminjaman->tanggal_rencana_kembali?->format('Y-m-d')) }}"
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
                        Keterangan Keperluan
                    </label>

                    <textarea
                        id="keterangan"
                        name="keterangan"
                        rows="3"
                        placeholder="Keterangan peminjaman..."
                        class="w-full rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5
                               font-mono text-[13.5px] font-medium text-[#1A1D2B] placeholder:text-[#5C6475]
                               focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow"
                    >{{ old('keterangan', $peminjaman->keterangan) }}</textarea>
                </div>

            </div>

            <hr class="my-6 border-2 border-dashed border-[#1A1D2B]/30">

            {{-- DAFTAR ALAT (DYNAMIC BUILDER) --}}
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="font-mono text-[13px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        // Rincian Alat Dipinjam
                    </h3>
                    <p class="font-mono text-[11px] text-[#5C6475]">
                        Ubah daftar alat atau jumlah unit sesuai kebutuhan transaksi.
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

                <button type="submit"
                    class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-5 py-2.5 font-mono text-[12px] font-extrabold uppercase tracking-wider text-white shadow-[3px_3px_0px_#1A1D2B] hover:bg-[#1d69bf]">
                    <span>Simpan Perubahan</span>
                    <span>→</span>
                </button>
            </div>
        </form>
    </div>

    {{-- ===== SIDE PANEL ===== --}}
    <aside class="space-y-4">
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-[#F2E7C9] shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">
            <div class="border-b-2 border-[#1A1D2B] bg-[#1A1D2B] px-4 py-2.5 text-white">
                <p class="font-mono text-[11px] font-extrabold uppercase tracking-widest">
                    // Status Transaksi
                </p>
            </div>
            <div class="space-y-3 p-4 font-mono text-[11px] text-[#1A1D2B]">
                <div>
                    <span class="font-bold opacity-60">Status Saat Ini:</span>
                    <p class="font-extrabold uppercase text-[#EC5928]">{{ $peminjaman->status }}</p>
                </div>
                <div>
                    <span class="font-bold opacity-60">Dibuat Pada:</span>
                    <p class="font-semibold">{{ $peminjaman->created_at->translatedFormat('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </aside>
</div>

{{-- ============ PHP DATA PREPARATION ============ --}}
@php
    $alatData = $alats->map(fn ($alat) => [
        'id' => $alat->id,
        'nama' => $alat->nama_alat,
        'kode' => $alat->kode_alat,
        'stok' => $alat->stok,
    ])->values()->all();

    $detailAwal = old('details');
    if ($detailAwal === null) {
        $detailAwal = $peminjaman->detailPeminjaman->map(fn ($detail) => [
            'alat_id' => $detail->alat_id,
            'jumlah' => $detail->jumlah,
        ])->values()->all();
    }
@endphp

<div id="data-alat" data-value="{{ json_encode($alatData) }}" hidden></div>
<div id="data-detail-awal" data-value="{{ json_encode($detailAwal) }}" hidden></div>

{{-- ============ BUILDER SCRIPT ============ --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alatData = JSON.parse(document.getElementById('data-alat').dataset.value);
        const detailAwal = JSON.parse(document.getElementById('data-detail-awal').dataset.value);

        const daftarAlat = document.getElementById('daftar-alat');
        const tombolTambah = document.getElementById('tambah-alat');

        let nomor = 0;

        function buatBarisAlat(data = {}) {
            const baris = document.createElement('div');
            baris.className = 'grid grid-cols-1 gap-2.5 rounded-xl border-2 border-[#1A1D2B] bg-white p-3.5 sm:grid-cols-12 shadow-[2px_2px_0px_#1A1D2B]';

            const pilihan = document.createElement('select');
            pilihan.name = `details[${nomor}][alat_id]`;
            pilihan.required = true;
            pilihan.className = 'w-full rounded-lg border-2 border-[#1A1D2B] bg-white px-3 py-2 font-mono text-[12.5px] font-bold text-[#1A1D2B] sm:col-span-7 focus:outline-none';

            const opsiAwal = document.createElement('option');
            opsiAwal.value = '';
            opsiAwal.textContent = '-- Pilih Alat --';
            pilihan.appendChild(opsiAwal);

            alatData.forEach(alat => {
                const opsi = document.createElement('option');
                opsi.value = alat.id;
                opsi.textContent = `${alat.nama} (${alat.kode}) — Stok: ${alat.stok}`;
                pilihan.appendChild(opsi);
            });

            pilihan.value = data.alat_id ?? '';

            const jumlah = document.createElement('input');
            jumlah.type = 'number';
            jumlah.name = `details[${nomor}][jumlah]`;
            jumlah.min = 1;
            jumlah.value = data.jumlah ?? 1;
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

        detailAwal.forEach(detail => {
            buatBarisAlat(detail);
        });

        if (detailAwal.length === 0) {
            buatBarisAlat();
        }

        tombolTambah.addEventListener('click', () => {
            buatBarisAlat();
        });
    });
</script>

@endsection