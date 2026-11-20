@php
    if (auth()->check()) {
        // Jika sudah login, arahkan otomatis ke dashboard sesuai role/hak akses
        redirect()->route('user.alats.index')->send();
        exit;
    }
@endphp

@extends('layouts.app')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto py-6">

    {{-- HEADER BANNER SELAMAT DATANG --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between bg-white rounded-2xl border-2 border-[#1A1D2B] p-6 sm:p-8 shadow-[4px_4px_0px_#1A1D2B]">
        <div>
            <div class="mb-2 inline-flex items-center gap-1.5 rounded-md border-2 border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-0.5 font-mono text-[11px] font-bold text-[#1A1D2B]">
                <span>Katalog Publik</span>
                <span>/</span>
                <span class="text-[#EC5928]">Peminjaman Alat</span>
            </div>

            <h2 class="text-[22px] sm:text-[28px] font-extrabold tracking-tight text-[#1A1D2B] leading-tight uppercase">
                Daftar Stok & Inventaris Alat Lab
            </h2>
            <p class="mt-1 font-mono text-[12px] sm:text-[13px] font-medium text-[#5C6475]">
                // jelajahi ketersediaan peralatan laboratorium. Silakan masuk terlebih dahulu untuk mengajukan peminjaman.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('login') }}" 
               class="inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-5 py-3 font-mono text-[13px] font-bold text-white shadow-[3px_3px_0px_#1A1D2B] hover:bg-[#1d69bf] transition">
                <span>🔑 Masuk untuk Pinjam Alat</span>
            </a>
        </div>
    </div>

    {{-- PANEL UTAMA TABEL KATALOG --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">

        {{-- ==== TOOLBAR (Search + Filter Kategori) ==== --}}
        <div class="border-b-2 border-[#1A1D2B] bg-[#F2E7C9]/40 p-4 sm:p-5">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

                <form action="{{ url('/') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5 w-full lg:max-w-2xl">
                    {{-- Input Pencarian --}}
                    <div class="flex flex-1 items-stretch overflow-hidden rounded-xl border-2 border-[#1A1D2B] bg-white shadow-[2px_2px_0px_#1A1D2B]">
                        <span class="flex items-center justify-center border-r-2 border-[#1A1D2B] bg-[#F2E7C9] px-3.5 text-[#1A1D2B]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama atau kode alat..."
                            class="w-full bg-transparent px-3 py-2 font-mono text-[13px] font-medium text-[#1A1D2B] placeholder:text-[#5C6475] focus:outline-none"
                        >
                    </div>

                    {{-- Filter Dropdown Kategori --}}
                    <div class="flex items-stretch overflow-hidden rounded-xl border-2 border-[#1A1D2B] bg-white shadow-[2px_2px_0px_#1A1D2B]">
                        <select name="kategori_id"
                                class="w-full sm:w-48 bg-transparent px-3 py-2 font-mono text-[12.5px] font-bold text-[#1A1D2B] focus:outline-none cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @if(isset($kategoris))
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" @selected(request('kategori_id') == $kategori->id)>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <button type="submit"
                            class="border-l-2 border-[#1A1D2B] bg-[#237DE1] px-4 font-mono text-[11px] font-bold uppercase tracking-wider text-white transition hover:bg-[#1d69bf]">
                            Filter
                        </button>
                    </div>
                </form>

                <div class="flex items-center justify-between sm:justify-end gap-3">
                    @if(request('search') || request('kategori_id'))
                        <a href="{{ url('/') }}"
                           class="font-mono text-[11px] font-bold uppercase tracking-wider text-[#EC5928] underline decoration-2 underline-offset-2">
                            × Reset filter
                        </a>
                    @endif
                    <span class="font-mono rounded-lg border-2 border-[#1A1D2B] bg-white px-3 py-1.5 text-[11px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B]">
                        TOTAL: {{ isset($alats) ? $alats->total() : 0 }} UNIT
                    </span>
                </div>
            </div>
        </div>

        {{-- ==== TABLE KATALOG ALAT ==== --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">

                <thead>
                    <tr class="border-b-2 border-[#1A1D2B] bg-[#1A1D2B] text-white">
                        <th class="w-[64px] border-r-2 border-[#1A1D2B] px-3 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest text-center">
                            #
                        </th>
                        <th class="w-[90px] border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest text-center">
                            Foto
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                            Nama Alat
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                            Kode
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                            Kategori
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest text-center">
                            Stok Ready
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                            Kondisi
                        </th>
                        <th class="px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest text-right">
                            Aksi Pinjam
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y-2 divide-[#1A1D2B]">
                    @forelse ($alats ?? [] as $alat)
                        @php
                            $rowNo = $alats->firstItem() + $loop->index;
                            $kondisiLower = strtolower($alat->kondisi);
                            $kondisiStyle = match(true) {
                                str_contains($kondisiLower, 'baik') || str_contains($kondisiLower, 'bagus') => 'bg-[#237DE1] text-white',
                                str_contains($kondisiLower, 'rusak') || str_contains($kondisiLower, 'buruk') => 'bg-[#EC5928] text-white',
                                default => 'bg-[#F2E7C9] text-[#1A1D2B]',
                            };
                        @endphp

                        <tr class="transition hover:bg-[#F2E7C9]/30">

                            {{-- NO --}}
                            <td class="border-r-2 border-[#1A1D2B] px-3 py-4 text-center align-middle">
                                <span class="font-mono text-[13px] font-bold text-[#5C6475]">
                                    {{ str_pad($rowNo, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            {{-- GAMBAR --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-3 text-center align-middle">
                                @if ($alat->gambar)
                                    <img
                                        src="{{ asset('storage/' . $alat->gambar) }}"
                                        alt="{{ $alat->nama_alat }}"
                                        class="mx-auto h-14 w-14 rounded-lg border-2 border-[#1A1D2B] object-cover shadow-[2px_2px_0px_#1A1D2B]"
                                    >
                                @else
                                    <div class="mx-auto flex h-14 w-14 flex-col items-center justify-center rounded-lg border-2 border-dashed border-[#1A1D2B]/40 bg-[#F2E7C9]/50 font-mono text-[9px] font-bold text-[#5C6475]">
                                        <span>NO</span>
                                        <span>IMG</span>
                                    </div>
                                @endif
                            </td>

                            {{-- NAMA ALAT --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-middle">
                                <p class="text-[13.5px] font-extrabold text-[#1A1D2B]">
                                    {{ $alat->nama_alat }}
                                </p>
                            </td>

                            {{-- KODE ALAT --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-middle">
                                <span class="font-mono inline-block rounded border border-[#1A1D2B] bg-[#F2E7C9] px-2 py-0.5 text-[11.5px] font-bold text-[#1A1D2B]">
                                    {{ $alat->kode_alat }}
                                </span>
                            </td>

                            {{-- KATEGORI --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-middle">
                                <span class="font-mono text-[12.5px] font-semibold text-[#5C6475]">
                                    {{ $alat->kategori->nama_kategori ?? '-' }}
                                </span>
                            </td>

                            {{-- STOK --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 text-center align-middle">
                                <span class="font-mono inline-flex min-w-[2.25rem] items-center justify-center rounded-md border-2 border-[#1A1D2B] px-2 py-0.5 text-[12px] font-extrabold shadow-[2px_2px_0px_#1A1D2B]
                                             {{ $alat->stok > 0 ? 'bg-white text-[#1A1D2B]' : 'bg-[#EC5928] text-white' }}">
                                    {{ $alat->stok }}
                                </span>
                            </td>

                            {{-- KONDISI --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-middle">
                                <span class="inline-block rounded-md border-2 border-[#1A1D2B] px-2.5 py-0.5 font-mono text-[10px] font-extrabold uppercase tracking-wider shadow-[2px_2px_0px_#1A1D2B] {{ $kondisiStyle }}">
                                    {{ $alat->kondisi }}
                                </span>
                            </td>

                            {{-- AKSI (DIARAHKAN KE LOGIN) --}}
                            <td class="px-4 py-4 align-middle text-right">
                                <a href="{{ route('login') }}"
                                   title="Silakan masuk untuk meminjam alat ini"
                                   class="inline-flex items-center gap-1 rounded-lg border-2 border-[#1A1D2B] bg-[#EC5928] px-4 py-2 font-mono text-[11px] font-bold text-white shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#d94b1c] transition">
                                    🔑 Masuk untuk Pinjam
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-16 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center gap-3">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] font-mono text-xl font-extrabold text-[#EC5928] shadow-[3px_3px_0px_#1A1D2B]">
                                        Ø
                                    </div>
                                    <p class="text-base font-extrabold uppercase text-[#1A1D2B]">
                                        Belum ada data alat
                                    </p>
                                    <p class="font-mono text-[12px] text-[#5C6475]">
                                        @if(request('search') || request('kategori_id'))
                                            Tidak ada peralatan yang cocok dengan filter pencarian Anda.
                                        @else
                                            Inventaris peralatan laboratorium saat ini masih kosong.
                                        @endif
                                    </p>
                                    @if(request('search') || request('kategori_id'))
                                        <a href="{{ url('/') }}"
                                           class="mt-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-2 font-mono text-[11px] font-bold text-white shadow-[2px_2px_0px_#1A1D2B]">
                                            Reset Filter
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ==== PAGINATION ==== --}}
        @if(isset($alats) && $alats->hasPages())
            <div class="border-t-2 border-[#1A1D2B] bg-[#F2E7C9]/40 px-4 py-3.5">
                <div class="flex flex-col items-center justify-between gap-3 sm:flex-row">
                    <p class="font-mono text-[11px] font-bold text-[#5C6475]">
                        Menampilkan {{ $alats->firstItem() }}–{{ $alats->lastItem() }} dari {{ $alats->total() }} total alat
                    </p>
                    <div>
                        {{ $alats->appends(request()->only(['search', 'kategori_id']))->links() }}
                    </div>
                </div>
            </div>
        @endif

    </div>

</div>

@endsection