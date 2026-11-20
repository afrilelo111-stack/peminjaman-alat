@extends('layouts.user')

@section('title', 'Ajukan Peminjaman')
@section('header', 'Formulir Pengajuan Peminjaman')

@section('content')

<div class="space-y-6 max-w-5xl mx-auto">

    {{-- TOMBOL KEMBALI --}}
    <div>
        <a href="{{ route('user.alats.show', $alat->id) }}"
            class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2 font-mono-tech text-xs font-bold text-[#1A1D2B] hard-shadow-sm hover:bg-[#F2E7C9] transition">
            <span>&larr;</span>
            <span>KEMBALI KE DETAIL ALAT</span>
        </a>
    </div>

    <form action="{{ route('user.peminjamans.store', $alat->id) }}"
        method="POST"
        class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        @csrf

        {{-- KOLOM KIRI: DETAIL INPUT & JADWAL (Span 7) --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- ALERT ERROR VALIDASI --}}
            @if ($errors->any())
                <div class="rounded-2xl border-2 border-[#1A1D2B] bg-rose-100 p-5 hard-shadow text-[#1A1D2B]">
                    <div class="flex items-center gap-2.5 mb-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-rose-300 font-mono-tech font-bold text-xs">
                            !
                        </div>
                        <p class="font-mono-tech text-xs font-extrabold uppercase tracking-tight text-rose-950">
                            Pengajuan belum dapat diproses:
                        </p>
                    </div>
                    <ul class="list-disc list-inside font-mono-tech text-[11.5px] font-bold text-rose-900 space-y-1 pl-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- KARTU 1: PERIODE WAKTU PEMINJAMAN --}}
            <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-6 hard-shadow space-y-5">
                <div class="border-b-2 border-[#1A1D2B]/15 pb-3">
                    <span class="font-mono-tech text-xs font-extrabold uppercase text-[#237DE1]">
                        // 01. ATUR JADWAL
                    </span>
                    <h3 class="text-base sm:text-lg font-extrabold text-[#1A1D2B] uppercase tracking-tight">
                        Periode Peminjaman Alat
                    </h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-mono-tech text-[11.5px] font-bold text-[#1A1D2B] uppercase mb-2">
                            Tanggal Mulai Pinjam <span class="text-rose-600">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal_pinjam"
                            min="{{ now()->toDateString() }}"
                            value="{{ old('tanggal_pinjam') }}"
                            required
                            class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] px-3.5 py-2.5 font-mono-tech text-xs font-bold text-[#1A1D2B] focus:outline-none hard-shadow-sm cursor-pointer"
                        >
                    </div>

                    <div>
                        <label class="block font-mono-tech text-[11.5px] font-bold text-[#1A1D2B] uppercase mb-2">
                            Rencana Tanggal Kembali <span class="text-rose-600">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal_rencana_kembali"
                            min="{{ now()->toDateString() }}"
                            value="{{ old('tanggal_rencana_kembali') }}"
                            required
                            class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] px-3.5 py-2.5 font-mono-tech text-xs font-bold text-[#1A1D2B] focus:outline-none hard-shadow-sm cursor-pointer"
                        >
                    </div>
                </div>

                <div>
                    <label class="block font-mono-tech text-[11.5px] font-bold text-[#1A1D2B] uppercase mb-2">
                        Keterangan / Keperluan <span class="text-[#5C6475] font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        name="keterangan"
                        rows="3"
                        placeholder="Tuliskan tujuan penggunaan alat (misal: Praktikum Laboratorium / Proyek)..."
                        class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] p-3.5 font-mono-tech text-xs font-medium text-[#1A1D2B] placeholder:text-gray-400 focus:outline-none hard-shadow-sm"
                    >{{ old('keterangan') }}</textarea>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN: RINGKASAN PRODUK & TOMBOL SUBMIT STICKY (Span 5) --}}
        <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-24">

            <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-6 hard-shadow space-y-5">
                <div class="border-b-2 border-[#1A1D2B]/15 pb-3">
                    <span class="font-mono-tech text-xs font-extrabold uppercase text-[#237DE1]">
                        // 02. RINGKASAN ITEM
                    </span>
                    <h3 class="text-base sm:text-lg font-extrabold text-[#1A1D2B] uppercase tracking-tight">
                        Alat Dipinjam
                    </h3>
                </div>

                {{-- Preview Kartu Produk Kecil --}}
                <div class="flex gap-4 items-center bg-[#F8F5EE] p-3 rounded-xl border-2 border-[#1A1D2B]">
                    <div class="w-20 h-20 rounded-lg border-2 border-[#1A1D2B] bg-white flex items-center justify-center overflow-hidden shrink-0">
                        @if ($alat->gambar)
                            <img src="{{ asset('storage/' . $alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover">
                        @else
                            <span class="font-mono-tech text-[9px] font-bold text-[#5C6475]">NO IMG</span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="inline-block rounded px-1.5 py-0.5 border border-[#1A1D2B] bg-[#F2E7C9] font-mono-tech text-[8.5px] font-extrabold uppercase text-[#1A1D2B] mb-1">
                            {{ $alat->kategori->nama_kategori ?? 'Umum' }}
                        </span>
                        <h4 class="font-extrabold text-xs sm:text-sm text-[#1A1D2B] truncate">{{ $alat->nama_alat }}</h4>
                        <p class="font-mono-tech text-[10px] font-bold text-[#5C6475]">Kode: {{ $alat->kode_alat }}</p>
                    </div>
                </div>

                {{-- Input Jumlah Unit --}}
                <div class="space-y-2 pt-1 border-t-2 border-dashed border-[#1A1D2B]/20">
                    <div class="flex items-center justify-between">
                        <label class="font-mono-tech text-xs font-bold text-[#1A1D2B] uppercase">
                            Jumlah Unit:
                        </label>
                        <span class="font-mono-tech text-[10px] font-bold text-[#5C6475]">Stok: {{ $alat->stok }} Unit</span>
                    </div>
                    <input
                        type="number"
                        name="jumlah"
                        min="1"
                        max="{{ $alat->stok }}"
                        value="{{ old('jumlah', 1) }}"
                        required
                        class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] px-3.5 py-2.5 font-mono-tech text-xs font-extrabold text-[#1A1D2B] focus:outline-none hard-shadow-sm text-center"
                    >
                </div>

                {{-- Tombol Aksi Submit --}}
                <div class="pt-3 space-y-3 border-t-2 border-[#1A1D2B]/15">
                    <button
                        type="submit"
                        @disabled($alat->stok < 1 || $alat->kondisi !== 'Baik')
                        class="tactile-btn w-full text-center rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] py-3.5 px-6 font-mono-tech text-xs font-bold text-white hard-shadow hover:bg-[#1d69bf] disabled:opacity-50 disabled:cursor-not-allowed">
                        KIRIM PENGAJUAN PEMINJAMAN &rarr;
                    </button>

                    <a href="{{ route('user.alats.show', $alat->id) }}"
                        class="tactile-btn block w-full text-center rounded-xl border-2 border-[#1A1D2B] bg-white py-2.5 px-4 font-mono-tech text-xs font-bold text-[#1A1D2B] hover:bg-[#F2E7C9] transition">
                        BATALKAN
                    </a>
                </div>

            </div>

        </div>

    </form>

</div>

@endsection