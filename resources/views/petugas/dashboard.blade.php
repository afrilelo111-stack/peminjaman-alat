@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')

@section('header', 'Dashboard Petugas')

@section('content')

{{-- ============ KARTU STATISTIK UTAMA ============ --}}
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">

    {{-- Statistik 1: Peminjaman Aktif --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-5 shadow-[4px_4px_0px_#1A1D2B] relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 text-7xl font-black text-[#F2E7C9]/40 select-none group-hover:scale-110 transition-transform">01</div>
        <div class="flex items-center gap-3.5">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] text-white shadow-[2px_2px_0px_#1A1D2B] text-xl font-bold">
                📋
            </div>
            <div>
                <p class="font-mono text-[10.5px] font-extrabold uppercase tracking-widest text-[#5C6475]">Peminjaman Aktif</p>
                <h3 class="text-2xl font-extrabold text-[#1A1D2B] mt-0.5">
                    {{ $totalPeminjamanAktif ?? 12 }}
                </h3>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1.5 font-mono text-[11px] font-bold text-[#237DE1]">
            <span>↑ Dalam proses sirkulasi</span>
        </div>
    </div>

    {{-- Statistik 2: Menunggu Verifikasi --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-5 shadow-[4px_4px_0px_#1A1D2B] relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 text-7xl font-black text-[#F2E7C9]/40 select-none group-hover:scale-110 transition-transform">02</div>
        <div class="flex items-center gap-3.5">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] text-xl font-bold">
                ⏳
            </div>
            <div>
                <p class="font-mono text-[10.5px] font-extrabold uppercase tracking-widest text-[#5C6475]">Menunggu Verifikasi</p>
                <h3 class="text-2xl font-extrabold text-[#1A1D2B] mt-0.5">
                    {{ $totalMenunggu ?? 3 }}
                </h3>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1.5 font-mono text-[11px] font-bold text-[#EC5928]">
            <span>⚡ Perlu tindakan segera</span>
        </div>
    </div>

    {{-- Statistik 3: Kembali Hari Ini --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-5 shadow-[4px_4px_0px_#1A1D2B] relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 text-7xl font-black text-[#F2E7C9]/40 select-none group-hover:scale-110 transition-transform">03</div>
        <div class="flex items-center gap-3.5">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] text-white shadow-[2px_2px_0px_#1A1D2B] text-xl font-bold">
                🔄
            </div>
            <div>
                <p class="font-mono text-[10.5px] font-extrabold uppercase tracking-widest text-[#5C6475]">Kembali Hari Ini</p>
                <h3 class="text-2xl font-extrabold text-[#1A1D2B] mt-0.5">
                    {{ $totalKembaliHariIni ?? 5 }}
                </h3>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1.5 font-mono text-[11px] font-bold text-[#5C6475]">
            <span>📅 Sesuai jadwal tempo</span>
        </div>
    </div>

    {{-- Statistik 4: Total Alat Ready --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-5 shadow-[4px_4px_0px_#1A1D2B] relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 text-7xl font-black text-[#F2E7C9]/40 select-none group-hover:scale-110 transition-transform">04</div>
        <div class="flex items-center gap-3.5">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#1A1D2B] text-[#F2E7C9] shadow-[2px_2px_0px_#1A1D2B] text-xl font-bold">
                🛠️
            </div>
            <div>
                <p class="font-mono text-[10.5px] font-extrabold uppercase tracking-widest text-[#5C6475]">Total Alat Ready</p>
                <h3 class="text-2xl font-extrabold text-[#1A1D2B] mt-0.5">
                    {{ $totalAlat ?? 48 }}
                </h3>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1.5 font-mono text-[11px] font-bold text-emerald-600">
            <span>✓ Kondisi siap pakai</span>
        </div>
    </div>

</div>

{{-- ============ BANNER SELAMAT DATANG & INFO OPERASIONAL ============ --}}
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-8">

    {{-- Banner Utama --}}
    <div class="lg:col-span-2 rounded-2xl border-2 border-[#1A1D2B] bg-[#F2E7C9] p-6 sm:p-7 shadow-[4px_4px_0px_#1A1D2B] flex flex-col justify-between relative overflow-hidden">
        <div>
            <div class="inline-flex items-center gap-1.5 rounded-md border-2 border-[#1A1D2B] bg-white px-3 py-1 font-mono text-[11px] font-extrabold text-[#1A1D2B] mb-4 shadow-[2px_2px_0px_#1A1D2B]">
                <span>⚡ PANEL OPERASIONAL PETUGAS</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-extrabold uppercase tracking-tight text-[#1A1D2B]">
                Selamat Datang, {{ auth()->user()->name }}!
            </h2>
            <p class="mt-2 font-mono text-[12.5px] font-medium text-[#1A1D2B]/80 leading-relaxed max-w-xl">
                Pastikan pemeriksaan kondisi fisik alat dilakukan secara teliti pada setiap proses pengembalian dan pastikan dokumen foto bukti tersimpan dengan baik di sistem.
            </p>
        </div>

        <div class="mt-7 flex flex-wrap items-center gap-3">
            <a href="{{ route('petugas.pengembalians.index') }}"
               class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4.5 py-2.5 font-mono text-[12px] font-extrabold uppercase tracking-wider text-white shadow-[3px_3px_0px_#1A1D2B] hover:bg-[#1d69bf]">
                <span>Proses Pengembalian</span>
                <span>→</span>
            </a>
            <a href="{{ route('petugas.peminjamans.index') }}"
               class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-4.5 py-2.5 font-mono text-[12px] font-extrabold uppercase tracking-wider text-[#1A1D2B] shadow-[3px_3px_0px_#1A1D2B] hover:bg-[#F9F6EE]">
                <span>Kelola Peminjaman</span>
            </a>
        </div>
    </div>

    {{-- Status Sistem --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-6 shadow-[4px_4px_0px_#1A1D2B] flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between border-b-2 border-[#1A1D2B] pb-3 mb-4">
                <span class="font-mono text-[11px] font-extrabold uppercase tracking-widest text-[#1A1D2B]">
                    // STATUS SISTEM
                </span>
                <span class="h-3 w-3 rounded-full border border-[#1A1D2B] bg-emerald-500 animate-pulse"></span>
            </div>
            <div class="space-y-3 font-mono text-[12px]">
                <div class="flex justify-between items-center">
                    <span class="text-[#5C6475]">Hari Ini:</span>
                    <span class="font-bold text-[#1A1D2B]">{{ now()->translatedFormat('d M Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[#5C6475]">Sesi Keamanan:</span>
                    <span class="font-bold text-[#237DE1]">Terenkripsi</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[#5C6475]">Role Akses:</span>
                    <span class="rounded border border-[#1A1D2B] bg-[#F2E7C9] px-2 py-0.5 font-extrabold text-[#1A1D2B]">Petugas</span>
                </div>
            </div>
        </div>
        <div class="mt-6 border-t-2 border-dashed border-[#1A1D2B]/30 pt-3 text-center font-mono text-[10.5px] text-[#5C6475]">
            Inventaris Alat v2.5.0
        </div>
    </div>

</div>

{{-- ============ TABEL AKTIVITAS TERBARU ============ --}}
<div class="rounded-2xl border-2 border-[#1A1D2B] bg-white shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b-2 border-[#1A1D2B] bg-[#F2E7C9]/40 px-5 py-4 gap-3">
        <div>
            <h3 class="font-extrabold uppercase tracking-tight text-[#1A1D2B] text-base">
                Aktivitas Peminjaman Terbaru
            </h3>
            <p class="font-mono text-[11px] text-[#5C6475] mt-0.5">
                // Daftar transaksi sirkulasi alat terakhir yang masuk ke sistem
            </p>
        </div>
        <a href="{{ route('petugas.peminjamans.index') }}"
           class="tactile-btn inline-flex items-center justify-center gap-1.5 rounded-xl border-2 border-[#1A1D2B] bg-white px-3.5 py-1.5 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9]">
            <span>Lihat Semua</span>
            <span>→</span>
        </a>
    </div>

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
                        Tanggal Pinjam / Tempo
                    </th>
                    <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest">
                        Status
                    </th>
                    <th class="px-4 py-3.5 font-mono text-[11px] font-bold uppercase tracking-widest text-center">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y-2 divide-[#1A1D2B]">
                @forelse ($peminjamanTerbaru ?? [] as $index => $item)
                    <tr class="transition hover:bg-[#F2E7C9]/30">
                        <td class="border-r-2 border-[#1A1D2B] px-3 py-4 text-center font-mono text-[12px] font-bold text-[#5C6475]">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="border-r-2 border-[#1A1D2B] px-4 py-4">
                            <p class="font-extrabold text-[13px] text-[#1A1D2B]">{{ $item->peminjam->name ?? '-' }}</p>
                            <p class="font-mono text-[11px] text-[#5C6475]">{{ $item->peminjam->email ?? '-' }}</p>
                        </td>
                        <td class="border-r-2 border-[#1A1D2B] px-4 py-4 font-mono text-[12px]">
                            <span class="text-[#237DE1] font-bold">Pinjam:</span> {{ $item->tanggal_pinjam->format('d/m/Y') }}<br>
                            <span class="text-[#EC5928] font-bold">Tempo:</span> {{ $item->tanggal_rencana_kembali->format('d/m/Y') }}
                        </td>
                        <td class="border-r-2 border-[#1A1D2B] px-4 py-4">
                            <span class="inline-block rounded-md border-2 border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-1 font-mono text-[10px] font-extrabold uppercase tracking-wider text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B]">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <a href="{{ route('petugas.peminjamans.index') }}"
                               class="tactile-btn inline-flex items-center gap-1 rounded-xl border-2 border-[#1A1D2B] bg-white px-3 py-1.5 font-mono text-[11px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#237DE1] hover:text-white transition">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center bg-white">
                            <div class="mx-auto flex max-w-sm flex-col items-center gap-2">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] font-mono font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B]">
                                    i
                                </div>
                                <p class="text-sm font-extrabold uppercase text-[#1A1D2B]">Belum ada aktivitas sirkulasi</p>
                                <p class="font-mono text-[11px] text-[#5C6475]">Data transaksi peminjaman baru akan muncul di tabel ini secara otomatis.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection