@extends('layouts.petugas')

@section('title', 'Pemantauan Pengembalian')
@section('header', 'Pemantauan Pengembalian Alat')

@section('content')

<div class="space-y-6">

    {{-- KONTROL FILTER & PENCARIAN --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-5 sm:p-6 hard-shadow">
        <form action="{{ route('petugas.pengembalians.index') }}" method="GET" class="space-y-4">
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-12">
                
                {{-- Input Cari Nama Peminjam --}}
                <div class="sm:col-span-5">
                    <label for="search" class="block font-mono-tech text-[11px] font-bold uppercase tracking-widest text-[#5C6475] mb-2">
                        // Nama Peminjam
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-[#5C6475]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input type="text"
                               name="search"
                               id="search"
                               value="{{ request('search') }}"
                               placeholder="Cari nama peminjam..."
                               class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9]/30 pl-10 pr-3.5 py-2.5 text-[13px] font-medium text-[#1A1D2B] placeholder:text-[#5C6475] focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-all">
                    </div>
                </div>

                {{-- Tanggal Pengembalian --}}
                <div class="sm:col-span-4">
                    <label for="tanggal_selesai" class="block font-mono-tech text-[11px] font-bold uppercase tracking-widest text-[#5C6475] mb-2">
                        // Tanggal Pengembalian
                    </label>
                    <input type="date"
                           name="tanggal_selesai"
                           id="tanggal_selesai"
                           value="{{ request('tanggal_selesai') }}"
                           class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9]/30 px-3.5 py-2.5 text-[13px] font-medium text-[#1A1D2B] focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-all">
                </div>

                {{-- Tombol Aksi Filter & Reset --}}
                <div class="sm:col-span-3 flex items-end gap-2">
                    <button type="submit"
                            class="tactile-btn flex-1 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-2.5 text-[13px] font-bold text-white hard-shadow-sm hover:bg-[#1d69bf] text-center">
                        Cari
                    </button>
                    @if(request('search') || request('tanggal_selesai'))
                        <a href="{{ route('petugas.pengembalians.index') }}"
                           class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] px-3.5 py-2.5 text-[13px] font-bold text-white hard-shadow-sm text-center"
                           title="Reset Filter">
                            ✕
                        </a>
                    @else
                        <a href="{{ route('petugas.pengembalians.index') }}"
                           class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-white px-3 py-2.5 text-[13px] font-bold text-[#1A1D2B] hard-shadow-sm text-center hover:bg-[#F2E7C9]">
                            Reset
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- CARD UTAMA TABEL PENGEMBALIAN --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white hard-shadow overflow-hidden">
        
        {{-- HEADER BAR: Info Jumlah Data & Tombol Export Excel --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b-2 border-[#1A1D2B] bg-[#F2E7C9]/60 px-5 py-4 gap-3">
            <div>
                <p class="font-mono-tech text-[11px] font-bold uppercase tracking-widest text-[#5C6475]">
                    Total Data: <span class="text-[#1A1D2B] font-extrabold">{{ $peminjamans->total() }}</span> Peminjaman
                </p>
            </div>

            <a href="{{ route('petugas.laporan.pengembalian', request()->only(['search', 'tanggal_selesai'])) }}"
               class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-3.5 py-2 font-mono-tech text-[11px] font-bold text-[#1A1D2B] hard-shadow-sm hover:bg-[#F2E7C9]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M4 7h16M4 7l1-4h14l1 4M5 7v13h14V7"/>
                </svg>
                <span>Export Laporan Excel</span>
            </a>
        </div>

        {{-- TABEL DATA --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[950px]">
                <thead>
                    <tr class="border-b-2 border-[#1A1D2B] bg-[#1A1D2B] text-white">
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest">Peminjaman</th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest">Peminjam</th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest">Tanggal Pinjam</th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest">Progres Pengembalian</th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest">Status</th>
                        <th class="px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-[#1A1D2B]">
                    @forelse ($peminjamans as $peminjaman)
                        @php
                            $totalDipinjam = $peminjaman->detailPeminjaman->sum('jumlah');

                            $totalDikembalikan = $peminjaman->detailPeminjaman->sum(
                                fn ($detail) => $detail->detailPengembalians->sum('jumlah_dikembalikan')
                            );

                            $sisaPengembalian = $totalDipinjam - $totalDikembalikan;
                            $progressWidth = $totalDipinjam > 0
                                ? min(100, ($totalDikembalikan / $totalDipinjam) * 100)
                                : 0;

                            if ($sisaPengembalian <= 0) {
                                $statusPengembalian = 'Selesai';
                                $badgeStyle = 'bg-emerald-300 text-emerald-950';
                            } elseif ($totalDikembalikan > 0) {
                                $statusPengembalian = 'Sebagian';
                                $badgeStyle = 'bg-amber-300 text-amber-950';
                            } else {
                                $statusPengembalian = 'Belum Dikembalikan';
                                $badgeStyle = 'bg-rose-300 text-rose-950';
                            }
                        @endphp

                        <tr class="transition hover:bg-[#F2E7C9]/30">
                            
                            {{-- ID & Jenis Alat --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4">
                                <p class="font-mono-tech font-extrabold text-[13px] text-[#1A1D2B]">
                                    #{{ $peminjaman->id }}
                                </p>
                                <p class="font-mono-tech text-[10.5px] text-[#5C6475] mt-0.5">
                                    {{ $peminjaman->detailPeminjaman->count() }} Jenis Alat
                                </p>
                            </td>

                            {{-- Peminjam --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-[#237DE1] text-xs font-extrabold text-white">
                                        {{ strtoupper(substr($peminjaman->peminjam->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-extrabold text-[13px] text-[#1A1D2B] truncate">
                                            {{ $peminjaman->peminjam->name ?? '-' }}
                                        </p>
                                        <p class="font-mono-tech text-[10.5px] text-[#5C6475] truncate">
                                            {{ $peminjaman->peminjam->email ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Tanggal Pinjam & Rencana Kembali --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 font-mono-tech text-[12px] text-[#1A1D2B]">
                                <p>{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</p>
                                <p class="text-[10.5px] text-[#EC5928] mt-0.5">
                                    Rencana: {{ $peminjaman->tanggal_rencana_kembali->format('d M Y') }}
                                </p>
                            </td>

                            {{-- Progres Pengembalian Bar --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-mono-tech text-[12px] font-bold text-[#1A1D2B]">
                                        {{ $totalDikembalikan }} / {{ $totalDipinjam }} Unit
                                    </span>
                                    <span class="font-mono-tech text-[10px] text-[#5C6475]">
                                        Sisa: {{ $sisaPengembalian }}
                                    </span>
                                </div>

                                {{-- Progress bar Neo-Brutalist --}}
                                <div class="w-full h-3 rounded-md border-2 border-[#1A1D2B] bg-white overflow-hidden p-0.5">
                                    <svg class="h-full w-full" viewBox="0 0 100 10" preserveAspectRatio="none" aria-hidden="true">
                                        <rect width="{{ $progressWidth }}" height="10" class="fill-[#237DE1]" />
                                    </svg>
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4">
                                <span class="inline-block rounded-md border-2 border-[#1A1D2B] {{ $badgeStyle }} px-2.5 py-1 font-mono-tech text-[10px] font-bold uppercase tracking-wider hard-shadow-sm">
                                    {{ $statusPengembalian }}
                                </span>
                            </td>

                            {{-- Tombol Detail --}}
                            <td class="px-4 py-4 text-center">
                                <a href="{{ route('petugas.pengembalians.show', $peminjaman->id) }}"
                                   class="tactile-btn inline-flex items-center gap-1 rounded-xl border-2 border-[#1A1D2B] bg-white px-3.5 py-1.5 font-mono-tech text-[11px] font-bold text-[#1A1D2B] hard-shadow-sm hover:bg-[#237DE1] hover:text-white transition">
                                    Detail
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-16 text-center bg-white">
                                <div class="mx-auto flex max-w-sm flex-col items-center gap-2">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] font-mono-tech font-bold text-[#1A1D2B] hard-shadow-sm">
                                        i
                                    </div>
                                    <p class="text-sm font-extrabold uppercase text-[#1A1D2B]">Tidak ada data pengembalian</p>
                                    <p class="text-[12.5px] font-medium text-[#5C6475]">
                                        Data peminjaman yang sudah disetujui akan muncul di sini untuk dipantau pengembaliannya.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($peminjamans->hasPages())
            <div class="border-t-2 border-[#1A1D2B] bg-[#F2E7C9]/30 p-4">
                {{ $peminjamans->appends(request()->only(['search', 'tanggal_selesai']))->links() }}
            </div>
        @endif

    </div>

</div>

@endsection