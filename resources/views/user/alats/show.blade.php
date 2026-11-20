@extends('layouts.user')

@section('title', $alat->nama_alat)
@section('header', 'Detail & Spesifikasi Alat')

@section('content')

<div class="space-y-5 max-w-7xl mx-auto">

    {{-- BREADCRUMB ALA TOKOPEDIA --}}
    <nav class="flex items-center gap-2 font-mono-tech text-[11px] font-bold text-[#5C6475] overflow-x-auto whitespace-nowrap pb-1">
        <a href="{{ route('user.alats.index') }}" class="text-[#237DE1] hover:underline">Home</a>
        <span>&gt;</span>
        <a href="{{ route('user.alats.index', ['kategori_id' => $alat->kategori_id]) }}" class="text-[#237DE1] hover:underline">
            {{ $alat->kategori->nama_kategori ?? 'Umum' }}
        </a>
        <span>&gt;</span>
        <span class="text-[#1A1D2B] truncate">{{ $alat->nama_alat }}</span>
    </nav>

    {{-- LAYOUT 3 KOLOM ALA TOKOPEDIA (FOTO | DETAIL & DESKRIPSI | KOTAK AKSI STICKY) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- KOLOM 1: GALERI FOTO UTAMA (Span 4) --}}
        <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-3">
            <div class="aspect-square w-full rounded-2xl border-2 border-[#1A1D2B] bg-white p-3 hard-shadow flex items-center justify-center overflow-hidden relative">
                @if ($alat->gambar)
                    <img src="{{ asset('storage/' . $alat->gambar) }}"
                         alt="{{ $alat->nama_alat }}"
                         class="w-full h-full object-contain rounded-xl">
                @else
                    <div class="text-center p-6">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] font-mono-tech font-bold text-lg text-[#1A1D2B] hard-shadow-sm mb-2">
                            ∅
                        </div>
                        <p class="font-mono-tech text-[11px] font-bold text-[#5C6475] italic">TIDAK ADA GAMBAR</p>
                    </div>
                @endif

                {{-- Badge Status Pojok --}}
                <div class="absolute top-3 left-3">
                    @if ($alat->stok > 0 && $alat->kondisi === 'Baik')
                        <span class="rounded-lg border-2 border-[#1A1D2B] bg-emerald-300 px-2.5 py-1 font-mono-tech text-[9.5px] font-extrabold uppercase text-emerald-950 hard-shadow-sm">
                            ● READY
                        </span>
                    @else
                        <span class="rounded-lg border-2 border-[#1A1D2B] bg-rose-300 px-2.5 py-1 font-mono-tech text-[9.5px] font-extrabold uppercase text-rose-950 hard-shadow-sm">
                            ✕ HABIS
                        </span>
                    @endif
                </div>
            </div>

            {{-- Thumbnail Preview Kecil (Estetika Galeri Tokopedia) --}}
            <div class="flex items-center gap-2">
                <div class="h-16 w-16 rounded-xl border-2 border-[#237DE1] bg-white p-1 hard-shadow-sm overflow-hidden flex items-center justify-center">
                    @if ($alat->gambar)
                        <img src="{{ asset('storage/' . $alat->gambar) }}" alt="Thumbnail" class="w-full h-full object-cover rounded-lg">
                    @else
                        <span class="font-mono-tech text-[10px] font-bold text-[#5C6475]">IMG</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM 2: JUDUL, TAB DETAIL PRODUK, & DESKRIPSI LENGKAP (Span 5) --}}
        <div class="lg:col-span-5 rounded-2xl border-2 border-[#1A1D2B] bg-white p-5 sm:p-6 hard-shadow space-y-5">
            
            {{-- Judul & Info Singkat --}}
            <div class="space-y-2 border-b-2 border-[#1A1D2B]/15 pb-4">
                <h1 class="text-xl sm:text-2xl font-extrabold text-[#1A1D2B] leading-snug">
                    {{ $alat->nama_alat }}
                </h1>

                <div class="flex flex-wrap items-center gap-2 font-mono-tech text-[11px] font-bold text-[#5C6475]">
                    <span>Kode: <strong class="text-[#1A1D2B]">{{ $alat->kode_alat }}</strong></span>
                    <span>•</span>
                    <span>Kondisi: <strong class="text-emerald-700">{{ $alat->kondisi }}</strong></span>
                </div>

                <div class="pt-1">
                    <span class="inline-block rounded-lg border-2 border-[#1A1D2B] bg-[#F2E7C9] px-3 py-1 font-mono-tech text-sm font-extrabold text-[#1A1D2B] hard-shadow-sm">
                        GRATIS PEMINJAMAN // INVENTARIS RESMI
                    </span>
                </div>
            </div>

            {{-- TAB MENU ALA TOKOPEDIA --}}
            <div>
                <div class="flex items-center gap-6 border-b-2 border-[#1A1D2B]/20 mb-4">
                    <span class="border-b-4 border-[#237DE1] pb-2 font-mono-tech text-xs font-extrabold uppercase text-[#237DE1]">
                        Detail Alat
                    </span>
                    <span class="pb-2 font-mono-tech text-xs font-bold uppercase text-[#5C6475]">
                        Ketentuan Pinjam
                    </span>
                </div>

                {{-- List Spesifikasi Key-Value Ala Tokopedia --}}
                <div class="space-y-2.5 text-[13px] mb-5">
                    <div class="flex items-center gap-2">
                        <span class="text-[#5C6475] w-28 shrink-0">Kondisi</span>
                        <span class="font-bold text-[#1A1D2B]">: {{ $alat->kondisi }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[#5C6475] w-28 shrink-0">Kode Alat</span>
                        <span class="font-mono-tech font-bold text-[#1A1D2B]">: {{ $alat->kode_alat }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[#5C6475] w-28 shrink-0">Kategori</span>
                        <a href="{{ route('user.alats.index', ['kategori_id' => $alat->kategori_id]) }}" class="font-bold text-[#237DE1] hover:underline">
                            : {{ $alat->kategori->nama_kategori ?? 'Umum' }}
                        </a>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[#5C6475] w-28 shrink-0">Min. Pinjam</span>
                        <span class="font-bold text-[#1A1D2B]">: 1 Unit</span>
                    </div>
                </div>

                {{-- Deskripsi Alat --}}
                <div class="border-t-2 border-dashed border-[#1A1D2B]/20 pt-4 space-y-2">
                    <h2 class="font-mono-tech text-xs font-extrabold uppercase text-[#1A1D2B]">
                        Deskripsi Alat
                    </h2>
                    @if ($alat->deskripsi)
                        <p class="whitespace-pre-line text-[13.5px] leading-relaxed text-[#1A1D2B]">
                            {{ $alat->deskripsi }}
                        </p>
                    @else
                        <p class="font-mono-tech text-xs italic text-[#5C6475]">
                            Belum ada deskripsi rinci untuk alat ini. Pastikan memeriksa kelengkapan fisik alat bersama petugas saat serah terima.
                        </p>
                    @endif
                </div>
            </div>

        </div>

        {{-- KOLOM 3: KOTAK PANEL PEMINJAMAN STICKY DI KANAN (Span 3) --}}
        <div class="lg:col-span-3 lg:sticky lg:top-24 rounded-2xl border-2 border-[#1A1D2B] bg-white p-5 hard-shadow space-y-4">
            
            <h3 class="font-extrabold text-sm text-[#1A1D2B] uppercase tracking-tight border-b-2 border-[#1A1D2B]/15 pb-3">
                Atur Peminjaman
            </h3>

            {{-- Mini Preview Item Terpilih --}}
            <div class="flex items-center gap-3 bg-[#F8F5EE] p-2.5 rounded-xl border border-[#1A1D2B]">
                <div class="h-11 w-11 rounded-lg border border-[#1A1D2B] bg-white overflow-hidden shrink-0 flex items-center justify-center">
                    @if ($alat->gambar)
                        <img src="{{ asset('storage/' . $alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover">
                    @else
                        <span class="font-mono-tech text-[9px] font-bold text-[#5C6475]">ALAT</span>
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-xs text-[#1A1D2B] truncate">{{ $alat->nama_alat }}</p>
                    <p class="font-mono-tech text-[10px] font-bold text-[#5C6475]">{{ $alat->kode_alat }}</p>
                </div>
            </div>

            {{-- Info Stok Total --}}
            <div class="flex items-center justify-between pt-1">
                <span class="font-mono-tech text-xs font-bold text-[#5C6475]">Stok Total:</span>
                <span class="font-mono-tech text-sm font-extrabold text-[#1A1D2B]">
                    {{ $alat->stok }} Unit
                </span>
            </div>

            {{-- Tombol Aksi Utama --}}
            <div class="space-y-2.5 pt-2">
                @if ($alat->stok > 0 && $alat->kondisi === 'Baik')
                    <a href="{{ route('user.peminjamans.index', $alat->id) }}"
                       class="tactile-btn flex items-center justify-center gap-1.5 w-full rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] py-3 px-4 font-mono-tech text-xs font-bold text-white hard-shadow-sm hover:bg-[#1d69bf]">
                        <span>+ Ajukan Peminjaman</span>
                    </a>
                @else
                    <button disabled
                            class="w-full rounded-xl border-2 border-[#1A1D2B]/40 bg-gray-200 py-3 px-4 font-mono-tech text-xs font-bold text-gray-500 cursor-not-allowed">
                        Stok Tidak Tersedia
                    </button>
                @endif

                <a href="{{ route('user.alats.index') }}"
                   class="tactile-btn block w-full text-center rounded-xl border-2 border-[#1A1D2B] bg-white py-2.5 px-4 font-mono-tech text-xs font-bold text-[#1A1D2B] hover:bg-[#F2E7C9]">
                    &larr; Kembali ke Katalog
                </a>
            </div>

            {{-- Catatan Kaki Kecil --}}
            <div class="border-t-2 border-dashed border-[#1A1D2B]/20 pt-3 flex items-center justify-center gap-4 font-mono-tech text-[10.5px] font-bold text-[#5C6475]">
                <span>✓ Verifikasi Cepat</span>
                <span>•</span>
                <span>✓ Inventaris Resmi</span>
            </div>

        </div>

    </div>

</div>

@endsection