@extends('layouts.petugas')

@section('title', 'Detail Pengembalian')
@section('header', 'Detail Verifikasi Pengembalian Alat')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto">

    {{-- BARIS ATAS: KEMBALI & BADGE NOMOR TRANSAKSI --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <a href="{{ route('petugas.pengembalians.index') }}"
           class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2 font-mono-tech text-xs font-bold text-[#1A1D2B] hard-shadow-sm hover:bg-[#F2E7C9] transition w-fit">
            <span>&larr;</span>
            <span>KEMBALI KE DAFTAR PENGEMBALIAN</span>
        </a>

        <div class="font-mono-tech inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] px-4 py-2 text-xs font-bold text-[#1A1D2B] hard-shadow-sm">
            <span>TRANSAKSI PEMINJAMAN #PMJ-{{ str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
    </div>

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

    @if ($errors->any())
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-rose-100 p-5 hard-shadow text-[#1A1D2B]">
            <div class="flex items-center gap-2.5 mb-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-rose-300 font-mono-tech font-bold text-xs">
                    !
                </div>
                <p class="font-mono-tech text-xs font-extrabold uppercase tracking-tight text-rose-950">
                    Terjadi kesalahan input pada verifikasi:
                </p>
            </div>
            <ul class="list-disc list-inside font-mono-tech text-[11.5px] font-bold text-rose-900 space-y-1 pl-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- GRID UTAMA 2 KOLOM (KIRI: VERIFIKASI & ANTRIAN PENGAJUAN | KANAN: STATISTIK & RINGKASAN) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- KOLOM KIRI: DAFTAR PERSETUJUAN / ANTRIAN PENGAJUAN MASUK (Span 7) --}}
        <div class="lg:col-span-7 space-y-6">

            <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-6 hard-shadow space-y-4">
                <div class="border-b-2 border-[#1A1D2B]/15 pb-3">
                    <span class="font-mono-tech text-xs font-extrabold uppercase text-[#237DE1]">
                        // 01. ANTRIAN VERIFIKASI
                    </span>
                    <h3 class="text-base sm:text-lg font-extrabold text-[#1A1D2B] uppercase tracking-tight">
                        Persetujuan Pengembalian Alat
                    </h3>
                    <p class="text-xs font-medium text-[#5C6475] mt-0.5">
                        Periksa rincian unit dan bukti foto dari peminjam sebelum menyetujui atau menolak.
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse ($peminjaman->pengembalians->sortByDesc('id') as $pengembalian)
                        @php
                            $statusStyle = match ($pengembalian->status) {
                                'Menunggu'  => 'bg-[#F2E7C9] text-[#1A1D2B]',
                                'Disetujui' => 'bg-emerald-300 text-emerald-950',
                                'Ditolak'   => 'bg-[#EC5928] text-white',
                                default     => 'bg-gray-200 text-[#1A1D2B]',
                            };
                        @endphp

                        <div class="rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] p-5 space-y-4 hard-shadow-sm">
                            
                            {{-- Header Pengajuan --}}
                            <div class="flex flex-wrap items-center justify-between gap-2 border-b-2 border-[#1A1D2B]/15 pb-3">
                                <div class="font-mono-tech text-xs">
                                    <span class="font-extrabold text-[#1A1D2B]">Pengajuan #PBK-{{ $pengembalian->id }}</span>
                                    <span class="text-[#5C6475] block sm:inline sm:ml-2">Tgl: {{ $pengembalian->tanggal_pengembalian->format('d M Y') }}</span>
                                </div>
                                <span class="rounded-lg border-2 border-[#1A1D2B] px-3 py-1 font-mono-tech text-[10px] font-extrabold uppercase hard-shadow-sm {{ $statusStyle }}">
                                    ● {{ $pengembalian->status }}
                                </span>
                            </div>

                            {{-- Item Diajukan --}}
                            <div class="space-y-3">
                                @foreach ($pengembalian->detailPengembalian as $item)
                                    <div class="rounded-xl border-2 border-[#1A1D2B] bg-white p-3.5 space-y-3">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                            <div>
                                                <h4 class="font-extrabold text-sm text-[#1A1D2B]">
                                                    {{ $item->detailPeminjaman->alat->nama_alat ?? 'Alat tidak ditemukan' }}
                                                </h4>
                                                <p class="font-mono-tech text-[10.5px] font-bold text-[#5C6475]">
                                                    Kode: {{ $item->detailPeminjaman->alat->kode_alat ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="flex items-center gap-2 font-mono-tech text-xs">
                                                <span class="rounded-lg border border-[#1A1D2B] bg-[#F2E7C9] px-2 py-0.5 font-extrabold text-[#1A1D2B]">
                                                    {{ $item->jumlah_dikembalikan }} Unit
                                                </span>
                                                <span class="rounded-lg border border-[#1A1D2B] bg-blue-100 px-2.5 py-0.5 font-extrabold text-[#237DE1]">
                                                    {{ $item->kondisi_alat }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Foto Bukti --}}
                                        @if ($item->gambar_bukti)
                                            <div class="border-t-2 border-dashed border-[#1A1D2B]/20 pt-3">
                                                <p class="font-mono-tech text-[10px] font-bold text-[#5C6475] mb-2">// FOTO BUKTI FISIK:</p>
                                                <a href="{{ asset('storage/' . $item->gambar_bukti) }}" target="_blank" class="block bg-gray-50 rounded-xl border-2 border-[#1A1D2B] overflow-hidden p-1.5 hover:bg-gray-100 transition">
                                                    <img src="{{ asset('storage/' . $item->gambar_bukti) }}" alt="Bukti" class="w-full max-h-56 object-contain rounded-lg mx-auto">
                                                </a>
                                                <p class="font-mono-tech text-[9.5px] text-center text-[#5C6475] mt-1 italic">Klik gambar untuk memperbesar</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            {{-- Catatan Peminjam --}}
                            @if ($pengembalian->catatan)
                                <div class="rounded-xl border border-[#1A1D2B]/30 bg-white p-3 font-mono-tech text-xs">
                                    <span class="text-[#5C6475] font-bold block mb-0.5">Catatan Peminjam:</span>
                                    <p class="text-[#1A1D2B] font-medium">{{ $pengembalian->catatan }}</p>
                                </div>
                            @endif

                            {{-- Info Petugas --}}
                            @if ($pengembalian->status !== 'Menunggu')
                                <p class="font-mono-tech text-[11px] text-[#5C6475]">
                                    Diproses oleh: <strong class="text-[#1A1D2B]">{{ $pengembalian->petugas->name ?? '-' }}</strong>
                                </p>
                            @endif

                            {{-- Tombol Aksi Verifikasi (Setuju / Tolak) --}}
                            @if ($pengembalian->status === 'Menunggu')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t-2 border-dashed border-[#1A1D2B]/20">
                                    <form method="POST" action="{{ route('petugas.pengembalians.update-status', $pengembalian) }}" onsubmit="return confirm('Yakin setujui pengembalian ini? Stok alat kondisi Baik akan dipulihkan.')">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="Disetujui">
                                        <button type="submit" class="tactile-btn w-full rounded-xl border-2 border-[#1A1D2B] bg-emerald-400 py-2.5 px-4 font-mono-tech text-xs font-extrabold text-emerald-950 hard-shadow-sm hover:bg-emerald-500">
                                            ✓ SETUJUI
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('petugas.pengembalians.update-status', $pengembalian) }}" onsubmit="return confirm('Yakin ingin menolak pengajuan ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="Ditolak">
                                        <button type="submit" class="tactile-btn w-full rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] py-2.5 px-4 font-mono-tech text-xs font-extrabold text-white hard-shadow-sm hover:bg-[#d94c20]">
                                            ✕ TOLAK
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    @empty
                        <div class="rounded-xl border-2 border-dashed border-[#1A1D2B]/30 p-8 text-center">
                            <p class="font-mono-tech text-xs font-bold text-[#5C6475]">
                                Belum ada pengajuan pengembalian masuk.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN: IDENTITAS PEMINJAM & STATISTIK UNIT (Span 5) --}}
        <div class="lg:col-span-5 space-y-6">

            {{-- Kartu Identitas Peminjam --}}
            <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-6 hard-shadow space-y-4">
                <div class="border-b-2 border-[#1A1D2B]/15 pb-3">
                    <span class="font-mono-tech text-xs font-extrabold uppercase text-[#237DE1]">
                        // 02. PROFIL PEMINJAM
                    </span>
                    <h3 class="text-base font-extrabold text-[#1A1D2B] uppercase tracking-tight">
                        Identitas Peminjaman
                    </h3>
                </div>

                <div class="space-y-3 font-mono-tech text-xs">
                    <div class="flex items-center justify-between pb-2 border-b border-[#1A1D2B]/10">
                        <span class="text-[#5C6475]">Nama Peminjam:</span>
                        <strong class="text-[#1A1D2B]">{{ $peminjaman->peminjam->name ?? '-' }}</strong>
                    </div>
                    <div class="flex items-center justify-between pb-2 border-b border-[#1A1D2B]/10">
                        <span class="text-[#5C6475]">Tanggal Pinjam:</span>
                        <strong class="text-[#1A1D2B]">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[#5C6475]">Rencana Kembali:</span>
                        <strong class="text-[#EC5928]">{{ $peminjaman->tanggal_rencana_kembali->format('d M Y') }}</strong>
                    </div>
                </div>
            </div>

            {{-- Hitung Statistik Unit --}}
            @php
                $totalDipinjam = 0;
                $totalDikembalikan = 0;

                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $totalDipinjam += $detail->jumlah;
                    $totalDikembalikan += $detail->detailPengembalians
                        ->filter(fn ($riwayat) => $riwayat->pengembalian?->status === 'Disetujui')
                        ->sum('jumlah_dikembalikan');
                }

                $totalSisa = $totalDipinjam - $totalDikembalikan;
            @endphp

            {{-- Kartu Statistik Unit --}}
            <div class="rounded-2xl border-2 border-[#1A1D2B] bg-[#F2E7C9] p-6 hard-shadow space-y-4">
                <span class="font-mono-tech text-xs font-extrabold uppercase text-[#EC5928]">
                    // 03. REKAPITULASI UNIT
                </span>
                
                <div class="grid grid-cols-3 gap-3 text-center font-mono-tech">
                    <div class="rounded-xl border-2 border-[#1A1D2B] bg-white p-3 hard-shadow-sm">
                        <span class="text-[9.5px] font-bold text-[#5C6475] uppercase block">Total</span>
                        <span class="text-base font-extrabold text-[#1A1D2B]">{{ $totalDipinjam }}</span>
                    </div>

                    <div class="rounded-xl border-2 border-[#1A1D2B] bg-emerald-300 p-3 hard-shadow-sm">
                        <span class="text-[9.5px] font-bold text-emerald-950 uppercase block">Kembali</span>
                        <span class="text-base font-extrabold text-emerald-950">{{ $totalDikembalikan }}</span>
                    </div>

                    <div class="rounded-xl border-2 border-[#1A1D2B] bg-rose-300 p-3 hard-shadow-sm">
                        <span class="text-[9.5px] font-bold text-rose-950 uppercase block">Sisa</span>
                        <span class="text-base font-extrabold text-rose-950">{{ $totalSisa }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- BAGIAN BAWAH: RINCIAN ALAT & LOG RIWAYAT MASUK --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-6 sm:p-8 hard-shadow space-y-6">
        <div class="border-b-2 border-[#1A1D2B]/15 pb-4">
            <span class="font-mono-tech text-xs font-extrabold uppercase text-[#237DE1]">
                // 04. RIWAYAT INVENTARIS
            </span>
            <h3 class="text-lg font-extrabold text-[#1A1D2B] uppercase tracking-tight">
                Rincian Item Alat & Log Masuk
            </h3>
        </div>

        <div class="space-y-6">
            @foreach ($peminjaman->detailPeminjaman as $detail)
                @php
                    $sudahDikembalikan = $detail->detailPengembalians
                        ->filter(fn ($riwayat) => $riwayat->pengembalian?->status === 'Disetujui')
                        ->sum('jumlah_dikembalikan');

                    $sisa = $detail->jumlah - $sudahDikembalikan;
                @endphp

                <div class="rounded-2xl border-2 border-[#1A1D2B] bg-[#F8F5EE] p-5 space-y-4">
                    
                    {{-- Header Alat --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b-2 border-[#1A1D2B]/15 pb-3">
                        <div>
                            <h4 class="font-extrabold text-base text-[#1A1D2B]">
                                {{ $detail->alat->nama_alat ?? 'Alat tidak ditemukan' }}
                            </h4>
                            <p class="font-mono-tech text-xs font-bold text-[#5C6475]">
                                Kode: <span class="text-[#1A1D2B]">{{ $detail->alat->kode_alat ?? '-' }}</span>
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2 font-mono-tech text-xs font-bold">
                            <span class="rounded-lg border border-[#1A1D2B] bg-white px-3 py-1">
                                Dipinjam: {{ $detail->jumlah }}
                            </span>
                            <span class="rounded-lg border border-[#1A1D2B] bg-emerald-200 px-3 py-1 text-emerald-950">
                                Kembali: {{ $sudahDikembalikan }}
                            </span>
                            <span class="rounded-lg border border-[#1A1D2B] bg-rose-200 px-3 py-1 text-rose-950">
                                Sisa: {{ $sisa }}
                            </span>
                        </div>
                    </div>

                    {{-- Log Riwayat Masuk Pengembalian Item Ini --}}
                    @if ($detail->detailPengembalians->isNotEmpty())
                        <div class="space-y-3 pt-1">
                            <p class="font-mono-tech text-xs font-bold uppercase text-[#5C6475]">
                                // Log Riwayat Masuk Terverifikasi:
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($detail->detailPengembalians as $riwayat)
                                    <div class="rounded-xl border-2 border-[#1A1D2B] bg-white p-4 hard-shadow-sm space-y-3">
                                        <div class="flex items-center justify-between font-mono-tech text-xs">
                                            <span class="rounded-lg border border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-1 font-extrabold text-[#1A1D2B]">
                                                {{ $riwayat->jumlah_dikembalikan }} Unit
                                            </span>
                                            <span class="font-bold text-[#237DE1]">
                                                Kondisi: {{ $riwayat->kondisi_alat }}
                                            </span>
                                        </div>

                                        <p class="font-mono-tech text-[11px] text-[#5C6475]">
                                            Tanggal: {{ $riwayat->pengembalian->tanggal_pengembalian->format('d M Y') }}<br>
                                            Petugas: <strong class="text-[#1A1D2B]">{{ $riwayat->pengembalian->petugas->name ?? '-' }}</strong>
                                        </p>

                                        @if ($riwayat->gambar_bukti)
                                            <div class="border-t-2 border-dashed border-[#1A1D2B]/20 pt-2.5">
                                                <a href="{{ asset('storage/' . $riwayat->gambar_bukti) }}" target="_blank" class="block bg-gray-50 rounded-xl border-2 border-[#1A1D2B] overflow-hidden p-1 hover:bg-gray-100 transition">
                                                    <img src="{{ asset('storage/' . $riwayat->gambar_bukti) }}" alt="Bukti" class="w-full max-h-48 object-contain rounded-lg mx-auto">
                                                </a>
                                                <p class="font-mono-tech text-[9.5px] text-center text-[#5C6475] mt-1 italic">Klik gambar untuk memperbesar</p>
                                            </div>
                                        @else
                                            <p class="font-mono-tech text-[11px] text-[#5C6475] italic">Tanpa foto bukti lampiran.</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="font-mono-tech text-xs text-[#5C6475] italic">
                            Belum ada riwayat pengembalian tercatat untuk alat ini.
                        </p>
                    @endif

                </div>
            @endforeach
        </div>
    </div>

</div>

@endsection