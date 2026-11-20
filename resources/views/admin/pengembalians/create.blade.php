@extends('layouts.admin')

@section('content')

{{-- ============ PAGE HEADER ============ --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        {{-- Breadcrumb Pill --}}
        <div class="mb-2 inline-flex items-center gap-1.5 rounded-md border-2 border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-0.5 font-mono text-[11px] font-bold text-[#1A1D2B]">
            <a href="{{ route('admin.pengembalians.index') }}" class="hover:text-[#237DE1] underline decoration-1 underline-offset-2">Pengembalian</a>
            <span>/</span>
            <span class="text-[#EC5928]">create</span>
        </div>

        <h1 class="text-[22px] sm:text-[26px] font-extrabold tracking-tight text-[#1A1D2B] leading-tight uppercase">
            Tambah Data Pengembalian
        </h1>
        <p class="mt-1 font-mono text-[12px] font-medium text-[#5C6475]">
            // proses pengembalian unit alat dari transaksi peminjaman aktif
        </p>
    </div>

    <a href="{{ route('admin.pengembalians.index') }}"
       class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2 font-mono text-[12px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9]">
        <span>← Kembali</span>
    </a>
</div>

{{-- ============ ERROR GLOBAL ============ --}}
@if($errors->any())
    <div class="mb-6 flex items-start gap-3 rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] px-4 py-3.5 text-white shadow-[4px_4px_0px_#1A1D2B]">
        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-white text-[#EC5928]">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <div>
            <p class="font-extrabold tracking-wider text-[13px] uppercase">Terjadi {{ $errors->count() }} kesalahan input:</p>
            <ul class="mt-1 list-inside list-disc font-mono text-[11px] font-bold opacity-90">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- ============ MAIN GRID ============ --}}
<div class="grid gap-6 lg:grid-cols-[1fr_320px]">

    {{-- ===== FORM CARD ===== --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center justify-between border-b-2 border-[#1A1D2B] bg-[#F2E7C9]/60 px-5 py-3">
            <div class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full border border-[#1A1D2B] bg-[#237DE1]"></span>
                <p class="font-mono text-[11px] font-bold uppercase tracking-widest text-[#1A1D2B]">
                    Formulir Input Pengembalian Alat
                </p>
            </div>
            <span class="font-mono-tech rounded border border-[#1A1D2B] bg-white px-2 py-0.5 text-[10px] font-extrabold text-[#1A1D2B]">
                METHOD: POST (MULTIPART)
            </span>
        </div>

        <form id="form-pengembalian" action="{{ route('admin.pengembalians.store') }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-7">
            @csrf

            <div class="space-y-5">

                {{-- 01. DATA PEMINJAMAN --}}
                <div>
                    <label for="peminjaman_id" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#EC5928] px-1.5 py-0.5 text-[9px] text-white">01</span>
                        Pilih Transaksi Peminjaman
                        <span class="text-[#EC5928]">*</span>
                    </label>

                    <select
                        id="peminjaman_id"
                        name="peminjaman_id"
                        required
                        class="w-full rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5
                               font-mono text-[13px] font-bold text-[#1A1D2B]
                               focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow cursor-pointer"
                    >
                        <option value="">-- Pilih Transaksi Peminjaman --</option>
                        @foreach ($peminjamans as $peminjaman)
                            <option value="{{ $peminjaman->id }}" @selected(old('peminjaman_id') == $peminjaman->id)>
                                #{{ $peminjaman->id }} — {{ $peminjaman->peminjam->name ?? '-' }} (Pinjam: {{ $peminjaman->tanggal_pinjam->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>

                    @error('peminjaman_id')
                        <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">✕ {{ $message }}</p>
                    @enderror
                </div>

                {{-- 02. TANGGAL PENGEMBALIAN --}}
                <div>
                    <label for="tanggal_pengembalian" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#237DE1] px-1.5 py-0.5 text-[9px] text-white">02</span>
                        Tanggal Pengembalian
                        <span class="text-[#EC5928]">*</span>
                    </label>

                    <input
                        type="date"
                        id="tanggal_pengembalian"
                        name="tanggal_pengembalian"
                        value="{{ old('tanggal_pengembalian', now()->toDateString()) }}"
                        required
                        class="w-full rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5
                               font-mono text-[13px] font-bold text-[#1A1D2B]
                               focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow"
                    >

                    @error('tanggal_pengembalian')
                        <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">✕ {{ $message }}</p>
                    @enderror
                </div>

                {{-- 03. CATATAN --}}
                <div>
                    <label for="catatan" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#5C6475] px-1.5 py-0.5 text-[9px] text-white">03</span>
                        Catatan Pemeriksaan (Opsional)
                    </label>

                    <textarea
                        id="catatan"
                        name="catatan"
                        rows="3"
                        placeholder="Contoh: Seluruh alat dikembalikan lengkap tanpa ada kendala..."
                        class="w-full rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5
                               font-mono text-[13.5px] font-medium text-[#1A1D2B] placeholder:text-[#5C6475]
                               focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow"
                    >{{ old('catatan') }}</textarea>

                    @error('catatan')
                        <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">✕ {{ $message }}</p>
                    @enderror
                </div>

            </div>

            <hr class="my-6 border-2 border-dashed border-[#1A1D2B]/30">

            {{-- DETAIL ALAT (DYNAMIC BUILDER) --}}
            <div class="mb-4">
                <h3 class="font-mono text-[13px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                    // Rincian Alat yang Dikembalikan
                </h3>
                <p class="font-mono text-[11px] text-[#5C6475]">
                    Pilih transaksi peminjaman di atas untuk memuat daftar alat secara otomatis.
                </p>
            </div>

            <div id="detail-container" class="space-y-4">
                <div class="rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9]/40 p-4 font-mono text-[12px] font-bold text-[#5C6475] text-center shadow-[2px_2px_0px_#1A1D2B]">
                    Silakan pilih data transaksi peminjaman terlebih dahulu.
                </div>
            </div>

            @error('details')
                <p class="mt-2 font-mono text-[11px] font-bold text-[#EC5928]">✕ {{ $message }}</p>
            @enderror

            {{-- ==== ACTIONS ==== --}}
            <div class="mt-8 flex flex-col-reverse items-stretch gap-3 border-t-2 border-[#1A1D2B] pt-5 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('admin.pengembalians.index') }}"
                   class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5 font-mono text-[12px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9]">
                    ← Batal
                </a>

                <button type="submit"
                    class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-5 py-2.5 font-mono text-[12px] font-extrabold uppercase tracking-wider text-white shadow-[3px_3px_0px_#1A1D2B] hover:bg-[#1d69bf]">
                    <span>Simpan Pengembalian</span>
                    <span>→</span>
                </button>
            </div>
        </form>
    </div>

    {{-- ===== SIDE PANEL (Panduan Kamera) ===== --}}
    <aside class="space-y-4">
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-[#F2E7C9] shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">
            <div class="border-b-2 border-[#1A1D2B] bg-[#1A1D2B] px-4 py-2.5 text-white">
                <p class="font-mono text-[11px] font-extrabold uppercase tracking-widest">
                    // Panduan Bukti Fisik
                </p>
            </div>
            <div class="space-y-3 p-4">
                <div class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded border border-[#1A1D2B] bg-[#237DE1] font-mono text-[10px] font-extrabold text-white">1</span>
                    <p class="font-mono text-[11px] font-medium text-[#1A1D2B] leading-tight">
                        Gunakan tombol <strong class="text-[#237DE1]">Buka Kamera</strong> untuk mengambil foto langsung kondisi fisik alat saat diterima.
                    </p>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded border border-[#1A1D2B] bg-[#237DE1] font-mono text-[10px] font-extrabold text-white">2</span>
                    <p class="font-mono text-[11px] font-medium text-[#1A1D2B] leading-tight">
                        Pastikan foto bukti jelas dan mencakup keseluruhan fisik alat sebelum menyimpan transaksi.
                    </p>
                </div>
            </div>
        </div>
    </aside>
</div>

{{-- ============ JSON DATA & BUILDER SCRIPT ============ --}}
@php
    $dataPeminjaman = $peminjamans->map(function ($peminjaman) {
        return [
            'id' => $peminjaman->id,
            'details' => $peminjaman->detailPeminjaman->map(function ($detail) use ($peminjaman) {
                $sudahDikembalikan = $peminjaman->pengembalians
                    ->flatMap->detailPengembalian
                    ->where('detail_peminjaman_id', $detail->id)
                    ->sum('jumlah_dikembalikan');

                return [
                    'id' => $detail->id,
                    'nama_alat' => $detail->alat->nama_alat ?? 'Alat tidak tersedia',
                    'jumlah_pinjam' => (int) $detail->jumlah,
                    'sudah_dikembalikan' => (int) $sudahDikembalikan,
                    'sisa' => max(0, (int) $detail->jumlah - (int) $sudahDikembalikan),
                ];
            })->values(),
        ];
    })->values();
@endphp

<div id="peminjaman-data" data-peminjaman="{{ $dataPeminjaman->toJson() }}" hidden></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dataElement = document.getElementById('peminjaman-data');
        const dataPeminjaman = JSON.parse(dataElement.dataset.peminjaman);

        const selectPeminjaman = document.getElementById('peminjaman_id');
        const container = document.getElementById('detail-container');
        const form = document.getElementById('form-pengembalian');
        const activeStreams = [];

        function buatElemen(tag, className, text = '') {
            const element = document.createElement(tag);
            element.className = className;
            element.textContent = text;
            return element;
        }

        function hentikanKamera(stream) {
            if (!stream) return;
            stream.getTracks().forEach(track => track.stop());
            const streamIndex = activeStreams.indexOf(stream);
            if (streamIndex !== -1) activeStreams.splice(streamIndex, 1);
        }

        function hentikanSemuaKamera() {
            activeStreams.forEach(stream => {
                stream.getTracks().forEach(track => track.stop());
            });
            activeStreams.length = 0;
        }

        function tampilkanDetail() {
            hentikanSemuaKamera();
            container.replaceChildren();

            const peminjaman = dataPeminjaman.find(item => item.id == selectPeminjaman.value);

            if (!peminjaman) {
                const notice = buatElemen('div', 'rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9]/40 p-4 font-mono text-[12px] font-bold text-[#5C6475] text-center shadow-[2px_2px_0px_#1A1D2B]', 'Silakan pilih data transaksi peminjaman terlebih dahulu.');
                container.append(notice);
                return;
            }

            const details = peminjaman.details.filter(item => item.sisa > 0);

            if (details.length === 0) {
                const notice = buatElemen('div', 'rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] p-4 font-mono text-[12px] font-bold text-white text-center shadow-[2px_2px_0px_#1A1D2B]', 'Seluruh alat pada transaksi peminjaman ini sudah selesai dikembalikan.');
                container.append(notice);
                return;
            }

            details.forEach((detail, index) => {
                const card = buatElemen('div', 'rounded-xl border-2 border-[#1A1D2B] bg-white p-4.5 space-y-4 shadow-[3px_3px_0px_#1A1D2B]');

                // Header Nama Alat
                const headerAlat = buatElemen('div', 'flex flex-col sm:flex-row sm:items-center sm:justify-between border-b-2 border-[#1A1D2B] pb-3');
                headerAlat.append(buatElemen('h4', 'font-mono text-[13.5px] font-extrabold text-[#1A1D2B]', detail.nama_alat));
                headerAlat.append(buatElemen('span', 'font-mono rounded border border-[#1A1D2B] bg-[#F2E7C9] px-2 py-0.5 text-[11px] font-extrabold text-[#1A1D2B]', `Sisa: ${detail.sisa} unit`));
                card.append(headerAlat);

                // Info Jumlah
                card.append(buatElemen('p', 'font-mono text-[11px] text-[#5C6475]', `Total Dipinjam: ${detail.jumlah_pinjam} unit | Sudah Kembali: ${detail.sudah_dikembalikan} unit`));

                // ID Detail Peminjaman (Hidden)
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = `details[${index}][detail_peminjaman_id]`;
                idInput.value = detail.id;
                card.append(idInput);

                // Grid Input Jumlah & Kondisi
                const gridInput = buatElemen('div', 'grid gap-4 sm:grid-cols-2');

                // Jumlah Dikembalikan
                const jumlahWrapper = buatElemen('div');
                jumlahWrapper.append(buatElemen('label', 'mb-1.5 block font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]', 'Jumlah Dikembalikan'));
                const jumlahInput = document.createElement('input');
                jumlahInput.type = 'number';
                jumlahInput.name = `details[${index}][jumlah_dikembalikan]`;
                jumlahInput.min = '1';
                jumlahInput.max = detail.sisa;
                jumlahInput.required = true;
                jumlahInput.placeholder = `Maks. ${detail.sisa} unit`;
                jumlahInput.className = 'w-full rounded-xl border-2 border-[#1A1D2B] bg-white px-3.5 py-2 font-mono text-[12.5px] font-bold text-[#1A1D2B] focus:outline-none';
                jumlahWrapper.append(jumlahInput);
                gridInput.append(jumlahWrapper);

                // Kondisi Alat
                const kondisiWrapper = buatElemen('div');
                kondisiWrapper.append(buatElemen('label', 'mb-1.5 block font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]', 'Kondisi Alat'));
                const kondisiSelect = document.createElement('select');
                kondisiSelect.name = `details[${index}][kondisi_alat]`;
                kondisiSelect.required = true;
                kondisiSelect.className = 'w-full rounded-xl border-2 border-[#1A1D2B] bg-white px-3.5 py-2 font-mono text-[12.5px] font-bold text-[#1A1D2B] focus:outline-none cursor-pointer';

                const pilihan = [
                    { value: '', text: '-- Pilih Kondisi --' },
                    { value: 'Baik', text: 'Baik (Normal)' },
                    { value: 'Rusak', text: 'Rusak (Perlu Penggantian)' },
                    { value: 'Perlu Perbaikan', text: 'Perlu Perbaikan (Maintenance)' },
                ];
                pilihan.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.value;
                    option.textContent = item.text;
                    kondisiSelect.append(option);
                });
                kondisiWrapper.append(kondisiSelect);
                gridInput.append(kondisiWrapper);

                card.append(gridInput);

                // Bagian Bukti Foto Kamera
                const fotoSection = buatElemen('div', 'space-y-3 border-t-2 border-dashed border-[#1A1D2B]/30 pt-4');
                fotoSection.append(buatElemen('p', 'font-mono text-[11.5px] font-extrabold uppercase tracking-wider text-[#1A1D2B]', 'Foto Bukti Kondisi Alat'));

                const video = document.createElement('video');
                video.className = 'hidden w-full max-h-64 rounded-xl border-2 border-[#1A1D2B] bg-black object-contain';
                video.autoplay = true;
                video.playsInline = true;
                video.muted = true;

                const preview = document.createElement('img');
                preview.className = 'hidden w-full max-h-64 rounded-xl border-2 border-[#1A1D2B] object-contain shadow-[2px_2px_0px_#1A1D2B]';
                preview.alt = 'Preview foto bukti';

                const canvas = document.createElement('canvas');
                canvas.className = 'hidden';

                const fotoInput = document.createElement('input');
                fotoInput.type = 'file';
                fotoInput.name = `details[${index}][gambar_bukti]`;
                fotoInput.accept = 'image/jpeg,image/png,image/webp';
                fotoInput.className = 'hidden';

                const bukaKameraButton = buatElemen('button', 'tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-white shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#1d69bf]', 'Buka Kamera');
                bukaKameraButton.type = 'button';

                const ambilFotoButton = buatElemen('button', 'hidden tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-[#10B981] px-4 py-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-white shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#059669]', 'Ambil Foto');
                ambilFotoButton.type = 'button';

                const ulangiFotoButton = buatElemen('button', 'hidden tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9]', 'Ulangi Foto');
                ulangiFotoButton.type = 'button';

                const statusFoto = buatElemen('p', 'font-mono text-[11px] text-[#5C6475]', 'Status: Belum ada foto bukti diambil.');

                bukaKameraButton.addEventListener('click', async () => {
                    try {
                        if (!navigator.mediaDevices?.getUserMedia) {
                            throw new Error('Browser tidak mendukung akses kamera. Gunakan HTTPS atau localhost.');
                        }
                        bukaKameraButton.disabled = true;
                        bukaKameraButton.textContent = 'Membuka...';

                        const stream = await navigator.mediaDevices.getUserMedia({
                            audio: false,
                            video: { facingMode: { ideal: 'environment' } }
                        });

                        activeStreams.push(stream);
                        video.srcObject = stream;
                        video.classList.remove('hidden');
                        preview.classList.add('hidden');

                        await video.play();

                        bukaKameraButton.classList.add('hidden');
                        ambilFotoButton.classList.remove('hidden');
                        ulangiFotoButton.classList.add('hidden');
                        statusFoto.textContent = 'Kamera aktif. Arahkan ke alat lalu ambil foto.';
                    } catch (error) {
                        console.error(error);
                        statusFoto.textContent = error.message || 'Gagal membuka kamera.';
                        bukaKameraButton.disabled = false;
                        bukaKameraButton.textContent = 'Coba Buka Kamera';
                    }
                });

                ambilFotoButton.addEventListener('click', () => {
                    if (!video.videoWidth || !video.videoHeight) {
                        statusFoto.textContent = 'Kamera belum siap sepenuhnya.';
                        return;
                    }

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const context = canvas.getContext('2d');
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    canvas.toBlob(blob => {
                        if (!blob) {
                            statusFoto.textContent = 'Gagal mengambil gambar.';
                            return;
                        }

                        const file = new window.File([blob], `bukti-${Date.now()}.jpg`, { type: 'image/jpeg' });
                        const transfer = new window.DataTransfer();
                        transfer.items.add(file);
                        fotoInput.files = transfer.files;

                        preview.src = URL.createObjectURL(file);
                        preview.classList.remove('hidden');

                        hentikanKamera(video.srcObject);
                        video.srcObject = null;
                        video.classList.add('hidden');

                        ambilFotoButton.classList.add('hidden');
                        bukaKameraButton.classList.add('hidden');
                        ulangiFotoButton.classList.remove('hidden');
                        statusFoto.textContent = 'Foto bukti berhasil disimpan.';
                    }, 'image/jpeg', 0.85);
                });

                ulangiFotoButton.addEventListener('click', () => {
                    fotoInput.value = '';
                    preview.src = '';
                    preview.classList.add('hidden');
                    ulangiFotoButton.classList.add('hidden');
                    bukaKameraButton.classList.remove('hidden');
                    bukaKameraButton.disabled = false;
                    bukaKameraButton.textContent = 'Buka Kamera';
                    statusFoto.textContent = 'Silakan ambil ulang foto bukti.';
                });

                fotoSection.append(video, preview, canvas, fotoInput);
                const btnRow = buatElemen('div', 'flex flex-wrap gap-2 pt-1');
                btnRow.append(bukaKameraButton, ambilFotoButton, ulangiFotoButton);
                fotoSection.append(btnRow, statusFoto);

                card.append(fotoSection);
                container.append(card);
            });
        }

        selectPeminjaman.addEventListener('change', tampilkanDetail);

        form.addEventListener('submit', event => {
            const fotoInputs = container.querySelectorAll('input[type="file"]');
            const fotoBelumDiambil = [...fotoInputs].some(input => input.files.length === 0);

            if (fotoBelumDiambil && fotoInputs.length > 0) {
                event.preventDefault();
                alert('Harap ambil foto bukti kondisi untuk setiap alat yang dikembalikan.');
                return;
            }

            hentikanSemuaKamera();
        });

        if (selectPeminjaman.value) {
            tampilkanDetail();
        }
    });
</script>

@endsection