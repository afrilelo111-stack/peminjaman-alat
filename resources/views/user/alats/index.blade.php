@extends('layouts.user')

@section('title', 'Katalog Alat')
@section('header', 'Katalog & Daftar Stok Alat')

@section('content')

<div class="space-y-5 sm:space-y-6">

    {{-- BARIS TAB KATEGORI UTAMA (GAYA E-COMMERCE TAB MENU) --}}
    @if(isset($kategoris) && $kategoris->isNotEmpty())
        <div class="bg-white rounded-2xl border-2 border-[#1A1D2B] p-2.5 sm:p-3 hard-shadow">
            <div class="flex items-center gap-2.5 overflow-x-auto pb-1 scrollbar-none">
                <a href="{{ route('user.alats.index') }}" 
                   class="tactile-btn whitespace-nowrap rounded-xl px-3.5 py-1.5 sm:px-4 sm:py-2 font-mono-tech text-[11.5px] sm:text-[12.5px] font-bold transition {{ request('kategori_id') == '' ? 'bg-[#1A1D2B] text-white hard-shadow-sm' : 'bg-transparent text-[#1A1D2B] hover:bg-[#F2E7C9]/60' }}">
                    ✨ Semua Produk
                </a>
                @foreach ($kategoris as $kat)
                    <a href="{{ route('user.alats.index', ['kategori_id' => $kat->id, 'search' => request('search')]) }}" 
                       class="tactile-btn whitespace-nowrap rounded-xl px-3.5 py-1.5 sm:px-4 sm:py-2 font-mono-tech text-[11.5px] sm:text-[12.5px] font-bold transition {{ request('kategori_id') == $kat->id ? 'bg-[#237DE1] text-white hard-shadow-sm' : 'bg-transparent text-[#1A1D2B] hover:bg-[#F2E7C9]/60' }}">
                        📁 {{ $kat->nama_kategori }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- INFORMASI JUMLAH DATA & STATUS FILTER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b-2 border-[#1A1D2B] pb-2.5 px-1">
        <p class="font-mono-tech text-[11.5px] sm:text-[12px] font-bold text-[#5C6475]">
            Menampilkan <span class="text-[#1A1D2B] font-extrabold">{{ $alats->count() }}</span> dari <span class="text-[#1A1D2B] font-extrabold">{{ $alats->total() }}</span> inventaris alat
        </p>
        @if(request('search') || request('kategori_id'))
            <a href="{{ route('user.alats.index') }}" class="font-mono-tech text-[11px] font-bold text-[#EC5928] hover:underline w-fit">
                [Reset Filter Aktif ✕]
            </a>
        @endif
    </div>

    {{-- GRID KATALOG PRODUK E-COMMERCE (2 Kolom Rapat di HP, 3-6 Kolom di Tablet/Desktop) --}}
    @if ($alats->isEmpty())
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-10 sm:p-12 text-center hard-shadow">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] font-mono-tech font-bold text-[#1A1D2B] hard-shadow-sm mb-3">
                i
            </div>
            <p class="text-sm font-extrabold uppercase text-[#1A1D2B]">Alat Tidak Ditemukan</p>
            <p class="text-[12px] sm:text-[12.5px] font-medium text-[#5C6475] mt-1">
                Pencarian atau kategori yang Anda pilih tidak cocok dengan data alat apa pun di sistem.
            </p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2.5 sm:gap-4">

            @foreach ($alats as $alat)
                <div class="rounded-xl border-2 border-[#1A1D2B] bg-white hard-shadow overflow-hidden flex flex-col justify-between transition hover:-translate-y-1 group">

                    <div>
                        {{-- Gambar Produk Kotak (Square) --}}
                        <div class="aspect-square border-b-2 border-[#1A1D2B] bg-[#F2E7C9]/30 flex items-center justify-center overflow-hidden relative">
                            @if ($alat->gambar)
                                <img src="{{ asset('storage/' . $alat->gambar) }}"
                                     alt="{{ $alat->nama_alat }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <span class="font-mono-tech text-[10px] sm:text-[10.5px] font-bold text-[#5C6475] italic">Tanpa Gambar</span>
                            @endif

                            {{-- Badge Kategori --}}
                            <div class="absolute top-1.5 left-1.5">
                                <span class="rounded border border-[#1A1D2B] bg-white/95 px-1.5 py-0.5 font-mono-tech text-[8px] sm:text-[8.5px] font-extrabold uppercase text-[#1A1D2B] hard-shadow-sm">
                                    {{ $alat->kategori->nama_kategori ?? 'Umum' }}
                                </span>
                            </div>
                        </div>

                        {{-- Informasi Produk --}}
                        <div class="p-2 sm:p-3 space-y-1">
                            <h3 class="font-extrabold text-[12px] sm:text-[13.5px] text-[#1A1D2B] leading-tight line-clamp-2" title="{{ $alat->nama_alat }}">
                                {{ $alat->nama_alat }}
                            </h3>

                            <p class="font-mono-tech text-[9.5px] sm:text-[10px] font-bold text-[#5C6475]">
                                Kode: {{ $alat->kode_alat }}
                            </p>
                        </div>
                    </div>

                    {{-- Bagian Bawah: Stok & Tombol Detail --}}
                    <div class="p-2 sm:p-3 pt-0 space-y-2">
                        <div class="flex items-center justify-between border-t-2 border-dashed border-[#1A1D2B]/20 pt-1.5 sm:pt-2">
                            <div>
                                <span class="font-mono-tech text-[8.5px] sm:text-[9px] uppercase font-bold text-[#5C6475] block">Stok Ready</span>
                                <span class="font-mono-tech text-[11px] sm:text-[11.5px] font-extrabold text-[#1A1D2B]">
                                    {{ $alat->stok }} Unit
                                </span>
                            </div>

                            @if ($alat->stok > 0 && $alat->kondisi === 'Baik')
                                <span class="rounded px-1 py-0.5 border border-[#1A1D2B] bg-emerald-200 font-mono-tech text-[8px] sm:text-[8.5px] font-extrabold uppercase text-emerald-950">
                                    Tersedia
                                </span>
                            @else
                                <span class="rounded px-1 py-0.5 border border-[#1A1D2B] bg-rose-200 font-mono-tech text-[8px] sm:text-[8.5px] font-extrabold uppercase text-rose-950">
                                    Habis
                                </span>
                            @endif
                        </div>

                        {{-- Tombol Lihat Detail --}}
                        <a href="{{ route('user.alats.show', $alat->id) }}"
                           class="tactile-btn block w-full text-center rounded-lg border-2 border-[#1A1D2B] bg-[#237DE1] py-1.5 sm:py-2 font-mono-tech text-[10.5px] sm:text-[11px] font-bold text-white hard-shadow-sm hover:bg-[#1d69bf]">
                            Lihat Detail
                        </a>
                    </div>

                </div>
            @endforeach

        </div>

        {{-- PAGINATION --}}
        <div class="mt-6 sm:mt-8">
            {{ $alats->appends(request()->only(['search', 'kategori_id']))->links() }}
        </div>
    @endif

</div>

@endsection