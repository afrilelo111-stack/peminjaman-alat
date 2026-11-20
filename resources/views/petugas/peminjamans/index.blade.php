@extends('layouts.petugas')

@section('title', 'Data Peminjaman')
@section('header', 'Data Peminjaman Alat')

@section('content')

<div class="space-y-6">

    {{-- KONTROL FILTER & PENCARIAN --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-5 sm:p-6 hard-shadow">
        <form action="{{ route('petugas.peminjamans.index') }}" method="GET" class="space-y-4">
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-12">
                
                {{-- Input Cari --}}
                <div class="sm:col-span-5">
                    <label for="search" class="block font-mono-tech text-[11px] font-bold uppercase tracking-widest text-[#5C6475] mb-2">
                        // Cari Peminjam
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
                               placeholder="Nama peminjam atau status..."
                               class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9]/30 pl-10 pr-3.5 py-2.5 text-[13px] font-medium text-[#1A1D2B] placeholder:text-[#5C6475] focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-all">
                    </div>
                </div>

                {{-- Input Tanggal --}}
                <div class="sm:col-span-4">
                    <label for="tanggal" class="block font-mono-tech text-[11px] font-bold uppercase tracking-widest text-[#5C6475] mb-2">
                        // Tanggal Peminjaman
                    </label>
                    <input type="date"
                           name="tanggal"
                           id="tanggal"
                           value="{{ request('tanggal') }}"
                           class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9]/30 px-3.5 py-2.5 text-[13px] font-medium text-[#1A1D2B] focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-all">
                </div>

                {{-- Tombol Aksi Filter --}}
                <div class="sm:col-span-3 flex items-end gap-2">
                    <button type="submit"
                            class="tactile-btn flex-1 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-2.5 text-[13px] font-bold text-white hard-shadow-sm hover:bg-[#1d69bf] text-center">
                        Filter
                    </button>
                    @if(request('search') || request('tanggal'))
                        <a href="{{ route('petugas.peminjamans.index') }}"
                           class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] px-3.5 py-2.5 text-[13px] font-bold text-white hard-shadow-sm text-center"
                           title="Reset Filter">
                            ✕
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- CARD UTAMA TABEL --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white hard-shadow overflow-hidden">
        
        {{-- HEADER BAR: Info Jumlah Data & Tombol Export --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b-2 border-[#1A1D2B] bg-[#F2E7C9]/60 px-5 py-4 gap-3">
            <div>
                <p class="font-mono-tech text-[11px] font-bold uppercase tracking-widest text-[#5C6475]">
                    Menampilkan <span class="text-[#1A1D2B] font-extrabold">{{ $peminjamans->firstItem() ?? 0 }}-{{ $peminjamans->lastItem() ?? 0 }}</span> dari <span class="text-[#1A1D2B] font-extrabold">{{ $peminjamans->total() }}</span> data
                </p>
            </div>

            <a href="{{ route('petugas.laporan.peminjaman', request()->only(['search', 'tanggal'])) }}"
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
                        <th class="w-[60px] border-r-2 border-[#1A1D2B] px-3 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest text-center">#</th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest">Peminjam</th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest">Tanggal Pinjam</th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest">Rencana Kembali</th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest text-center">Jumlah Alat</th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest">Status</th>
                        <th class="px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest text-center">Aksi & Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-[#1A1D2B]">
                    @forelse ($peminjamans as $index => $peminjaman)
                        <tr class="transition hover:bg-[#F2E7C9]/30">
                            
                            {{-- Nomor --}}
                            <td class="border-r-2 border-[#1A1D2B] px-3 py-4 text-center font-mono-tech text-[12px] font-bold text-[#5C6475]">
                                {{ str_pad($peminjamans->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                            </td>

                            {{-- Peminjam --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-[#237DE1] text-xs font-extrabold text-white">
                                        {{ strtoupper(substr($peminjaman->peminjam->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-extrabold text-[13px] text-[#1A1D2B]">
                                            {{ $peminjaman->peminjam->name ?? 'Pengguna Dihapus' }}
                                        </p>
                                        <p class="font-mono-tech text-[10.5px] text-[#5C6475]">
                                            ID: #{{ $peminjaman->id }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Tanggal Pinjam --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 font-mono-tech text-[12px] text-[#1A1D2B]">
                                {{ $peminjaman->tanggal_pinjam?->format('d/m/Y') ?? '-' }}
                            </td>

                            {{-- Rencana Kembali --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 font-mono-tech text-[12px] text-[#1A1D2B]">
                                {{ $peminjaman->tanggal_rencana_kembali?->format('d/m/Y') ?? '-' }}
                            </td>

                            {{-- Jumlah Unit --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 text-center font-mono-tech text-[12px] font-bold text-[#1A1D2B]">
                                <span class="rounded-md border border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-1">
                                    {{ $peminjaman->detailPeminjaman->sum('jumlah') }} Unit
                                </span>
                            </td>

                            {{-- Status Badge --}}
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4">
                                @php
                                    $statusConfig = [
                                        'Menunggu'     => 'bg-[#F2E7C9] text-[#1A1D2B]',
                                        'Disetujui'    => 'bg-emerald-300 text-emerald-950',
                                        'Ditolak'      => 'bg-rose-300 text-rose-950',
                                        'Dikembalikan' => 'bg-sky-300 text-sky-950',
                                    ];
                                    $badgeStyle = $statusConfig[$peminjaman->status] ?? 'bg-gray-200 text-gray-800';
                                @endphp
                                <span class="inline-block rounded-md border-2 border-[#1A1D2B] {{ $badgeStyle }} px-2.5 py-1 font-mono-tech text-[10px] font-bold uppercase tracking-wider hard-shadow-sm">
                                    {{ $peminjaman->status }}
                                </span>
                            </td>

                            {{-- Aksi & Form Ubah Status dengan Trigger Popup Modal --}}
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('petugas.peminjamans.show', $peminjaman->id) }}"
                                       class="tactile-btn inline-flex items-center gap-1 rounded-xl border-2 border-[#1A1D2B] bg-white px-3.5 py-1.5 font-mono-tech text-[11px] font-bold text-[#1A1D2B] hard-shadow-sm hover:bg-[#237DE1] hover:text-white transition">
                                        Detail
                                    </a>

                                    {{-- Form Verifikasi Status Cepat --}}
                                    @if ($peminjaman->status === 'Menunggu')
                                        <form action="{{ route('petugas.peminjamans.update-status', $peminjaman->id) }}"
                                              method="POST"
                                              id="form-status-{{ $peminjaman->id }}"
                                              class="flex items-center gap-1.5">
                                            @csrf
                                            @method('PATCH')

                                            <select name="status"
                                                    id="select-status-{{ $peminjaman->id }}"
                                                    required
                                                    class="rounded-lg border-2 border-[#1A1D2B] bg-white px-2 py-1 text-[11px] font-bold text-[#1A1D2B] focus:outline-none">
                                                <option value="">Pilih...</option>
                                                <option value="Disetujui">Setuju</option>
                                                <option value="Ditolak">Tolak</option>
                                            </select>

                                            <button type="button"
                                                    data-peminjaman-id="{{ $peminjaman->id }}"
                                                    data-peminjam-name="{{ $peminjaman->peminjam->name ?? 'Pengguna' }}"
                                                    onclick="openConfirmModal(this.dataset.peminjamanId, this.dataset.peminjamName)"
                                                    class="tactile-btn rounded-lg border-2 border-[#1A1D2B] bg-[#237DE1] px-2.5 py-1 font-mono-tech text-[11px] font-bold text-white hard-shadow-sm hover:bg-[#1d69bf]">
                                                OK
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center bg-white">
                                <div class="mx-auto flex max-w-sm flex-col items-center gap-2">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] font-mono-tech font-bold text-[#1A1D2B] hard-shadow-sm">
                                        i
                                    </div>
                                    <p class="text-sm font-extrabold uppercase text-[#1A1D2B]">Tidak ada data peminjaman</p>
                                    <p class="text-[12.5px] font-medium text-[#5C6475]">
                                        @if (request('search') || request('tanggal'))
                                            Pencarian atau filter tanggal tidak menemukan hasil yang cocok.
                                        @else
                                            Belum ada antrean permintaan peminjaman alat masuk.
                                        @endif
                                    </p>
                                    @if (request('search') || request('tanggal'))
                                        <a href="{{ route('petugas.peminjamans.index') }}"
                                           class="mt-2 inline-block font-mono-tech text-[12px] font-bold text-[#237DE1] underline">
                                            Reset Filter Pencarian
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
        @if ($peminjamans->hasPages())
            <div class="border-t-2 border-[#1A1D2B] bg-[#F2E7C9]/30 p-4">
                {{ $peminjamans->appends(request()->only(['search', 'tanggal']))->links() }}
            </div>
        @endif

    </div>

</div>

{{-- MODAL POPUP KONFIRMASI (FIX POSISI DI TENGAH LAYAR) --}}
<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#1A1D2B]/60 backdrop-blur-xs p-4">
    <div class="w-full max-w-md rounded-2xl border-2 border-[#1A1D2B] bg-white p-6 hard-shadow transform transition-all my-auto">
        <div class="flex items-center justify-between border-b-2 border-[#1A1D2B] pb-3 mb-4">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-[#EC5928] font-mono-tech font-bold text-white text-xs">!</span>
                <h3 class="font-extrabold uppercase text-[#1A1D2B] text-base">Konfirmasi Status</h3>
            </div>
            <button type="button" onclick="closeConfirmModal()" class="font-bold text-[#1A1D2B] hover:text-[#EC5928] text-lg">✕</button>
        </div>
        
        <p class="text-[13.5px] font-medium text-[#5C6475] mb-6">
            Anda akan mengubah status peminjaman milik <strong id="modalPeminjamName" class="text-[#1A1D2B]"></strong> menjadi <strong id="modalStatusTarget" class="text-[#237DE1]"></strong>. Lanjutkan tindakan ini?
        </p>

        <div class="flex items-center justify-end gap-3">
            <button type="button"
                    onclick="closeConfirmModal()"
                    class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2 font-mono-tech text-[12px] font-bold text-[#1A1D2B] hard-shadow-sm hover:bg-gray-100">
                Batal
            </button>
            <button type="button"
                    id="modalConfirmBtn"
                    class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-5 py-2 font-mono-tech text-[12px] font-bold text-white hard-shadow-sm hover:bg-[#1d69bf]">
                Ya, Proses
            </button>
        </div>
    </div>
</div>

{{-- SCRIPT PENGATUR MODAL POPUP --}}
<script>
    let activePeminjamanId = null;

    function openConfirmModal(id, peminjamName) {
        const selectElement = document.getElementById(`select-status-${id}`);
        const selectedStatus = selectElement.value;

        if (!selectedStatus) {
            alert('Silakan pilih status terlebih dahulu (Setuju / Tolak).');
            selectElement.focus();
            return;
        }

        activePeminjamanId = id;
        document.getElementById('modalPeminjamName').innerText = peminjamName;
        document.getElementById('modalStatusTarget').innerText = selectedStatus;
        
        const modal = document.getElementById('confirmModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex'); // Memastikan modal menggunakan display flex agar centering aktif
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        activePeminjamanId = null;
    }

    document.getElementById('modalConfirmBtn').addEventListener('click', function () {
        if (activePeminjamanId) {
            document.getElementById(`form-status-${activePeminjamanId}`).submit();
        }
    });

    window.addEventListener('click', function (e) {
        const modal = document.getElementById('confirmModal');
        if (e.target === modal) {
            closeConfirmModal();
        }
    });
</script>

@endsection