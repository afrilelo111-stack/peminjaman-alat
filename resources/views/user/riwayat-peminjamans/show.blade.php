@extends('layouts.user')

@section('title', 'Detail Transaksi Peminjaman')
@section('header', 'Rincian Transaksi Peminjaman')

@section('content')

<div class="space-y-6 max-w-5xl mx-auto">

    {{-- FLASH MESSAGES --}}
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

    {{-- TOMBOL KEMBALI --}}
    <div>
        <a href="{{ route('user.riwayat-peminjamans.index') }}"
           class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2 font-mono-tech text-xs font-bold text-[#1A1D2B] hard-shadow-sm hover:bg-[#F2E7C9] transition">
            <span>&larr;</span>
            <span>KEMBALI KE DAFTAR TRANSAKSI</span>
        </a>
    </div>

    {{-- KARTU UTAMA INFORMASI TRANSAKSI --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white hard-shadow overflow-hidden">
        
        {{-- KEPALA KARTU: KODE TRANSAKSI & STATUS --}}
        <div class="bg-[#F2E7C9] border-b-2 border-[#1A1D2B] px-6 py-4 flex flex-wrap items-center justify-between gap-3">
            <div class="space-y-0.5">
                <span class="font-mono-tech text-[10px] font-extrabold uppercase tracking-widest text-[#EC5928] block">
                    // DETAIL INVENTARIS RESMI
                </span>
                <h2 class="text-base sm:text-lg font-extrabold text-[#1A1D2B] uppercase tracking-tight">
                    Transaksi #PMJ-{{ str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT) }}
                </h2>
            </div>

            @php
                $adaPengembalianMenunggu = $peminjaman->pengembalians->contains('status', 'Menunggu');
                
                // Ambil pengembalian terakhir yang ditolak (jika ada)
                $pengembalianDitolakTerakhir = $peminjaman->pengembalians->where('status', 'Ditolak')->sortByDesc('updated_at')->first();
                
                $bolehAjukanLagi = true;
                $sisaWaktuMenit = 0;

                if ($pengembalianDitolakTerakhir) {
                    $waktuDitolak = $pengembalianDitolakTerakhir->updated_at;
                    $selisihMenit = now()->diffInMinutes($waktuDitolak, false); // bernilai negatif jika sudah lewat
                    
                    if ($selisihMenit > -30) {
                        $bolehAjukanLagi = false;
                        $sisaWaktuMenit = abs(30 + $selisihMenit); // sisa waktu menuju 30 menit
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
                        'Menunggu'     => 'Peminjaman Diproses',
                        'Disetujui'    => 'Disetujui',
                        'Ditolak'      => 'Peminjaman Ditolak',
                        default        => $peminjaman->status,
                    };

                    $statusStyle = match ($peminjaman->status) {
                        'Menunggu'     => 'bg-[#F2E7C9] text-[#1A1D2B]',
                        'Disetujui'    => 'bg-[#237DE1] text-white',
                        'Ditolak'      => 'bg-[#EC5928] text-white',
                        default        => 'bg-gray-200 text-[#1A1D2B]',
                    };
                }
            @endphp

            <span class="rounded-lg border-2 border-[#1A1D2B] px-3 py-1.5 font-mono-tech text-xs font-extrabold uppercase tracking-wider hard-shadow-sm {{ $statusStyle }}">
                ● {{ $statusLabel }}
            </span>
        </div>

        {{-- KONTEN INFORMASI JADWAL & KETERANGAN --}}
        <div class="p-6 space-y-6">

            @if ($adaPengembalianMenunggu)
                <div class="rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] p-4 hard-shadow-sm">
                    <div class="flex items-center gap-3 font-mono-tech text-xs font-bold text-[#1A1D2B]">
                        <span>⏳</span>
                        <p>Pengajuan pengembalian Anda sedang ditinjau dan diproses oleh petugas inventaris.</p>
                    </div>
                </div>
            @elseif ($pengembalianDitolakTerakhir && !$bolehAjukanLagi)
                <div class="rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928]/15 p-4 hard-shadow-sm">
                    <div class="flex items-center gap-3 font-mono-tech text-xs font-bold text-[#1A1D2B]">
                        <span>⚠️</span>
                        <p>Pengajuan pengembalian sebelumnya ditolak oleh petugas. Anda dapat mengajukan kembali dalam waktu sekitar <strong>{{ ceil($sisaWaktuMenit) }} menit ke depan</strong>.</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 font-mono-tech">
                <div class="rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] p-4 space-y-1">
                    <span class="text-[10.5px] font-bold text-[#5C6475] uppercase block">// TANGGAL MULAI PEMINJAMAN</span>
                    <span class="text-sm font-extrabold text-[#1A1D2B]">
                        {{ $peminjaman->tanggal_pinjam->format('d F Y') }}
                    </span>
                </div>

                <div class="rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] p-4 space-y-1">
                    <span class="text-[10.5px] font-bold text-[#5C6475] uppercase block">// RENCANA TANGGAL KEMBALI</span>
                    <span class="text-sm font-extrabold text-[#1A1D2B]">
                        {{ $peminjaman->tanggal_rencana_kembali->format('d F Y') }}
                    </span>
                </div>
            </div>

            <div class="rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] p-4 space-y-1 font-mono-tech">
                <span class="text-[10.5px] font-bold text-[#5C6475] uppercase block">// KETERANGAN / TUJUAN PEMINJAMAN</span>
                <p class="text-xs sm:text-[13px] font-medium text-[#1A1D2B] leading-relaxed">
                    {{ $peminjaman->keterangan ?: 'Tidak ada keterangan khusus yang dicantumkan.' }}
                </p>
            </div>

            {{-- DAFTAR ALAT YANG DIPINJAM --}}
            <div class="space-y-4 pt-2">
                <div class="flex items-center justify-between border-b-2 border-[#1A1D2B]/15 pb-2">
                    <h3 class="font-mono-tech text-xs font-extrabold uppercase text-[#1A1D2B]">
                        // RINCIAN ITEM ALAT
                    </h3>
                    <span class="font-mono-tech text-[10px] font-bold text-[#5C6475]">
                        Total Jenis: {{ $peminjaman->detailPeminjaman->count() }} Alat
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    @foreach ($peminjaman->detailPeminjaman as $detail)
                        @php
                            $sisa = $sisaPerDetail[$detail->id] ?? 0;
                            $dikembalikan = $detail->jumlah - $sisa;
                        @endphp

                        <div class="rounded-xl border-2 border-[#1A1D2B] bg-white p-4 hard-shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                            
                            {{-- Info Alat & Foto --}}
                            <div class="flex items-center gap-4">
                                <div class="h-14 w-14 shrink-0 rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9]/40 overflow-hidden flex items-center justify-center">
                                    @if ($detail->alat && $detail->alat->gambar)
                                        <img src="{{ asset('storage/' . $detail->alat->gambar) }}" alt="Alat" class="w-full h-full object-cover">
                                    @else
                                        <span class="font-mono-tech text-[10px] font-bold text-[#5C6475]">UNIT</span>
                                    @endif
                                </div>
                                <div class="space-y-1">
                                    <h4 class="font-extrabold text-sm sm:text-base text-[#1A1D2B]">
                                        {{ $detail->alat->nama_alat ?? 'Alat tidak ditemukan' }}
                                    </h4>
                                    <p class="font-mono-tech text-[11px] font-bold text-[#5C6475]">
                                        Kode Inventaris: <span class="text-[#1A1D2B]">{{ $detail->alat->kode_alat ?? '-' }}</span>
                                    </p>
                                </div>
                            </div>

                            {{-- Statistik Unit --}}
                            <div class="grid grid-cols-3 gap-2 text-center font-mono-tech shrink-0 pt-2 md:pt-0 border-t-2 border-dashed border-[#1A1D2B]/15 md:border-t-0">
                                <div class="rounded-lg border border-[#1A1D2B] bg-[#F8F5EE] p-2">
                                    <span class="text-[9px] font-bold text-[#5C6475] uppercase block">Dipinjam</span>
                                    <span class="text-xs font-extrabold text-[#237DE1]">{{ $detail->jumlah }}</span>
                                </div>
                                <div class="rounded-lg border border-[#1A1D2B] bg-emerald-50 p-2">
                                    <span class="text-[9px] font-bold text-emerald-800 uppercase block">Kembali</span>
                                    <span class="text-xs font-extrabold text-emerald-700">{{ $dikembalikan }}</span>
                                </div>
                                <div class="rounded-lg border border-[#1A1D2B] bg-rose-50 p-2">
                                    <span class="text-[9px] font-bold text-rose-800 uppercase block">Sisa</span>
                                    <span class="text-xs font-extrabold text-[#EC5928]">{{ $sisa }}</span>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TOMBOL AJUKAN PENGEMBALIAN DENGAN PEMBATASAN WAKTU 30 MENIT --}}
            @if ($peminjaman->status === 'Disetujui' && array_sum($sisaPerDetail) > 0)
                @if ($adaPengembalianMenunggu)
                    <div class="border-t-2 border-dashed border-[#1A1D2B]/20 pt-6 text-center">
                        <p class="font-mono-tech text-xs font-bold text-[#5C6475] italic">
                            Pengajuan pengembalian sedang diproses oleh petugas. Tombol pengajuan baru akan aktif setelah verifikasi selesai.
                        </p>
                    </div>
                @elseif (!$bolehAjukanLagi)
                    <div class="border-t-2 border-dashed border-[#1A1D2B]/20 pt-6 text-center">
                        <p class="font-mono-tech text-xs font-bold text-[#EC5928] italic">
                            Pengajuan sebelumnya ditolak. Tombol ajukan pengembalian akan aktif kembali setelah jeda 30 menit.
                        </p>
                    </div>
                @else
                    <div class="border-t-2 border-dashed border-[#1A1D2B]/20 pt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <p class="font-mono-tech text-xs font-extrabold text-[#1A1D2B]">
                                PENGEMBALIAN INVENTARIS
                            </p>
                            <p class="text-xs font-medium text-[#5C6475] mt-0.5">
                                Ada unit alat yang belum dikembalikan ke petugas lab/inventaris.
                            </p>
                        </div>

                        <a href="{{ route('user.riwayat-peminjamans.create', $peminjaman->id) }}"
                           class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-6 py-3 font-mono-tech text-xs font-bold text-white hard-shadow hover:bg-[#1d69bf] shrink-0">
                            <span>AJUKAN PENGEMBALIAN ALAT</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                @endif
            @endif

        </div>

    </div>

</div>

@endsection