@extends('layouts.admin')

@section('content')

@php
    $roleStyles = [
        'admin'    => ['bg' => '#EC5928', 'text' => 'white', 'label' => 'ADMIN'],
        'petugas'  => ['bg' => '#237DE1', 'text' => 'white', 'label' => 'PETUGAS'],
        'peminjam' => ['bg' => '#F2E7C9', 'text' => '#1A1D2B', 'label' => 'PEMINJAM'],
    ];
    $roleClasses = [
        'admin'    => 'bg-[#EC5928] text-white',
        'petugas'  => 'bg-[#237DE1] text-white',
        'peminjam' => 'bg-[#F2E7C9] text-[#1A1D2B]',
    ];
@endphp

{{-- ============ PAGE HEADER ============ --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

    <div>
        {{-- Breadcrumb Pill --}}
        <div class="mb-2 inline-flex items-center gap-1.5 rounded-md border-2 border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-0.5 font-mono text-[11px] font-bold text-[#1A1D2B]">
            <a href="{{ route('admin.users.index') }}" class="hover:text-[#237DE1] underline decoration-1 underline-offset-2">Home</a>
            <span>/</span>
            <span>manajemen</span>
            <span>/</span>
            <span class="text-[#EC5928]">users</span>
        </div>

        <h1 class="text-[22px] sm:text-[26px] font-extrabold tracking-tight text-[#1A1D2B] leading-tight uppercase">
            Manajemen Pengguna
        </h1>
        <p class="mt-1 font-mono text-[12px] font-medium text-[#5C6475]">
            // kelola akun, role, dan hak akses sistem peminjaman
        </p>
    </div>

    <a href="{{ route('admin.users.create') }}"
       class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-2.5 font-mono text-[13px] font-bold text-white shadow-[3px_3px_0px_#1A1D2B] hover:bg-[#1d69bf]">
        <span class="flex h-5 w-5 items-center justify-center rounded border border-[#1A1D2B] bg-white text-[#237DE1] text-sm font-extrabold">+</span>
        <span>Tambah Pengguna</span>
    </a>
</div>

{{-- ============ FLASH MESSAGES ============ --}}
@if(session('success'))
    <div class="mb-6 flex items-center gap-3 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-3.5 text-white shadow-[4px_4px_0px_#1A1D2B]">
        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-white text-[#237DE1]">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <p class="text-[13.5px] font-bold">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
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
    userName: '' 
}" class="relative">

    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">

        {{-- ==== TOOLBAR (search + info) ==== --}}
        <div class="border-b-2 border-[#1A1D2B] bg-[#F2E7C9]/40 p-4 sm:p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <form method="GET" action="{{ route('admin.users.index') }}" class="w-full sm:max-w-md">
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
                            placeholder="Cari nama atau email pengguna..."
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
                        <a href="{{ route('admin.users.index') }}"
                           class="font-mono text-[11px] font-bold uppercase tracking-wider text-[#EC5928] underline decoration-2 underline-offset-2">
                            × Reset filter
                        </a>
                    @endif
                    <span class="font-mono-tech rounded-lg border-2 border-[#1A1D2B] bg-white px-3 py-1.5 text-[11px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B]">
                        TOTAL: {{ $users->total() }} USER
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
                            Identitas
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                            Email
                        </th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                            Role
                        </th>
                        <th class="px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest text-right">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y-2 divide-[#1A1D2B]">
                    @forelse($users as $user)
                        @php
                            $role = $roleStyles[$user->role] ?? ['bg' => '#F2E7C9', 'text' => '#1A1D2B', 'label' => strtoupper($user->role)];
                            $isSelf = auth()->id() === $user->id;
                            $rowNo = $users->firstItem() + $loop->index;
                        @endphp

                        <tr class="transition hover:bg-[#F2E7C9]/30">

                            {{-- NO --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 text-center align-middle">
                                <span class="font-mono-tech text-[13px] font-bold text-[#5C6475]">
                                    {{ str_pad($rowNo, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            {{-- IDENTITAS --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-middle">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-[#237DE1] font-mono text-xs font-extrabold text-white shadow-[2px_2px_0px_#1A1D2B]">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-[13.5px] font-bold text-[#1A1D2B]">
                                            {{ $user->name }}
                                            @if($isSelf)
                                                <span class="ml-1.5 inline-block rounded border border-[#1A1D2B] bg-[#EC5928] px-1.5 py-0.5 font-mono text-[9px] font-extrabold text-white align-middle">
                                                    ANDA
                                                </span>
                                            @endif
                                        </p>
                                        <p class="font-mono-tech text-[10.5px] text-[#5C6475]">
                                            ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- EMAIL --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-middle">
                                <a href="mailto:{{ $user->email }}"
                                   class="font-mono text-[12.5px] font-medium text-[#237DE1] hover:underline">
                                    {{ $user->email }}
                                </a>
                            </td>

                            {{-- ROLE BADGE --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 align-middle">
                                <span class="{{ $roleClasses[$user->role] ?? 'bg-[#F2E7C9] text-[#1A1D2B]' }} inline-block rounded-md border-2 border-[#1A1D2B] px-2.5 py-1 font-mono text-[10px] font-extrabold tracking-wider shadow-[2px_2px_0px_#1A1D2B]">
                                    {{ $role['label'] }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-4 py-4 align-middle text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="inline-flex items-center gap-1 rounded-lg border-2 border-[#1A1D2B] bg-[#F2E7C9] px-3 py-1.5 font-mono text-[11px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#237DE1] hover:text-white transition">
                                        Edit
                                    </a>

                                    @if(!$isSelf)
                                        {{-- Tombol Hapus memicu Modal Alpine.js (Tanpa konfirmasi browser bawaan) --}}
                                        <button type="button"
                                            @click="showDeleteModal = true; deleteUrl = '{{ route('admin.users.destroy', $user) }}'; userName = '{{ $user->name }}'"
                                            class="inline-flex items-center gap-1 rounded-lg border-2 border-[#1A1D2B] bg-white px-3 py-1.5 font-mono text-[11px] font-bold text-[#EC5928] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#EC5928] hover:text-white transition">
                                            Hapus
                                        </button>
                                    @else
                                        <span class="inline-flex items-center rounded-lg border-2 border-dashed border-[#5C6475]/40 px-3 py-1.5 font-mono text-[11px] font-bold text-[#5C6475]/50 cursor-not-allowed">
                                            Locked
                                        </span>
                                    @endif
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
                                        Tidak ada data pengguna
                                    </p>
                                    <p class="font-mono text-[12px] text-[#5C6475]">
                                        @if(request('search'))
                                            Pencarian untuk "{{ request('search') }}" tidak ditemukan.
                                        @else
                                            Belum ada data pengguna yang terdaftar di database.
                                        @endif
                                    </p>
                                    @if(request('search'))
                                        <a href="{{ route('admin.users.index') }}"
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
        @if($users->hasPages())
            <div class="border-t-2 border-[#1A1D2B] bg-[#F2E7C9]/40 px-4 py-3.5">
                <div class="flex flex-col items-center justify-between gap-3 sm:flex-row">

                    <p class="font-mono text-[11px] font-bold text-[#5C6475]">
                        Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} total data
                    </p>

                    <div class="flex items-center gap-1.5">
                        @if($users->onFirstPage())
                            <span class="inline-flex h-8 items-center rounded-lg border-2 border-[#1A1D2B]/30 bg-white/50 px-3 font-mono text-[11px] font-bold text-[#5C6475]/40 cursor-not-allowed">
                                ← Prev
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}"
                               class="inline-flex h-8 items-center rounded-lg border-2 border-[#1A1D2B] bg-white px-3 font-mono text-[11px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9] transition">
                                ← Prev
                            </a>
                        @endif

                        @foreach($users->getUrlRange(max(1, $users->currentPage()-2), min($users->lastPage(), $users->currentPage()+2)) as $page => $url)
                            @if($page == $users->currentPage())
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

                        @if($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}"
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
                    <h3 class="text-base font-extrabold uppercase text-[#1A1D2B]">Konfirmasi Penghapusan</h3>
                    <p class="font-mono text-[11px] text-[#5C6475]">Tindakan ini permanen dan tidak dapat dibatalkan</p>
                </div>
            </div>

            {{-- Pesan Peringatan --}}
            <div class="py-5">
                <p class="text-[13.5px] font-medium text-[#1A1D2B]">
                    Apakah Anda yakin ingin menghapus akun pengguna <strong class="text-[#EC5928] underline decoration-2 underline-offset-2" x-text="userName"></strong>?
                </p>
                <div class="mt-3 rounded-xl border border-[#1A1D2B] bg-[#F2E7C9]/50 p-3 font-mono text-[11px] text-[#1A1D2B]">
                    ⚠️ Seluruh data histori yang terikat dengan pengguna ini mungkin akan ikut terpengaruh atau terhapus dari sistem.
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
                        Ya, Hapus Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection