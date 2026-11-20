@extends('layouts.admin')

@section('content')

{{-- ============ PAGE HEADER ============ --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        {{-- Breadcrumb Pill --}}
        <div class="mb-2 inline-flex items-center gap-1.5 rounded-md border-2 border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-0.5 font-mono text-[11px] font-bold text-[#1A1D2B]">
            <a href="{{ route('admin.users.index') }}" class="hover:text-[#237DE1] underline decoration-1 underline-offset-2">Home</a>
            <span>/</span>
            <span>transaksi</span>
            <span>/</span>
            <span class="text-[#EC5928]">pengembalian</span>
        </div>

        <h1 class="text-[22px] sm:text-[26px] font-extrabold tracking-tight text-[#1A1D2B] leading-tight uppercase">
            Data Pengembalian Alat
        </h1>
        <p class="mt-1 font-mono text-[12px] font-medium text-[#5C6475]">
            // pantau histori pengembalian, kondisi fisik alat, dan dokumen bukti
        </p>
    </div>

    <a href="{{ route('admin.pengembalians.create') }}"
       class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-2.5 font-mono text-[13px] font-bold text-white shadow-[3px_3px_0px_#1A1D2B] hover:bg-[#1d69bf]">
        <span class="flex h-5 w-5 items-center justify-center rounded border border-[#1A1D2B] bg-white text-[#237DE1] text-sm font-extrabold">+</span>
        <span>Tambah Pengembalian</span>
    </a>
</div>

{{-- ============ FLASH MESSAGES ============ --}}
@if (session('success'))
    <div class="mb-6 flex items-center gap-3 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-3.5 text-white shadow-[4px_4px_0px_#1A1D2B]">
        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-white text-[#237DE1]">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <p class="text-[13.5px] font-bold">{{ session('success') }}</p>
    </div>
@endif

@if (session('error') || $errors->any())
    <div class="mb-6 flex items-center gap-3 rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] px-4 py-3.5 text-white shadow-[4px_4px_0px_#1A1D2B]">
        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-white text-[#EC5928]">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <div>
            <p class="font-extrabold tracking-wider text-[13px] uppercase">Terjadi kesalahan pada data pengembalian:</p>
            @if(session('error'))
                <p class="font-mono text-[11px] font-bold mt-0.5">{{ session('error') }}</p>
            @endif
            @if($errors->any())
                <ul class="mt-1 list-inside list-disc font-mono text-[11px] font-bold opacity-90">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endif

{{-- ============ MAIN PANEL (Bungkus Alpine.js Data untuk Modal) ============ --}}
<div x-data="{ 
    showDeleteModal: false, 
    deleteUrl: '', 
    peminjamName: '', 
    tglPengembalian: '' 
}" class="relative">

    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">

        {{-- ==== TOOLBAR (search + info) ==== --}}
        <div class="border-b-2 border-[#1A1D2B] bg-[#F2E7C9]/40 p-4 sm:p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <form method="GET" action="{{ route('admin.pengembalians.index') }}" class="w-full sm:max-w-md">
                    <div class="flex items-stretch overflow-hidden rounded-xl border-2 border-[#1A1D2B] bg-white shadow-[2px_2px_0px_#1A1D2B]">
                        <span class="flex items-center justify-center border-r-2 border-[#1A1D2B] bg-[#F2E7C9] px-3.5 text-[#1A1D2B]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama peminjam..."
                            class="w-full bg-transparent px-3 py-2 font-mono text-[13px] font-medium text-[#1A1D2B] placeholder:text-[#5C6475] focus:outline-none"
                        >
                        <button type="submit"
                            class="border-l-2 border-[#1A1D2B] bg-[#237DE1] px-4 font-mono text-[11px] font-bold uppercase tracking-wider text-white transition hover:bg-[#1d69bf]">
                            Cari
                        </button>
                    </div>
                </form>

                <div class="flex items-center gap-3">
                    @if(request('search'))
                        <a href="{{ route('admin.pengembalians.index') }}"
                           class="font-mono text-[11px] font-bold uppercase tracking-wider text-[#EC5928] underline decoration-2 underline-offset-2">
                            × Reset filter
                        </a>
                    @endif
                    <span class="font-mono-tech rounded-lg border-2 border-[#1A1D2B] bg-white px-3 py-1.5 text-[11px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B]">
                        TOTAL: {{ $pengembalians->total() }} DATA
                    </span>
                </div>
            </div>
        </div>

        {{-- ==== TABLE ==== --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">

                <thead>
                    <tr class="border-b-2 border-[#1A1D2B] bg-[#1A1D2B] text-white">
                        <th class="w-[60px] border-r-2 border-[#1A1D2B] px-3 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest text-center">
                            #
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                            Peminjam
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                            Petugas
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                            Tanggal Kembali
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                            Detail Alat & Kondisi
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest text-center">
                            Foto Bukti
                        </th>
                        <th class="px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest text-center">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y-2 divide-[#1A1D2B]">
                    @forelse ($pengembalians as $index => $pengembalian)
                        @php
                            $rowNo = $pengembalians->firstItem() + $index;
                        @endphp

                        <tr class="transition hover:bg-[#F2E7C9]/30">

                            {{-- NO --}}
                            <td class="border-r-2 border-[#1A1D2B] px-3 py-4 text-center align-top">
                                <span class="font-mono-tech text-[13px] font-bold text-[#5C6475]">
                                    {{ str_pad($rowNo, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            {{-- PEMINJAM --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-top">
                                <p class="text-[13.5px] font-extrabold text-[#1A1D2B]">
                                    {{ $pengembalian->peminjaman->peminjam->name ?? 'User Dihapus' }}
                                </p>
                                <p class="font-mono text-[11px] text-[#5C6475] mt-0.5">
                                    {{ $pengembalian->peminjaman->peminjam->email ?? '-' }}
                                </p>
                            </td>

                            {{-- PETUGAS --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-top">
                                <span class="font-mono text-[12px] font-semibold text-[#1A1D2B]">
                                    {{ $pengembalian->petugas->name ?? '-' }}
                                </span>
                            </td>

                            {{-- TANGGAL KEMBALI --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-top font-mono text-[12px] font-bold text-[#237DE1]">
                                {{ $pengembalian->tanggal_pengembalian->format('d/m/Y') }}
                            </td>

                            {{-- DETAIL ALAT --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-top">
                                <div class="space-y-2">
                                    @foreach ($pengembalian->detailPengembalian as $detail)
                                        @php
                                            $kondisi = strtolower($detail->kondisi_alat);
                                            $badgeStyle = match(true) {
                                                str_contains($kondisi, 'baik') => 'bg-[#237DE1] text-white',
                                                str_contains($kondisi, 'rusak') => 'bg-[#EC5928] text-white',
                                                default => 'bg-[#F2E7C9] text-[#1A1D2B]',
                                            };
                                        @endphp
                                        <div class="rounded-lg border border-[#1A1D2B]/20 bg-white p-2 shadow-[1px_1px_0px_#1A1D2B]">
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-xs font-bold text-[#1A1D2B]">
                                                    • {{ $detail->detailPeminjaman->alat->nama_alat ?? 'Alat tidak tersedia' }}
                                                </span>
                                                <span class="font-mono rounded border border-[#1A1D2B] bg-[#F2E7C9] px-1.5 py-0.2 text-[10px] font-extrabold text-[#1A1D2B]">
                                                    {{ $detail->jumlah_dikembalikan }} unit
                                                </span>
                                            </div>
                                            <div class="mt-1">
                                                <span class="inline-block rounded border border-[#1A1D2B] px-2 py-0.5 font-mono text-[9px] font-extrabold uppercase tracking-wider {{ $badgeStyle }}">
                                                    {{ $detail->kondisi_alat }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            {{-- FOTO BUKTI --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-top text-center">
                                <div class="flex flex-wrap items-center justify-center gap-2">
                                    @foreach ($pengembalian->detailPengembalian as $detail)
                                        @if ($detail->gambar_bukti)
                                            <a
                                                href="{{ asset('storage/' . $detail->gambar_bukti) }}"
                                                target="_blank"
                                                title="Lihat foto bukti"
                                                class="group block"
                                            >
                                                <img
                                                    src="{{ asset('storage/' . $detail->gambar_bukti) }}"
                                                    alt="Foto bukti"
                                                    class="h-14 w-14 rounded-lg border-2 border-[#1A1D2B] object-cover shadow-[2px_2px_0px_#1A1D2B] transition group-hover:scale-105"
                                                >
                                            </a>
                                        @else
                                            <span class="font-mono text-[10px] text-[#5C6475] italic">
                                                Tanpa Foto
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-4 py-4 align-top text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Tombol Hapus memicu Modal Alpine.js --}}
                                    <button
                                        type="button"
                                        @click="showDeleteModal = true; deleteUrl = '{{ route('admin.pengembalians.destroy', $pengembalian->id) }}'; peminjamName = '{{ $pengembalian->peminjaman->peminjam->name ?? 'User' }}'; tglPengembalian = '{{ $pengembalian->tanggal_pengembalian->format('d/m/Y') }}'"
                                        class="inline-flex items-center gap-1 rounded-lg border-2 border-[#1A1D2B] bg-white px-3 py-1.5 font-mono text-[11px] font-bold text-[#EC5928] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#EC5928] hover:text-white transition"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center gap-3">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] font-mono text-xl font-extrabold text-[#EC5928] shadow-[3px_3px_0px_#1A1D2B]">
                                        Ø
                                    </div>
                                    <p class="text-base font-extrabold uppercase text-[#1A1D2B]">
                                        Belum ada data pengembalian
                                    </p>
                                    <p class="font-mono text-[12px] text-[#5C6475]">
                                        @if(request('search'))
                                            Pencarian untuk "{{ request('search') }}" tidak ditemukan.
                                        @else
                                            Belum ada riwayat pengembalian alat yang tercatat.
                                        @endif
                                    </p>
                                    @if(request('search'))
                                        <a href="{{ route('admin.pengembalians.index') }}"
                                           class="mt-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-2 font-mono text-[11px] font-bold text-white shadow-[2px_2px_0px_#1A1D2B]">
                                            Reset Pencarian
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($pengembalians->hasPages())
            <div class="border-t-2 border-[#1A1D2B] bg-[#F2E7C9]/40 px-4 py-3.5">
                <div class="flex flex-col items-center justify-between gap-3 sm:flex-row">

                    <p class="font-mono text-[11px] font-bold text-[#5C6475]">
                        Menampilkan {{ $pengembalians->firstItem() }}–{{ $pengembalians->lastItem() }} dari {{ $pengembalians->total() }} total data
                    </p>

                    <div class="flex items-center gap-1.5">
                        {{-- Prev --}}
                        @if($pengembalians->onFirstPage())
                            <span class="inline-flex h-8 items-center rounded-lg border-2 border-[#1A1D2B]/30 bg-white/50 px-3 font-mono text-[11px] font-bold text-[#5C6475]/40 cursor-not-allowed">
                                ← Prev
                            </span>
                        @else
                            <a href="{{ $pengembalians->previousPageUrl() }}"
                               class="inline-flex h-8 items-center rounded-lg border-2 border-[#1A1D2B] bg-white px-3 font-mono text-[11px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9] transition">
                                ← Prev
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach($pengembalians->getUrlRange(max(1, $pengembalians->currentPage()-2), min($pengembalians->lastPage(), $pengembalians->currentPage()+2)) as $page => $url)
                            @if($page == $pengembalians->currentPage())
                                <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-[#EC5928] px-2 font-mono text-[11px] font-extrabold text-white shadow-[2px_2px_0px_#1A1D2B]">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                   class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-white px-2 font-mono text-[11px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9] transition">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($pengembalians->hasMorePages())
                            <a href="{{ $pengembalians->nextPageUrl() }}"
                               class="inline-flex h-8 items-center rounded-lg border-2 border-[#1A1D2B] bg-white px-3 font-mono text-[11px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9] transition">
                                Next →
                            </a>
                        @else
                            <span class="inline-flex h-8 items-center rounded-lg border-2 border-[#1A1D2B]/30 bg-white/50 px-3 font-mono text-[11px] font-bold text-[#5C6475]/40 cursor-not-allowed">
                                Next →
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- ============ MODAL POPUP KONFIRMASI HAPUS (NEO-BRUTALIST) ============ --}}
    <div 
        x-cloak
        x-show="showDeleteModal" 
        class="fixed inset-0 z-50 flex items-center justify-center bg-[#1A1D2B]/60 p-4 backdrop-blur-xs"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            @click.outside="showDeleteModal = false"
            class="w-full max-w-md rounded-2xl border-2 border-[#1A1D2B] bg-white p-6 shadow-[6px_6px_0px_#1A1D2B]"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="scale-95 opacity-0"
            x-transition:enter-end="scale-100 opacity-100"
        >
            {{-- Header Modal --}}
            <div class="flex items-center gap-3 border-b-2 border-[#1A1D2B] pb-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] text-white shadow-[2px_2px_0px_#1A1D2B]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold uppercase text-[#1A1D2B]">Konfirmasi Hapus Pengembalian</h3>
                    <p class="font-mono text-[11px] text-[#5C6475]">Penghapusan data histori permanen</p>
                </div>
            </div>

            {{-- Pesan Peringatan --}}
            <div class="py-5">
                <p class="text-[13.5px] font-medium text-[#1A1D2B]">
                    Apakah Anda yakin ingin menghapus data pengembalian dari peminjam <strong class="text-[#EC5928] underline decoration-2 underline-offset-2" x-text="peminjamName"></strong> (Tanggal: <span class="font-mono font-bold" x-text="tglPengembalian"></span>)?
                </p>
                <div class="mt-3 rounded-xl border border-[#1A1D2B] bg-[#F2E7C9]/50 p-3 font-mono text-[11px] text-[#1A1D2B]">
                    ⚠️ Peringatan: Tindakan ini akan menghapus data bukti foto, rincian kondisi alat, serta log pengembalian terkait dari sistem.
                </div>
            </div>

            {{-- Tombol Aksi Modal --}}
            <div class="flex items-center justify-end gap-3 border-t-2 border-[#1A1D2B] pt-4">
                <button type="button"
                    @click="showDeleteModal = false"
                    class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2 font-mono text-[12px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9]">
                    Batal
                </button>

                <form :action="deleteUrl" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] px-4 py-2 font-mono text-[12px] font-extrabold uppercase tracking-wider text-white shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#d94b1c]">
                        Ya, Hapus Data
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection