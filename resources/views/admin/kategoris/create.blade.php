@extends('layouts.admin')

@section('content')

{{-- ============ PAGE HEADER ============ --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        {{-- Breadcrumb Pill --}}
        <div class="mb-2 inline-flex items-center gap-1.5 rounded-md border-2 border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-0.5 font-mono text-[11px] font-bold text-[#1A1D2B]">
            <a href="{{ route('admin.kategoris.index') }}" class="hover:text-[#237DE1] underline decoration-1 underline-offset-2">Kategori</a>
            <span>/</span>
            <span class="text-[#EC5928]">create</span>
        </div>

        <h1 class="text-[22px] sm:text-[26px] font-extrabold tracking-tight text-[#1A1D2B] leading-tight uppercase">
            Tambah Kategori Baru
        </h1>
        <p class="mt-1 font-mono text-[12px] font-medium text-[#5C6475]">
            // daftarkan jenis pengelompokan atau kategori alat inventaris baru
        </p>
    </div>

    <a href="{{ route('admin.kategoris.index') }}"
       class="tactile-btn inline-flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2 font-mono text-[12px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9]">
        <span>← Kembali</span>
    </a>
</div>

{{-- ============ FLASH / ERROR GLOBAL ============ --}}
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
                    Formulir Tambah Kategori
                </p>
            </div>
            <span class="font-mono-tech rounded border border-[#1A1D2B] bg-white px-2 py-0.5 text-[10px] font-extrabold text-[#1A1D2B]">
                METHOD: POST
            </span>
        </div>

        <form action="{{ route('admin.kategoris.store') }}" method="POST" class="p-5 sm:p-7">
            @csrf

            <div class="space-y-5">

                {{-- NAMA KATEGORI --}}
                <div>
                    <label for="nama_kategori" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#EC5928] px-1.5 py-0.5 text-[9px] text-white">01</span>
                        Nama Kategori
                        <span class="text-[#EC5928]">*</span>
                    </label>

                    <input
                        type="text"
                        id="nama_kategori"
                        name="nama_kategori"
                        value="{{ old('nama_kategori') }}"
                        required
                        maxlength="255"
                        placeholder="Contoh: Alat Ukur / Elektronik"
                        class="w-full rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5
                               font-mono text-[13.5px] font-medium text-[#1A1D2B]
                               placeholder:text-[#5C6475]
                               focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow
                               @error('nama_kategori') ring-2 ring-[#EC5928] ring-offset-0 bg-[#EC5928]/5 @enderror"
                    >

                    @error('nama_kategori')
                        <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">
                            ✕ {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label for="deskripsi" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#EC5928] px-1.5 py-0.5 text-[9px] text-white">02</span>
                        Deskripsi Kategori
                    </label>

                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="4"
                        placeholder="Keterangan singkat mengenai kategori alat ini..."
                           style="--deskripsi-border-color: {{ $errors->has('deskripsi') ? '#EC5928' : '#1A1D2B' }}"
                        class="w-full rounded-xl border-2 bg-white px-4 py-2.5
                               font-mono text-[13.5px] font-medium text-[#1A1D2B]
                               placeholder:text-[#5C6475]
                               focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow
                               border-[color:var(--deskripsi-border-color)]
                               @error('deskripsi') bg-[#EC5928]/5 @enderror"
                    >{{ old('deskripsi') }}</textarea>

                    @error('deskripsi')
                        <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">
                            ✕ {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- ==== ACTIONS ==== --}}
            <div class="mt-8 flex flex-col-reverse items-stretch gap-3 border-t-2 border-[#1A1D2B] pt-5 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('admin.kategoris.index') }}"
                   class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5 font-mono text-[12px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9]">
                    ← Batal
                </a>

                <div class="flex items-center gap-3">
                    <span class="hidden font-mono text-[10px] font-bold text-[#5C6475] sm:inline">
                        Tips: Tekan <kbd class="rounded border border-[#1A1D2B] bg-[#F2E7C9] px-1 py-0.5 text-[9px]">Ctrl + Enter</kbd>
                    </span>
                    <button type="submit"
                        class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-5 py-2.5 font-mono text-[12px] font-extrabold uppercase tracking-wider text-white shadow-[3px_3px_0px_#1A1D2B] hover:bg-[#1d69bf]">
                        <span>Simpan Kategori</span>
                        <span>→</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== SIDE PANEL ===== --}}
    <aside class="space-y-4">

        {{-- Card: Panduan Kategori --}}
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-[#F2E7C9] shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">
            <div class="border-b-2 border-[#1A1D2B] bg-[#1A1D2B] px-4 py-2.5 text-white">
                <p class="font-mono text-[11px] font-extrabold uppercase tracking-widest">
                    // Panduan Kategori
                </p>
            </div>
            <div class="space-y-3 p-4">
                <div class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded border border-[#1A1D2B] bg-[#237DE1] font-mono text-[10px] font-extrabold text-white">1</span>
                    <p class="font-mono text-[11px] font-medium text-[#1A1D2B] leading-tight">
                        Nama kategori harus <strong class="text-[#EC5928]">unik</strong> dan mendeskripsikan kelompok alat dengan jelas.
                    </p>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded border border-[#1A1D2B] bg-[#237DE1] font-mono text-[10px] font-extrabold text-white">2</span>
                    <p class="font-mono text-[11px] font-medium text-[#1A1D2B] leading-tight">
                        Deskripsi bersifat opsional namun dianjurkan untuk memberikan catatan spesifik.
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