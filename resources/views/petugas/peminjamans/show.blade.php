@extends('layouts.petugas')

@section('title', 'Detail Peminjaman')
@section('header', 'Detail Peminjaman Alat')

@section('content')

<div class="space-y-6 max-w-5xl mx-auto">

    {{-- TOMBOL KEMBALI --}}
    <div>
        <a href="{{ route('petugas.peminjamans.index') }}"
           class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-3.5 py-2 font-mono-tech text-[12px] font-bold text-[#1A1D2B] hard-shadow-sm hover:bg-[#F2E7C9]">
            <span>←</span>
            <span>Kembali ke Daftar Peminjaman</span>
        </a>
    </div>

    {{-- FLASH MESSAGE SUKSES --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-3.5 text-white hard-shadow-sm">
            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-white text-[#237DE1]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-[13.5px] font-bold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- KARTU INFORMASI UTAMA PEMINJAMAN --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white hard-shadow overflow-hidden">
        <div class="border-b-2 border-[#1A1D2B] bg-[#F2E7C9]/60 px-6 py-4">
            <h3 class="font-mono-tech text-[12px] font-bold uppercase tracking-widest text-[#1A1D2B]">
                // Informasi Detail Peminjam & Transaksi
            </h3>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

            <div>
                <p class="font-mono-tech text-[11px] font-bold uppercase text-[#5C6475] mb-1">Nama Peminjam</p>
                <p class="text-[15px] font-extrabold text-[#1A1D2B]">
                    {{ $peminjaman->peminjam->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="font-mono-tech text-[11px] font-bold uppercase text-[#5C6475] mb-1">Tanggal Pinjam</p>
                <p class="text-[14px] font-bold font-mono-tech text-[#1A1D2B]">
                    {{ $peminjaman->tanggal_pinjam?->format('d/m/Y') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="font-mono-tech text-[11px] font-bold uppercase text-[#5C6475] mb-1">Rencana Pengembalian</p>
                <p class="text-[14px] font-bold font-mono-tech text-[#EC5928]">
                    {{ $peminjaman->tanggal_rencana_kembali?->format('d/m/Y') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="font-mono-tech text-[11px] font-bold uppercase text-[#5C6475] mb-1">Status Saat Ini</p>
                @php
                    $statusConfig = [
                        'Menunggu'     => 'bg-[#F2E7C9] text-[#1A1D2B]',
                        'Disetujui'    => 'bg-emerald-300 text-emerald-950',
                        'Ditolak'      => 'bg-rose-300 text-rose-950',
                        'Dikembalikan' => 'bg-sky-300 text-sky-950',
                    ];
                    $badgeStyle = $statusConfig[$peminjaman->status] ?? 'bg-gray-200 text-gray-800';
                @endphp
                <span class="inline-block rounded-md border-2 border-[#1A1D2B] {{ $badgeStyle }} px-3 py-1 font-mono-tech text-[11px] font-bold uppercase tracking-wider hard-shadow-sm">
                    {{ $peminjaman->status }}
                </span>
            </div>

            <div class="sm:col-span-2 border-t-2 border-dashed border-[#1A1D2B]/20 pt-4">
                <p class="font-mono-tech text-[11px] font-bold uppercase text-[#5C6475] mb-1">Keterangan / Keperluan</p>
                <p class="text-[13.5px] font-medium text-[#1A1D2B] bg-[#F2E7C9]/30 p-3 rounded-xl border-2 border-[#1A1D2B]">
                    {{ $peminjaman->keterangan ?: 'Tidak ada keterangan tambahan.' }}
                </p>
            </div>

            @if ($peminjaman->penyetuju)
                <div class="sm:col-span-2 border-t-2 border-dashed border-[#1A1D2B]/20 pt-4">
                    <p class="font-mono-tech text-[11px] font-bold uppercase text-[#5C6475] mb-1">Disetujui/Diproses Oleh</p>
                    <p class="text-[13.5px] font-extrabold text-[#237DE1]">
                        {{ $peminjaman->penyetuju->name }}
                    </p>
                </div>
            @endif

        </div>
    </div>

    {{-- KARTU DAFTAR ALAT YANG DIPINJAM --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white hard-shadow overflow-hidden">
        <div class="border-b-2 border-[#1A1D2B] bg-[#F2E7C9]/60 px-6 py-4">
            <h3 class="font-mono-tech text-[12px] font-bold uppercase tracking-widest text-[#1A1D2B]">
                // Daftar Alat yang Diajukan
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="border-b-2 border-[#1A1D2B] bg-[#1A1D2B] text-white">
                        <th class="w-[60px] border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest text-center">No</th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest">Gambar</th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest">Nama Alat</th>
                        <th class="border-r-2 border-[#1A1D2B] px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest">Kode Alat</th>
                        <th class="px-4 py-3.5 font-mono-tech text-[11px] font-bold uppercase tracking-widest text-center">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-[#1A1D2B]">
                    @forelse ($peminjaman->detailPeminjaman as $index => $detail)
                        <tr class="transition hover:bg-[#F2E7C9]/30">
                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 text-center font-mono-tech text-[12px] font-bold text-[#5C6475]">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="border-r-2 border-[#1A1D2B] px-4 py-3">
                                @if ($detail->alat?->gambar)
                                    <img src="{{ asset('storage/' . $detail->alat->gambar) }}"
                                         alt="{{ $detail->alat->nama_alat }}"
                                         class="h-12 w-12 rounded-lg border-2 border-[#1A1D2B] object-cover hard-shadow-sm">
                                @else
                                    <span class="font-mono-tech text-[10px] font-bold text-[#5C6475] italic">Tanpa Gambar</span>
                                @endif
                            </td>

                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 font-extrabold text-[13.5px] text-[#1A1D2B]">
                                {{ $detail->alat->nama_alat ?? '-' }}
                            </td>

                            <td class="border-r-2 border-[#1A1D2B] px-4 py-4 font-mono-tech text-[12px] text-[#5C6475]">
                                {{ $detail->alat->kode_alat ?? '-' }}
                            </td>

                            <td class="px-4 py-4 text-center font-mono-tech text-[13px] font-bold text-[#1A1D2B]">
                                <span class="rounded-md border border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-1">
                                    {{ $detail->jumlah }} Unit
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center font-mono-tech text-xs text-[#5C6475]">
                                Tidak ada data detail alat pada peminjaman ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="border-t-2 border-[#1A1D2B] bg-[#F2E7C9]/40 font-mono-tech text-[13px]">
                    <tr>
                        <td colspan="4" class="px-6 py-3.5 text-right font-extrabold text-[#1A1D2B]">
                            TOTAL KESELURUHAN UNIT ALAT:
                        </td>
                        <td class="px-6 py-3.5 text-center font-extrabold text-[#EC5928]">
                            {{ $peminjaman->detailPeminjaman->sum('jumlah') }} Unit
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- FORM PERSETUJUAN STATUS (JIKA STATUS MASIH MENUNGGU) --}}
        @if ($peminjaman->status === 'Menunggu')
            <div class="border-t-2 border-[#1A1D2B] bg-[#F2E7C9]/60 p-6">
                <form action="{{ route('petugas.peminjamans.update-status', $peminjaman->id) }}"
                      method="POST"
                      id="form-detail-status"
                      data-peminjam-name="{{ $peminjaman->peminjam->name ?? 'Pengguna' }}"
                      class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <p class="font-extrabold text-[14px] text-[#1A1D2B]">Tindakan Verifikasi Petugas</p>
                        <p class="text-[12px] text-[#5C6475] font-medium">Pilih status persetujuan untuk memproses pengajuan peminjaman alat.</p>
                    </div>

                    <div class="flex items-center gap-2.5 w-full sm:w-auto">
                        <select name="status"
                                id="select-detail-status"
                                required
                                class="rounded-xl border-2 border-[#1A1D2B] bg-white px-3 py-2 text-[13px] font-bold text-[#1A1D2B] focus:outline-none focus:shadow-[2px_2px_0px_#237DE1]">
                            <option value="">Pilih Keputusan...</option>
                            <option value="Disetujui">Setujui Peminjaman</option>
                            <option value="Ditolak">Tolak Peminjaman</option>
                        </select>

                        <button type="button"
                                onclick="openDetailModal()"
                                class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-5 py-2.5 text-[13px] font-bold text-white hard-shadow-sm hover:bg-[#1d69bf]">
                            Simpan Status
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>

</div>

{{-- MODAL POPUP KONFIRMASI DETAIL (DI TENGAH LAYAR) --}}
<div id="detailConfirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#1A1D2B]/60 backdrop-blur-xs p-4">
    <div class="w-full max-w-md rounded-2xl border-2 border-[#1A1D2B] bg-white p-6 hard-shadow transform transition-all my-auto">
        <div class="flex items-center justify-between border-b-2 border-[#1A1D2B] pb-3 mb-4">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-[#EC5928] font-mono-tech font-bold text-white text-xs">!</span>
                <h3 class="font-extrabold uppercase text-[#1A1D2B] text-base">Konfirmasi Verifikasi</h3>
            </div>
            <button type="button" onclick="closeDetailModal()" class="font-bold text-[#1A1D2B] hover:text-[#EC5928] text-lg">✕</button>
        </div>
        
        <p class="text-[13.5px] font-medium text-[#5C6475] mb-6">
            Anda akan mengubah status peminjaman milik <strong id="modalDetailName" class="text-[#1A1D2B]"></strong> menjadi <strong id="modalDetailTarget" class="text-[#237DE1]"></strong>. Lanjutkan tindakan ini?
        </p>

        <div class="flex items-center justify-end gap-3">
            <button type="button"
                    onclick="closeDetailModal()"
                    class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2 font-mono-tech text-[12px] font-bold text-[#1A1D2B] hard-shadow-sm hover:bg-gray-100">
                Batal
            </button>
            <button type="button"
                    id="modalDetailConfirmBtn"
                    class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-5 py-2 font-mono-tech text-[12px] font-bold text-white hard-shadow-sm hover:bg-[#1d69bf]">
                Ya, Proses
            </button>
        </div>
    </div>
</div>

{{-- SCRIPT MODAL DETAIL --}}
<script>
    function openDetailModal() {
        const selectElement = document.getElementById('select-detail-status');
        const selectedStatus = selectElement.value;

        if (!selectedStatus) {
            alert('Silakan pilih status keputusan terlebih dahulu.');
            selectElement.focus();
            return;
        }

        const peminjamName = document.getElementById('form-detail-status').dataset.peminjamName;
        document.getElementById('modalDetailName').innerText = peminjamName;
        document.getElementById('modalDetailTarget').innerText = selectedStatus;
        
        const modal = document.getElementById('detailConfirmModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailConfirmModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    document.getElementById('modalDetailConfirmBtn')?.addEventListener('click', function () {
        document.getElementById('form-detail-status').submit();
    });

    window.addEventListener('click', function (e) {
        const modal = document.getElementById('detailConfirmModal');
        if (e.target === modal) {
            closeDetailModal();
        }
    });
</script>

@endsection