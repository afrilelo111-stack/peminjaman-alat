@extends('layouts.admin')

@section('content')

{{-- ============ PAGE HEADER ============ --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        {{-- Breadcrumb Pill --}}
        <div class="mb-2 inline-flex items-center gap-1.5 rounded-md border-2 border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-0.5 font-mono text-[11px] font-bold text-[#1A1D2B]">
            <a href="{{ route('admin.alats.index') }}" class="hover:text-[#237DE1] underline decoration-1 underline-offset-2">Alat</a>
            <span>/</span>
            <span class="text-[#EC5928]">create</span>
        </div>

        <h1 class="text-[22px] sm:text-[26px] font-extrabold tracking-tight text-[#1A1D2B] leading-tight uppercase">
            Tambah Unit Alat Baru
        </h1>
        <p class="mt-1 font-mono text-[12px] font-medium text-[#5C6475]">
            // daftarkan peralatan baru ke dalam database inventaris sistem
        </p>
    </div>

    <a href="{{ route('admin.alats.index') }}"
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
                <span class="h-2.5 w-2.5 rounded-full border border-[#1A1D2B] bg-[#EC5928]"></span>
                <p class="font-mono text-[11px] font-bold uppercase tracking-widest text-[#1A1D2B]">
                    Formulir Inventaris Alat
                </p>
            </div>
            <span class="font-mono-tech rounded border border-[#1A1D2B] bg-white px-2 py-0.5 text-[10px] font-extrabold text-[#1A1D2B]">
                METHOD: POST (MULTIPART)
            </span>
        </div>

        <form action="{{ route('admin.alats.store') }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-7">
            @csrf

            <div class="space-y-5">

                {{-- 01. KATEGORI --}}
                <div>
                    <label for="kategori_id" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#EC5928] px-1.5 py-0.5 text-[9px] text-white">01</span>
                        Kategori Alat
                        <span class="text-[#EC5928]">*</span>
                    </label>

                    <select
                        id="kategori_id"
                        name="kategori_id"
                        required
                        class="w-full rounded-xl border-2 px-4 py-2.5 font-mono text-[13px] font-bold text-[#1A1D2B] focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow cursor-pointer {{ $errors->has('kategori_id') ? 'border-[#EC5928] bg-[#EC5928]/5' : 'border-[#1A1D2B] bg-white' }}"
                    >
                        <option value="">-- Pilih Kategori Alat --</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" @selected(old('kategori_id') == $kategori->id)>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    @error('kategori_id')
                        <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">✕ {{ $message }}</p>
                    @enderror
                </div>

                {{-- 02 & 03. NAMA ALAT & KODE ALAT --}}
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="nama_alat" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                            <span class="rounded border border-[#1A1D2B] bg-[#EC5928] px-1.5 py-0.5 text-[9px] text-white">02</span>
                            Nama Alat
                            <span class="text-[#EC5928]">*</span>
                        </label>

                        <input
                            type="text"
                            id="nama_alat"
                            name="nama_alat"
                            value="{{ old('nama_alat') }}"
                            required
                            maxlength="255"
                            placeholder="Contoh: Bor Listrik Makita"
                            class="w-full rounded-xl border-2 px-4 py-2.5 font-mono text-[13.5px] font-medium text-[#1A1D2B] placeholder:text-[#5C6475] focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow {{ $errors->has('nama_alat') ? 'border-[#EC5928] bg-[#EC5928]/5' : 'border-[#1A1D2B] bg-white' }}"
                        >

                        @error('nama_alat')
                            <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">✕ {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kode_alat" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                            <span class="rounded border border-[#1A1D2B] bg-[#EC5928] px-1.5 py-0.5 text-[9px] text-white">03</span>
                            Kode Alat / SKU
                            <span class="text-[#EC5928]">*</span>
                        </label>

                        <input
                            type="text"
                            id="kode_alat"
                            name="kode_alat"
                            value="{{ old('kode_alat') }}"
                            required
                            maxlength="255"
                            placeholder="Contoh: ALT-MKT-01"
                            class="w-full rounded-xl border-2 px-4 py-2.5 font-mono text-[13px] font-bold text-[#1A1D2B] placeholder:text-[#5C6475] focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow {{ $errors->has('kode_alat') ? 'border-[#EC5928] bg-[#EC5928]/5' : 'border-[#1A1D2B] bg-white' }}"
                        >

                        @error('kode_alat')
                            <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">✕ {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 04 & 05. STOK & KONDISI --}}
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="stok" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                            <span class="rounded border border-[#1A1D2B] bg-[#237DE1] px-1.5 py-0.5 text-[9px] text-white">04</span>
                            Jumlah Stok Unit
                            <span class="text-[#EC5928]">*</span>
                        </label>

                        <input
                            type="number"
                            id="stok"
                            name="stok"
                            value="{{ old('stok', 0) }}"
                            min="0"
                            required
                            class="w-full rounded-xl border-2 px-4 py-2.5 font-mono text-[13.5px] font-bold text-[#1A1D2B] focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow {{ $errors->has('stok') ? 'border-[#EC5928] bg-[#EC5928]/5' : 'border-[#1A1D2B] bg-white' }}"
                        >

                        @error('stok')
                            <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">✕ {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kondisi" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                            <span class="rounded border border-[#1A1D2B] bg-[#237DE1] px-1.5 py-0.5 text-[9px] text-white">05</span>
                            Kondisi Fisik
                            <span class="text-[#EC5928]">*</span>
                        </label>

                        <select
                            id="kondisi"
                            name="kondisi"
                            required
                            class="w-full rounded-xl border-2 px-4 py-2.5 font-mono text-[13px] font-bold text-[#1A1D2B] focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow cursor-pointer {{ $errors->has('kondisi') ? 'border-[#EC5928] bg-[#EC5928]/5' : 'border-[#1A1D2B] bg-white' }}"
                        >
                            <option value="">-- Pilih Kondisi Alat --</option>
                            <option value="Baik" @selected(old('kondisi') == 'Baik')>Baik (Normal)</option>
                            <option value="Rusak" @selected(old('kondisi') == 'Rusak')>Rusak (Tidak Berfungsi)</option>
                            <option value="Perlu Perbaikan" @selected(old('kondisi') == 'Perlu Perbaikan')>Perlu Perbaikan (Maintenance)</option>
                        </select>

                        @error('kondisi')
                            <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">✕ {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 06. DESKRIPSI ALAT --}}
                <div>
                    <label for="deskripsi" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#237DE1] px-1.5 py-0.5 text-[9px] text-white">06</span>
                        Deskripsi Alat
                    </label>

                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="4"
                        maxlength="5000"
                        placeholder="Jelaskan fungsi, spesifikasi, merek, atau informasi tambahan mengenai alat..."
                        class="w-full rounded-xl border-2 px-4 py-3 font-mono text-[13px] font-medium text-[#1A1D2B] placeholder:text-[#5C6475] focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow resize-y {{ $errors->has('deskripsi') ? 'border-[#EC5928] bg-[#EC5928]/5' : 'border-[#1A1D2B] bg-white' }}"
                    >{{ old('deskripsi') }}</textarea>

                    <div class="mt-1 flex justify-between">
                        <p class="font-mono text-[10px] text-[#5C6475]">
                            Maksimal 5.000 karakter.
                        </p>
                        <p class="font-mono text-[10px] text-[#5C6475]">
                            Opsional
                        </p>
                    </div>

                    @error('deskripsi')
                        <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">✕ {{ $message }}</p>
                    @enderror
                </div>

                {{-- 07. UPLOAD GAMBAR --}}
                <div>
                    <label for="gambar" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#237DE1] px-1.5 py-0.5 text-[9px] text-white">07</span>
                        Foto Dokumentasi Alat
                    </label>

                    <div class="relative flex items-center rounded-xl border-2 border-[#1A1D2B] bg-white p-2 shadow-[2px_2px_0px_#1A1D2B]">
                        <input
                            type="file"
                            id="gambar"
                            name="gambar"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="w-full font-mono text-[12px] text-[#5C6475] file:mr-4 file:rounded-lg file:border-2 file:border-[#1A1D2B] file:bg-[#F2E7C9] file:px-3 file:py-1.5 file:font-mono file:text-[11px] file:font-extrabold file:text-[#1A1D2B] hover:file:bg-[#237DE1] hover:file:text-white file:transition cursor-pointer"
                        >
                    </div>
                    <p class="mt-1.5 font-mono text-[10.5px] text-[#5C6475]">
                        Format: JPG, JPEG, PNG, WEBP. Ukuran file maksimum 2 MB.
                    </p>

                    @error('gambar')
                        <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">✕ {{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- ==== ACTIONS ==== --}}
            <div class="mt-8 flex flex-col-reverse items-stretch gap-3 border-t-2 border-[#1A1D2B] pt-5 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('admin.alats.index') }}"
                   class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5 font-mono text-[12px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9]">
                    ← Batal
                </a>

                <div class="flex items-center gap-3">
                    <span class="hidden font-mono text-[10px] font-bold text-[#5C6475] sm:inline">
                        Shortcut: <kbd class="rounded border border-[#1A1D2B] bg-[#F2E7C9] px-1 py-0.5 text-[9px]">Ctrl + Enter</kbd>
                    </span>
                    <button type="submit"
                        class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-5 py-2.5 font-mono text-[12px] font-extrabold uppercase tracking-wider text-white shadow-[3px_3px_0px_#1A1D2B] hover:bg-[#1d69bf]">
                        <span>Simpan Alat Baru</span>
                        <span>→</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== SIDE PANEL (Panduan & Informasi) ===== --}}
    <aside class="space-y-4">

        {{-- Card: Panduan Input --}}
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-[#F2E7C9] shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">
            <div class="border-b-2 border-[#1A1D2B] bg-[#1A1D2B] px-4 py-2.5 text-white">
                <p class="font-mono text-[11px] font-extrabold uppercase tracking-widest">
                    // Panduan Inventaris
                </p>
            </div>
            <div class="space-y-3 p-4">
                <div class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded border border-[#1A1D2B] bg-[#237DE1] font-mono text-[10px] font-extrabold text-white">1</span>
                    <p class="font-mono text-[11px] font-medium text-[#1A1D2B] leading-tight">
                        Pastikan <strong class="text-[#EC5928]">Kode Alat / SKU</strong> bersifat unik dan belum pernah didaftarkan.
                    </p>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded border border-[#1A1D2B] bg-[#237DE1] font-mono text-[10px] font-extrabold text-white">2</span>
                    <p class="font-mono text-[11px] font-medium text-[#1A1D2B] leading-tight">
                        Jumlah stok akan diperbarui otomatis saat transaksi peminjaman atau pengembalian divalidasi.
                    </p>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded border border-[#1A1D2B] bg-[#237DE1] font-mono text-[10px] font-extrabold text-white">3</span>
                    <p class="font-mono text-[11px] font-medium text-[#1A1D2B] leading-tight">
                        Unggah foto dengan pencahayaan yang jelas agar memudahkan identifikasi fisik alat.
                    </p>
                </div>
            </div>
        </div>

    </aside>
</div>

{{-- ============ SCRIPT SUBMIT SHORTCUT ============ --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('keydown', function (e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'Enter') {
                e.preventDefault();
                document.querySelector('form')?.requestSubmit();
            }
        });
    });
</script>

@endsection