@extends('layouts.admin')

@section('content')

{{-- ============ PAGE HEADER ============ --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        {{-- Breadcrumb Pill --}}
        <div class="mb-2 inline-flex items-center gap-1.5 rounded-md border-2 border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-0.5 font-mono text-[11px] font-bold text-[#1A1D2B]">
            <a href="{{ route('admin.users.index') }}" class="hover:text-[#237DE1] underline decoration-1 underline-offset-2">Home</a>
            <span>/</span>
            <span>manajemen</span>
            <span>/</span>
            <span class="text-[#EC5928]">kategori</span>
        </div>

        <h1 class="text-[22px] sm:text-[26px] font-extrabold tracking-tight text-[#1A1D2B] leading-tight uppercase">
            Manajemen Kategori Alat
        </h1>
        <p class="mt-1 font-mono text-[12px] font-medium text-[#5C6475]">
            // kelola pengelompokan jenis dan kategori alat inventaris
        </p>
    </div>

    <a href="{{ route('admin.kategoris.create') }}"
       class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-2.5 font-mono text-[13px] font-bold text-white shadow-[3px_3px_0px_#1A1D2B] hover:bg-[#1d69bf]">
        <span class="flex h-5 w-5 items-center justify-center rounded border border-[#1A1D2B] bg-white text-[#237DE1] text-sm font-extrabold">+</span>
        <span>Tambah Kategori</span>
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

@if (session('error'))
    <div class="mb-6 flex items-center gap-3 rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] px-4 py-3.5 text-white shadow-[4px_4px_0px_#1A1D2B]">
        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-white text-[#EC5928]">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <p class="text-[13.5px] font-bold">{{ session('error') }}</p>
    </div>
@endif

{{-- ============ MAIN PANEL (Bungkus dengan Alpine Data untuk Modal) ============ --}}
<div x-data="{ 
    showDeleteModal: false, 
    deleteUrl: '', 
    kategoriName: '' 
}" class="relative">

    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">

        {{-- ==== TOOLBAR (search + info) ==== --}}
        <div class="border-b-2 border-[#1A1D2B] bg-[#F2E7C9]/40 p-4 sm:p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <form action="{{ route('admin.kategoris.index') }}" method="GET" class="w-full sm:max-w-md">
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
                            placeholder="Cari nama kategori..."
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
                        <a href="{{ route('admin.kategoris.index') }}"
                           class="font-mono text-[11px] font-bold uppercase tracking-wider text-[#EC5928] underline decoration-2 underline-offset-2">
                            × Reset filter
                        </a>
                    @endif
                    <span class="font-mono-tech rounded-lg border-2 border-[#1A1D2B] bg-white px-3 py-1.5 text-[11px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B]">
                        TOTAL: {{ $kategoris->total() }} KATEGORI
                    </span>
                </div>
            </div>
        </div>

        {{-- ==== TABLE ==== --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">

                <thead>
                    <tr class="border-b-2 border-[#1A1D2B] bg-[#1A1D2B] text-white">
                        <th class="w-[70px] border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest text-center">
                            #
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                            Nama Kategori
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                            Deskripsi
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                            Dibuat
                        </th>
                        <th class="px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest text-right">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y-2 divide-[#1A1D2B]">
                    @forelse ($kategoris as $kategori)
                        @php $rowNo = $kategoris->firstItem() + $loop->index; @endphp

                        <tr class="transition hover:bg-[#F2E7C9]/30">

                            {{-- NO --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 text-center align-middle">
                                <span class="font-mono-tech text-[13px] font-bold text-[#5C6475]">
                                    {{ str_pad($rowNo, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            {{-- NAMA KATEGORI --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-middle">
                                <span class="text-[13.5px] font-extrabold text-[#1A1D2B]">
                                    {{ $kategori->nama_kategori }}
                                </span>
                            </td>

                            {{-- DESKRIPSI --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-middle">
                                <span class="font-mono text-[12.5px] text-[#5C6475]">
                                    {{ $kategori->deskripsi ?: '-' }}
                                </span>
                            </td>

                            {{-- DIBUAT --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-middle">
                                <span class="font-mono text-[12px] font-semibold text-[#1A1D2B]">
                                    {{ $kategori->created_at->format('d/m/Y') }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-4 py-4 align-middle text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.kategoris.edit', $kategori) }}"
                                       class="inline-flex items-center gap-1 rounded-lg border-2 border-[#1A1D2B] bg-[#F2E7C9] px-3 py-1.5 font-mono text-[11px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#237DE1] hover:text-white transition">
                                        Edit
                                    </a>

                                    {{-- Tombol Hapus memicu Modal Alpine.js --}}
                                    <button type="button"
                                        @click="showDeleteModal = true; deleteUrl = '{{ route('admin.kategoris.destroy', $kategori) }}'; kategoriName = '{{ $kategori->nama_kategori }}'"
                                        class="inline-flex items-center gap-1 rounded-lg border-2 border-[#1A1D2B] bg-white px-3 py-1.5 font-mono text-[11px] font-bold text-[#EC5928] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#EC5928] hover:text-white transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-16 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center gap-3">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] font-mono text-xl font-extrabold text-[#EC5928] shadow-[3px_3px_0px_#1A1D2B]">
                                        Ø
                                    </div>
                                    <p class="text-base font-extrabold uppercase text-[#1A1D2B]">
                                        Belum ada kategori
                                    </p>
                                    <p class="font-mono text-[12px] text-[#5C6475]">
                                        @if(request('search'))
                                            Pencarian untuk "{{ request('search') }}" tidak ditemukan.
                                        @else
                                            Belum ada data kategori alat yang terdaftar.
                                        @endif
                                    </p>
                                    @if(request('search'))
                                        <a href="{{ route('admin.kategoris.index') }}"
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

        {{-- ==== PAGINATION ==== --}}
        @if($kategoris->hasPages())
            <div class="border-t-2 border-[#1A1D2B] bg-[#F2E7C9]/40 px-4 py-3.5">
                <div class="flex flex-col items-center justify-between gap-3 sm:flex-row">

                    <p class="font-mono text-[11px] font-bold text-[#5C6475]">
                        Menampilkan {{ $kategoris->firstItem() }}–{{ $kategoris->lastItem() }} dari {{ $kategoris->total() }} total kategori
                    </p>

                    <div class="flex items-center gap-1.5">
                        @if($kategoris->onFirstPage())
                            <span class="inline-flex h-8 items-center rounded-lg border-2 border-[#1A1D2B]/30 bg-white/50 px-3 font-mono text-[11px] font-bold text-[#5C6475]/40 cursor-not-allowed">
                                ← Prev
                            </span>
                        @else
                            <a href="{{ $kategoris->previousPageUrl() }}"
                               class="inline-flex h-8 items-center rounded-lg border-2 border-[#1A1D2B] bg-white px-3 font-mono text-[11px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9] transition">
                                ← Prev
                            </a>
                        @endif

                        @foreach($kategoris->getUrlRange(max(1, $kategoris->currentPage()-2), min($kategoris->lastPage(), $kategoris->currentPage()+2)) as $page => $url)
                            @if($page == $kategoris->currentPage())
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

                        @if($kategoris->hasMorePages())
                            <a href="{{ $kategoris->nextPageUrl() }}"
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
                    <h3 class="text-base font-extrabold uppercase text-[#1A1D2B]">Konfirmasi Hapus Kategori</h3>
                    <p class="font-mono text-[11px] text-[#5C6475]">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            {{-- Pesan Peringatan --}}
            <div class="py-5">
                <p class="text-[13.5px] font-medium text-[#1A1D2B]">
                    Apakah Anda yakin ingin menghapus kategori <strong class="text-[#EC5928] underline decoration-2 underline-offset-2" x-text="kategoriName"></strong>?
                </p>
                <div class="mt-3 rounded-xl border border-[#1A1D2B] bg-[#F2E7C9]/50 p-3 font-mono text-[11px] text-[#1A1D2B]">
                    ⚠️ Peringatan: Menghapus kategori dapat menyebabkan data alat yang terikat kehilangan referensi kategori atau gagal dimuat.
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
                        Ya, Hapus Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection