@extends('layouts.user')

@section('title', 'Riwayat Peminjaman')
@section('header', 'Daftar Transaksi Peminjaman')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto">

    {{-- FLASH MESSAGE SUKSES/ERROR --}}
    @if (session('success'))
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-emerald-200 p-4 hard-shadow flex items-center gap-3">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-white font-mono-tech font-extrabold text-emerald-700 hard-shadow-sm">
                ✓
            </div>
            <p class="font-mono-tech text-xs font-bold text-emerald-950">
                {{ session('success') }}
            </p>
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-[#EC5928]/20 p-4 hard-shadow flex items-center gap-3">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] font-mono-tech font-extrabold text-white hard-shadow-sm">
                !
            </div>
            <p class="font-mono-tech text-xs font-bold text-[#1A1D2B]">
                {{ session('error') }}
            </p>
        </div>
    @endif

    {{-- LAYOUT 2 KOLOM (SIDEBAR PROFIL DI KIRI, DAFTAR TRANSAKSI DI KANAN) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- KOLOM KIRI: SIDEBAR AKUN & MENU PENGGUNA (Span 3) --}}
        <div class="lg:col-span-3 space-y-4">
            <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-5 hard-shadow space-y-4">
                <div class="flex items-center gap-3 border-b-2 border-[#1A1D2B]/15 pb-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] text-white font-mono-tech font-bold text-lg hard-shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-extrabold text-sm text-[#1A1D2B] truncate">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                        <span class="font-mono-tech text-[10px] font-bold text-[#5C6475] bg-[#F2E7C9] px-2 py-0.5 rounded border border-[#1A1D2B]">
                            Active User
                        </span>
                    </div>
                </div>

                <div class="space-y-1.5 font-mono-tech text-xs">
                    <a href="{{ route('user.riwayat-peminjamans.index') }}" 
                       class="flex items-center justify-between p-2.5 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] text-white font-bold hard-shadow-sm">
                        <span>📋 Riwayat Peminjaman</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="{{ route('user.alats.index') }}" 
                       class="flex items-center justify-between p-2.5 rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] text-[#1A1D2B] font-bold hover:bg-[#F2E7C9] transition">
                        <span>📦 Katalog Inventaris</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <div class="rounded-2xl border-2 border-[#1A1D2B] bg-[#F2E7C9] p-4 hard-shadow space-y-2">
                <span class="font-mono-tech text-[10px] font-extrabold uppercase text-[#EC5928] block">
                    // PANDUAN PEMINJAMAN
                </span>
                <p class="text-[12px] font-medium text-[#1A1D2B] leading-relaxed">
                    Gunakan filter di atas daftar transaksi untuk mempermudah pencarian riwayat alat berdasarkan status atau tanggal.
                </p>
            </div>
        </div>

        {{-- KOLOM KANAN: KOTAK FILTER & DAFTAR TRANSAKSI (Span 9) --}}
        <div class="lg:col-span-9 space-y-5">

            {{-- FILTER PENCARIAN TRANSAKSI ALA TOKOPEDIA --}}
            <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-5 hard-shadow space-y-4">
                
                <form action="{{ route('user.riwayat-peminjamans.index') }}" method="GET" class="space-y-4">
                    
                    {{-- Baris 1: Search Bar, Kategori Produk, & Filter Tanggal --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        
                        {{-- Input Pencarian Nama Alat / Kode --}}
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari transaksimu di sini..." 
                                   class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] pl-9 pr-3 py-2.5 font-mono-tech text-xs font-medium text-[#1A1D2B] focus:outline-none">
                        </div>

                        {{-- Dropdown Kategori / Semua Produk --}}
                        <div>
                            <select name="kategori_id" 
                                    class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] px-3 py-2.5 font-mono-tech text-xs font-bold text-[#1A1D2B] focus:outline-none cursor-pointer">
                                <option value="">Semua Produk</option>
                                @if(isset($kategoris))
                                    @foreach($kategoris as $kat)
                                        <option value="{{ $kat->id }}" @selected(request('kategori_id') == $kat->id)>
                                            {{ $kat->nama_kategori }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Filter Tanggal Transaksi --}}
                        <div>
                            <input type="date" 
                                   name="tanggal" 
                                   value="{{ request('tanggal') }}"
                                   class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] px-3 py-2.5 font-mono-tech text-xs font-bold text-[#1A1D2B] focus:outline-none cursor-pointer"
                                   title="Pilih Tanggal Transaksi">
                        </div>

                    </div>

                    {{-- Baris 2: Filter Berdasarkan Status (Pills Tab ala Tokopedia) & Tombol Reset --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t-2 border-[#1A1D2B]/15">
                        
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-mono-tech text-xs font-extrabold text-[#1A1D2B] uppercase mr-1">Status:</span>

                            @php
                                $currentStatus = request('status', 'semua');
                            @endphp

                            <a href="{{ route('user.riwayat-peminjamans.index', array_merge(request()->except(['status', 'page']), ['status' => 'semua'])) }}"
                               class="tactile-btn rounded-xl border-2 border-[#1A1D2B] px-3 py-1 font-mono-tech text-xs font-bold transition {{ $currentStatus == 'semua' ? 'bg-[#1A1D2B] text-white hard-shadow-sm' : 'bg-white text-[#1A1D2B] hover:bg-[#F2E7C9]' }}">
                                Semua
                            </a>

                            <a href="{{ route('user.riwayat-peminjamans.index', array_merge(request()->except(['status', 'page']), ['status' => 'Menunggu'])) }}"
                               class="tactile-btn rounded-xl border-2 border-[#1A1D2B] px-3 py-1 font-mono-tech text-xs font-bold transition {{ $currentStatus == 'Menunggu' ? 'bg-[#237DE1] text-white hard-shadow-sm' : 'bg-white text-[#1A1D2B] hover:bg-[#F2E7C9]' }}">
                                Peminjaman Diproses
                            </a>

                            <a href="{{ route('user.riwayat-peminjamans.index', array_merge(request()->except(['status', 'page']), ['status' => 'Disetujui'])) }}"
                               class="tactile-btn rounded-xl border-2 border-[#1A1D2B] px-3 py-1 font-mono-tech text-xs font-bold transition {{ $currentStatus == 'Disetujui' ? 'bg-emerald-300 text-emerald-950 hard-shadow-sm' : 'bg-white text-[#1A1D2B] hover:bg-[#F2E7C9]' }}">
                                Berhasil / Disetujui
                            </a>

                            <a href="{{ route('user.riwayat-peminjamans.index', array_merge(request()->except(['status', 'page']), ['status' => 'Ditolak'])) }}"
                               class="tactile-btn rounded-xl border-2 border-[#1A1D2B] px-3 py-1 font-mono-tech text-xs font-bold transition {{ $currentStatus == 'Ditolak' ? 'bg-[#EC5928] text-white hard-shadow-sm' : 'bg-white text-[#1A1D2B] hover:bg-[#F2E7C9]' }}">
                                Peminjaman Ditolak
                            </a>
                        </div>

                        {{-- Tombol Reset Filter & Submit --}}
                        <div class="flex items-center gap-2">
                            <a href="{{ route('user.riwayat-peminjamans.index') }}" 
                               class="font-mono-tech text-xs font-extrabold text-[#EC5928] hover:underline">
                                Reset Filter
                            </a>
                            <button type="submit" 
                                    class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-1.5 font-mono-tech text-xs font-bold text-white hard-shadow-sm hover:bg-[#1d69bf]">
                                TERAPKAN
                            </button>
                        </div>

                    </div>

                </form>

            </div>

            {{-- DAFTAR KARTU TRANSAKSI PEMINJAMAN --}}
            <div class="space-y-4">
                @forelse ($peminjamans as $peminjaman)
                    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white hard-shadow overflow-hidden transition hover:-translate-y-0.5">
                        
                        {{-- KEPALA KARTU --}}
                        <div class="bg-[#F8F5EE] border-b-2 border-[#1A1D2B] px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex flex-wrap items-center gap-2.5 font-mono-tech text-xs">
                                <span class="rounded-lg border-2 border-[#1A1D2B] bg-white px-2.5 py-1 font-extrabold text-[#1A1D2B] hard-shadow-sm">
                                    #PMJ-{{ str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="text-[#5C6475] font-bold">
                                    Mulai: <strong class="text-[#1A1D2B]">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</strong>
                                </span>
                                <span class="hidden sm:inline text-[#1A1D2B]/30">|</span>
                                <span class="text-[#5C6475] font-bold">
                                    Tempo: <strong class="text-[#1A1D2B]">{{ $peminjaman->tanggal_rencana_kembali->format('d M Y') }}</strong>
                                </span>
                            </div>

                            @php
                                $adaPengembalianMenunggu = $peminjaman->pengembalians->contains('status', 'Menunggu');
                                
                                // Cek pengembalian terakhir yang ditolak untuk cooldown 30 menit
                                $pengembalianDitolakTerakhir = $peminjaman->pengembalians->where('status', 'Ditolak')->sortByDesc('updated_at')->first();
                                
                                $bolehAjukanLagi = true;
                                $sisaWaktuMenit = 0;

                                if ($pengembalianDitolakTerakhir) {
                                    $waktuDitolak = $pengembalianDitolakTerakhir->updated_at;
                                    $selisihMenit = now()->diffInMinutes($waktuDitolak, false);
                                    
                                    if ($selisihMenit > -30) {
                                        $bolehAjukanLagi = false;
                                        $sisaWaktuMenit = abs(30 + $selisihMenit);
                                    }
                                }

                                if ($adaPengembalianMenunggu) {
                                    $statusLabel = 'Pengembalian Diproses';
                                    $statusStyle = 'bg-[#F2E7C9] text-[#1A1D2B]';

                                } elseif ($peminjaman->status === 'Dikembalikan') {
                                    $statusLabel = 'Dikembalikan';
                                    $statusStyle = 'bg-emerald-300 text-emerald-950';

                                } elseif ($pengembalianDitolakTerakhir && !$bolehAjukanLagi) {
                                    $statusLabel = 'Pengembalian Ditolak';
                                    $statusStyle = 'bg-[#EC5928] text-white';

                                } else {
                                    $statusLabel = match ($peminjaman->status) {
                                        'Menunggu' => 'Peminjaman Diproses',
                                        'Disetujui' => 'Disetujui',
                                        'Ditolak' => 'Peminjaman Ditolak',
                                        default => $peminjaman->status,
                                    };

                                    $statusStyle = match ($peminjaman->status) {
                                        'Menunggu' => 'bg-[#F2E7C9] text-[#1A1D2B]',
                                        'Disetujui' => 'bg-[#237DE1] text-white',
                                        'Ditolak' => 'bg-[#EC5928] text-white',
                                        default => 'bg-gray-200 text-[#1A1D2B]',
                                    };
                                }
                            @endphp
                            <span class="rounded-lg border-2 border-[#1A1D2B] px-3 py-1 font-mono-tech text-[10.5px] font-extrabold uppercase tracking-wider hard-shadow-sm {{ $statusStyle }}">
                                ● {{ $statusLabel }}
                            </span>
                        </div>

                        {{-- ISI KARTU --}}
                        <div class="p-4 sm:p-6 space-y-4">

                            {{-- ALERT KETERLAMBATAN --}}
                            @if ($peminjaman->hari_terlambat > 0)
                                <div class="rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] p-4 text-white hard-shadow-sm">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-[#F2E7C9] font-mono-tech text-sm font-extrabold text-[#1A1D2B]">
                                            ⚠
                                        </div>
                                        <div>
                                            <p class="font-mono-tech text-xs font-extrabold uppercase tracking-wide">
                                                PERINGATAN KETERLAMBATAN PENGEMBALIAN!
                                            </p>
                                            <p class="text-xs sm:text-[13px] font-medium mt-1 leading-relaxed">
                                                Batas waktu pinjam telah terlewati selama 
                                                <span class="inline-block rounded border border-[#1A1D2B] bg-white px-1.5 py-0.2 font-mono-tech font-extrabold text-[#EC5928]">
                                                    {{ $peminjaman->hari_terlambat }} HARI
                                                </span>. Segera kembalikan alat kepada petugas.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- ALERT COOLDOWN PENGEMBALIAN DITOLAK --}}
                            @if ($pengembalianDitolakTerakhir && !$bolehAjukanLagi)
                                <div class="rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928]/15 p-3.5 hard-shadow-sm">
                                    <div class="flex items-center gap-3 font-mono-tech text-xs font-bold text-[#1A1D2B]">
                                        <span>⚠️</span>
                                        <p>Pengajuan pengembalian ditolak. Ajukan kembali dalam <strong>{{ ceil($sisaWaktuMenit) }} menit</strong>.</p>
                                    </div>
                                </div>
                            @endif

                            {{-- DAFTAR ITEM ALAT --}}
                            <div class="space-y-2">
                                <p class="font-mono-tech text-[10.5px] font-bold uppercase text-[#5C6475]">
                                    // DETAIL ITEM ALAT:
                                </p>

                                <div class="grid grid-cols-1 gap-2.5">
                                    @foreach ($peminjaman->detailPeminjaman as $detail)
                                        <div class="flex items-center gap-3.5 rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] p-3">
                                            <div class="h-14 w-14 shrink-0 rounded-xl border-2 border-[#1A1D2B] bg-white overflow-hidden flex items-center justify-center">
                                                @if ($detail->alat && $detail->alat->gambar)
                                                    <img src="{{ asset('storage/' . $detail->alat->gambar) }}"
                                                         alt="{{ $detail->alat->nama_alat }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <span class="font-mono-tech text-[9px] font-bold text-[#5C6475]">UNIT</span>
                                                @endif
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <p class="font-extrabold text-[13px] sm:text-[14px] text-[#1A1D2B] truncate">
                                                    {{ $detail->alat->nama_alat ?? 'Alat tidak ditemukan' }}
                                                </p>
                                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                                    <span class="rounded border border-[#1A1D2B] bg-[#F2E7C9] px-2 py-0.5 font-mono-tech text-[10px] font-extrabold text-[#1A1D2B]">
                                                        {{ $detail->jumlah }} UNIT
                                                    </span>
                                                    <span class="font-mono-tech text-[10.5px] text-[#5C6475]">
                                                        Kode: {{ $detail->alat->kode_alat ?? '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- FOOTER KARTU --}}
                            <div class="border-t-2 border-dashed border-[#1A1D2B]/20 pt-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <p class="font-mono-tech text-[11px] text-[#5C6475]">
                                    Keperluan: <strong class="text-[#1A1D2B]">{{ $peminjaman->keterangan ?: 'Tidak ada keterangan khusus' }}</strong>
                                </p>

                                <a href="{{ route('user.riwayat-peminjamans.show', $peminjaman->id) }}"
                                   class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-5 py-2.5 font-mono-tech text-xs font-bold text-white hard-shadow-sm hover:bg-[#1d69bf] shrink-0">
                                    <span>LIHAT DETAIL TRANSAKSI</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    {{-- EMPTY STATE --}}
                    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-12 sm:p-16 text-center hard-shadow">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border-2 border-[#1A1D2B] bg-[#F2E7C9] font-mono-tech text-xl font-extrabold text-[#1A1D2B] hard-shadow-sm mb-4">
                            ∅
                        </div>
                        <span class="font-mono-tech text-[10px] font-bold uppercase tracking-widest text-[#EC5928] block mb-1">
                            // KOSONG
                        </span>
                        <h3 class="text-base sm:text-lg font-extrabold text-[#1A1D2B] uppercase">
                            Tidak Ada Transaksi Sesuai Filter
                        </h3>
                        <p class="text-xs sm:text-[13px] font-medium text-[#5C6475] max-w-md mx-auto mt-1.5 mb-6">
                            Pencarian atau status filter yang Anda pilih tidak cocok dengan data riwayat peminjaman apa pun.
                        </p>

                        <a href="{{ route('user.riwayat-peminjamans.index') }}"
                           class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-6 py-3 font-mono-tech text-xs font-bold text-white hard-shadow-sm hover:bg-[#1d69bf]">
                            <span>RESET FILTER PENCARIAN</span>
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if ($peminjamans->hasPages())
                <div class="pt-4">
                    {{ $peminjamans->appends(request()->all())->links() }}
                </div>
            @endif

        </div>

    </div>

</div>

@endsection