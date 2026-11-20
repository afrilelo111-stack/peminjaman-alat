@extends('layouts.user')

@section('title', 'Formulir Pengembalian')
@section('header', 'Formulir Pengembalian Inventaris')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto">

    {{-- TOMBOL KEMBALI --}}
    <div>
        <a href="{{ route('user.riwayat-peminjamans.show', $peminjaman->id) }}"
           class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2 font-mono-tech text-xs font-bold text-[#1A1D2B] hard-shadow-sm hover:bg-[#F2E7C9] transition">
            <span>&larr;</span>
            <span>KEMBALI KE DETAIL TRANSAKSI</span>
        </a>
    </div>

    {{-- ALERT ERROR VALIDASI --}}
    @if ($errors->any())
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-rose-100 p-5 hard-shadow text-[#1A1D2B]">
            <div class="flex items-center gap-2.5 mb-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-rose-300 font-mono-tech font-bold text-xs">
                    !
                </div>
                <p class="font-mono-tech text-xs font-extrabold uppercase tracking-tight text-rose-950">
                    Periksa kembali data pengembalian berikut:
                </p>
            </div>
            <ul class="list-disc list-inside font-mono-tech text-[11.5px] font-bold text-rose-900 space-y-1 pl-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- LAYOUT 2 KOLOM ALA CHECKOUT TOKOPEDIA (KIRI: FORM & KAMERA | KANAN: RINGKASAN & SUBMIT STICKY) --}}
    <form
        action="{{ route('user.riwayat-peminjamans.store', $peminjaman->id) }}"
        method="POST"
        enctype="multipart/form-data"
        class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start"
    >
        @csrf

        {{-- KOLOM KIRI: FORM INPUT PENGEMBALIAN PER ITEM (Span 7) --}}
        <div class="lg:col-span-7 space-y-6">

            <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-5 sm:p-6 hard-shadow space-y-3">
                <span class="font-mono-tech text-xs font-extrabold uppercase text-[#237DE1]">
                    // 01. DAFTAR ITEM DIKEMBALIKAN
                </span>
                <h3 class="text-base sm:text-lg font-extrabold text-[#1A1D2B] uppercase tracking-tight">
                    Formulir Kondisi & Bukti Fisik
                </h3>
                <p class="text-xs font-medium text-[#5C6475]">
                    Masukkan jumlah unit yang dikembalikan, pilih kondisi fisik, serta ambil foto bukti untuk tiap jenis alat.
                </p>
            </div>

            {{-- LOOPING DETAIL ALAT --}}
            <div class="space-y-6">
                @foreach ($peminjaman->detailPeminjaman as $detail)
                    @php
                        $sisa = $sisaPerDetail[$detail->id] ?? 0;
                    @endphp

                    @if ($sisa > 0)
                        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-6 hard-shadow space-y-5">
                            
                            {{-- Header Item Alat --}}
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b-2 border-[#1A1D2B]/15 pb-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="h-12 w-12 shrink-0 rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9]/40 overflow-hidden flex items-center justify-center">
                                        @if ($detail->alat && $detail->alat->gambar)
                                            <img src="{{ asset('storage/' . $detail->alat->gambar) }}" alt="Alat" class="w-full h-full object-cover">
                                        @else
                                            <span class="font-mono-tech text-[9px] font-bold text-[#5C6475]">UNIT</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-base text-[#1A1D2B]">
                                            {{ $detail->alat->nama_alat ?? 'Alat tidak ditemukan' }}
                                        </h4>
                                        <p class="font-mono-tech text-[11px] font-bold text-[#5C6475]">
                                            Kode: <span class="text-[#1A1D2B]">{{ $detail->alat->kode_alat ?? '-' }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 font-mono-tech">
                                    <span class="rounded-lg border border-[#1A1D2B] bg-blue-100 px-2.5 py-1 text-[11px] font-extrabold text-[#237DE1]">
                                        Belum Kembali: {{ $sisa }} Unit
                                    </span>
                                </div>
                            </div>

                            {{-- Form Input Jumlah & Kondisi --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                
                                {{-- Jumlah Dikembalikan --}}
                                <div>
                                    <label class="block font-mono-tech text-xs font-bold text-[#1A1D2B] uppercase mb-2">
                                        Jumlah Dikembalikan <span class="text-rose-600">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        name="details[{{ $detail->id }}][jumlah_dikembalikan]"
                                        min="1"
                                        max="{{ $sisa }}"
                                        value="{{ old('details.' . $detail->id . '.jumlah_dikembalikan') }}"
                                        required
                                        class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] px-3.5 py-2.5 font-mono-tech text-xs font-bold text-[#1A1D2B] focus:outline-none hard-shadow-sm"
                                        placeholder="Maks: {{ $sisa }}"
                                    >
                                </div>

                                {{-- Kondisi Alat --}}
                                <div>
                                    <label class="block font-mono-tech text-xs font-bold text-[#1A1D2B] uppercase mb-2">
                                        Kondisi Fisik <span class="text-rose-600">*</span>
                                    </label>
                                    <select
                                        name="details[{{ $detail->id }}][kondisi_alat]"
                                        required
                                        class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] px-3.5 py-2.5 font-mono-tech text-xs font-bold text-[#1A1D2B] focus:outline-none hard-shadow-sm cursor-pointer"
                                    >
                                        <option value="">-- Pilih Kondisi --</option>
                                        <option value="Baik" @selected(old('details.' . $detail->id . '.kondisi_alat') === 'Baik')>Baik (Normal)</option>
                                        <option value="Rusak" @selected(old('details.' . $detail->id . '.kondisi_alat') === 'Rusak')>Rusak</option>
                                        <option value="Perlu Perbaikan" @selected(old('details.' . $detail->id . '.kondisi_alat') === 'Perlu Perbaikan')>Perlu Perbaikan</option>
                                    </select>
                                </div>

                                {{-- Kamera / Bukti Foto Pengembalian --}}
                                <div class="sm:col-span-2" data-camera-card>
                                    <label class="block font-mono-tech text-xs font-bold text-[#1A1D2B] uppercase mb-2">
                                        Foto Bukti Fisik <span class="text-rose-600">*</span>
                                    </label>

                                    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-[#F8F5EE] p-4 space-y-3">

                                        {{-- Tampilan Kamera --}}
                                        <video
                                            data-camera-video
                                            autoplay
                                            playsinline
                                            muted
                                            class="hidden w-full max-h-64 rounded-xl border-2 border-[#1A1D2B] bg-black object-contain hard-shadow-sm"
                                        ></video>

                                        {{-- Preview Foto --}}
                                        <img
                                            data-camera-preview
                                            class="hidden w-full max-h-64 rounded-xl border-2 border-[#1A1D2B] object-contain hard-shadow-sm bg-white"
                                            alt="Preview foto pengembalian"
                                        >

                                        <canvas
                                            data-camera-canvas
                                            class="hidden"
                                        ></canvas>

                                        {{-- Tombol Kontrol Kamera --}}
                                        <div class="flex flex-wrap gap-2 pt-1">
                                            <button
                                                type="button"
                                                data-camera-start
                                                class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-3.5 py-2 font-mono-tech text-xs font-bold text-white hard-shadow-sm hover:bg-[#1d69bf]"
                                            >
                                                📷 AKTIFKAN KAMERA
                                            </button>

                                            <button
                                                type="button"
                                                data-camera-capture
                                                disabled
                                                class="tactile-btn hidden rounded-xl border-2 border-[#1A1D2B] bg-emerald-400 px-3.5 py-2 font-mono-tech text-xs font-bold text-emerald-950 hard-shadow-sm hover:bg-emerald-500 disabled:opacity-50"
                                            >
                                                📸 AMBIL FOTO
                                            </button>

                                            <button
                                                type="button"
                                                data-camera-retake
                                                class="tactile-btn hidden rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] px-3.5 py-2 font-mono-tech text-xs font-bold text-white hard-shadow-sm hover:bg-[#d94b1c]"
                                            >
                                                🔄 AMBIL ULANG
                                            </button>
                                        </div>

                                        {{-- Input File Tersembunyi --}}
                                        <input
                                            type="file"
                                            name="details[{{ $detail->id }}][gambar_bukti]"
                                            accept="image/jpeg,image/png,image/webp"
                                            data-camera-input
                                            class="hidden"
                                        >

                                        <p
                                            data-camera-message
                                            class="font-mono-tech text-[11px] font-bold text-[#5C6475]"
                                        >
                                            // Wajib ambil foto bukti serah terima alat.
                                        </p>

                                    </div>
                                </div>

                            </div>

                        </div>
                    @endif
                @endforeach
            </div>

        </div>

        {{-- KOLOM KANAN: RINGKASAN TRANSAKSI & TOMBOL SUBMIT STICKY (Span 5) --}}
        <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-24">

            <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white p-6 hard-shadow space-y-5">
                <div class="border-b-2 border-[#1A1D2B]/15 pb-3">
                    <span class="font-mono-tech text-xs font-extrabold uppercase text-[#237DE1]">
                        // 02. RINGKASAN PENGEMBALIAN
                    </span>
                    <h3 class="text-base sm:text-lg font-extrabold text-[#1A1D2B] uppercase tracking-tight">
                        Ringkasan Transaksi
                    </h3>
                </div>

                <div class="space-y-3 font-mono-tech text-xs">
                    <div class="flex items-center justify-between pb-2 border-b border-[#1A1D2B]/10">
                        <span class="text-[#5C6475]">Nomor Transaksi:</span>
                        <strong class="text-[#1A1D2B]">#PMJ-{{ str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                    <div class="flex items-center justify-between pb-2 border-b border-[#1A1D2B]/10">
                        <span class="text-[#5C6475]">Tanggal Pinjam:</span>
                        <strong class="text-[#1A1D2B]">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</strong>
                    </div>
                </div>

                <div>
                    <label class="block font-mono-tech text-xs font-bold text-[#1A1D2B] uppercase mb-2">
                        Catatan Tambahan <span class="text-[#5C6475] font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        name="catatan"
                        rows="3"
                        placeholder="Tuliskan catatan khusus atau kendala..."
                        class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] p-3 font-mono-tech text-xs font-medium text-[#1A1D2B] placeholder:text-gray-400 focus:outline-none hard-shadow-sm"
                    >{{ old('catatan') }}</textarea>
                </div>

                {{-- Kotak Info Kebijakan --}}
                <div class="rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] p-3.5">
                    <p class="font-mono-tech text-[11px] font-medium text-[#1A1D2B] leading-relaxed">
                        💡 <strong>Kebijakan:</strong> Alat dengan kondisi <em>Baik</em> otomatis memulihkan stok inventaris. Pastikan data sudah sesuai sebelum mengirim.
                    </p>
                </div>

                {{-- Tombol Aksi Submit & Batal --}}
                <div class="pt-2 space-y-2.5 border-t-2 border-[#1A1D2B]/15">
                    <button
                        type="submit"
                        onclick="return confirm('Apakah data pengembalian alat sudah benar dan sesuai?')"
                        class="tactile-btn w-full text-center rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] py-3.5 px-6 font-mono-tech text-xs font-bold text-white hard-shadow hover:bg-[#1d69bf]"
                    >
                        SIMPAN & KIRIM PENGEMBALIAN &rarr;
                    </button>

                    <a href="{{ route('user.riwayat-peminjamans.show', $peminjaman->id) }}"
                       class="tactile-btn block w-full text-center rounded-xl border-2 border-[#1A1D2B] bg-white py-2.5 px-4 font-mono-tech text-xs font-bold text-[#1A1D2B] hover:bg-[#F2E7C9] transition">
                        BATALKAN
                    </a>
                </div>

            </div>

        </div>

    </form>

</div>

{{-- JAVASCRIPT KAMERA --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('[data-camera-card]').forEach(function (card) {

            const video = card.querySelector('[data-camera-video]');
            const preview = card.querySelector('[data-camera-preview]');
            const canvas = card.querySelector('[data-camera-canvas]');
            const input = card.querySelector('[data-camera-input]');

            const startButton = card.querySelector('[data-camera-start]');
            const captureButton = card.querySelector('[data-camera-capture]');
            const retakeButton = card.querySelector('[data-camera-retake]');
            const message = card.querySelector('[data-camera-message]');

            let stream = null;
            let previewUrl = null;

            function stopCamera() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
                video.srcObject = null;
            }

            // Aktifkan Kamera
            startButton.addEventListener('click', async function () {
                try {
                    if (!navigator.mediaDevices?.getUserMedia) {
                        throw new Error('Browser tidak mendukung akses kamera.');
                    }

                    stopCamera();

                    stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: 'environment' },
                        audio: false
                    });

                    video.srcObject = stream;
                    await video.play();

                    video.classList.remove('hidden');
                    preview.classList.add('hidden');

                    captureButton.classList.remove('hidden');
                    captureButton.disabled = false;

                    startButton.classList.add('hidden');
                    retakeButton.classList.add('hidden');

                    message.textContent = '// Kamera aktif. Ambil foto bukti.';

                } catch (error) {
                    message.textContent = 'Gagal mengaktifkan kamera: ' + error.message;
                }
            });

            // Ambil Foto
            captureButton.addEventListener('click', function () {
                if (!video.videoWidth || !video.videoHeight) {
                    message.textContent = 'Kamera belum siap sepenuhnya.';
                    return;
                }

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                canvas.toBlob(function (blob) {
                    if (!blob) {
                        message.textContent = 'Gagal memproses gambar.';
                        return;
                    }

                    const file = new window.File([blob], 'bukti-pengembalian.jpg', {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });

                    const transfer = new window.DataTransfer();
                    transfer.items.add(file);
                    input.files = transfer.files;

                    if (previewUrl) {
                        URL.revokeObjectURL(previewUrl);
                    }

                    previewUrl = URL.createObjectURL(blob);
                    preview.src = previewUrl;

                    preview.classList.remove('hidden');
                    video.classList.add('hidden');

                    stopCamera();

                    captureButton.classList.add('hidden');
                    startButton.classList.add('hidden');
                    retakeButton.classList.remove('hidden');

                    message.textContent = '// Foto bukti berhasil direkam.';

                }, 'image/jpeg', 0.90);
            });

            // Ambil Ulang Foto
            retakeButton.addEventListener('click', function () {
                input.value = '';
                preview.classList.add('hidden');
                preview.src = '';

                if (previewUrl) {
                    URL.revokeObjectURL(previewUrl);
                    previewUrl = null;
                }

                retakeButton.classList.add('hidden');
                startButton.classList.remove('hidden');

                message.textContent = '// Aktifkan kembali kamera untuk foto ulang.';
            });

            window.addEventListener('pagehide', stopCamera);
        });

    });
</script>

@endsection