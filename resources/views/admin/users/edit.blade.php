@extends('layouts.admin')

@section('content')

@php
    $roles = [
        'admin'    => ['label' => 'Admin',   'desc' => 'Akses penuh sistem & manajemen',   'color' => '#EC5928'],
        'petugas'  => ['label' => 'Petugas', 'desc' => 'Kelola sirkulasi & transaksi alat', 'color' => '#237DE1'],
        'user'     => ['label' => 'Peminjam',    'desc' => 'Akses peminjaman alat mandiri',     'color' => '#F2E7C9'],
    ];
    $oldRole = old('role', $user->role);
@endphp

{{-- ============ PAGE HEADER ============ --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        {{-- Breadcrumb Pill --}}
        <div class="mb-2 inline-flex items-center gap-1.5 rounded-md border-2 border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-0.5 font-mono text-[11px] font-bold text-[#1A1D2B]">
            <a href="{{ route('admin.users.index') }}" class="hover:text-[#237DE1] underline decoration-1 underline-offset-2">Users</a>
            <span>/</span>
            <span class="text-[#EC5928]">edit</span>
        </div>

        <h1 class="text-[22px] sm:text-[26px] font-extrabold tracking-tight text-[#1A1D2B] leading-tight uppercase">
            Edit Pengguna: {{ $user->name }}
        </h1>
        <p class="mt-1 font-mono text-[12px] font-medium text-[#5C6475]">
            // perbarui informasi profil, hak akses, atau kredensial akun
        </p>
    </div>

    <a href="{{ route('admin.users.index') }}"
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

{{-- ============ GRID: FORM + SIDE PANEL ============ --}}
<div class="grid gap-6 lg:grid-cols-[1fr_320px]">

    {{-- ===== FORM CARD ===== --}}
    <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center justify-between border-b-2 border-[#1A1D2B] bg-[#F2E7C9]/60 px-5 py-3">
            <div class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full border border-[#1A1D2B] bg-[#237DE1]"></span>
                <p class="font-mono text-[11px] font-bold uppercase tracking-widest text-[#1A1D2B]">
                    Formulir Edit Akun [ID: #{{ $user->id }}]
                </p>
            </div>
            <span class="font-mono-tech rounded border border-[#1A1D2B] bg-white px-2 py-0.5 text-[10px] font-extrabold text-[#1A1D2B]">
                METHOD: PUT
            </span>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-5 sm:p-7">
            @csrf
            @method('PUT')

            <div class="space-y-5">

                {{-- === 01 NAMA === --}}
                <div>
                    <label for="name" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#237DE1] px-1.5 py-0.5 text-[9px] text-white">01</span>
                        Nama Lengkap
                        <span class="text-[#EC5928]">*</span>
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        required
                        placeholder="Contoh: Budi Santoso"
                           class="w-full rounded-xl border-2 {{ $errors->has('name') ? 'border-[#EC5928] bg-[#EC5928]/5' : 'border-[#1A1D2B]' }} bg-white px-4 py-2.5
                               font-mono text-[13.5px] font-medium text-[#1A1D2B]
                               placeholder:text-[#5C6475]
                               focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow
                               "
                    >

                    @error('name')
                        <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">
                            ✕ {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- === 02 EMAIL === --}}
                <div>
                    <label for="email" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#237DE1] px-1.5 py-0.5 text-[9px] text-white">02</span>
                        Alamat Email
                        <span class="text-[#EC5928]">*</span>
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        placeholder="budi@example.com"
                           class="w-full rounded-xl border-2 {{ $errors->has('email') ? 'border-[#EC5928] bg-[#EC5928]/5' : 'border-[#1A1D2B]' }} bg-white px-4 py-2.5
                               font-mono text-[13.5px] font-medium text-[#1A1D2B]
                               placeholder:text-[#5C6475]
                               focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow
                               "
                    >

                    @error('email')
                        <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">
                            ✕ {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- === 03 ROLE (Segmented Selector) === --}}
                <div>
                    <label class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                        <span class="rounded border border-[#1A1D2B] bg-[#237DE1] px-1.5 py-0.5 text-[9px] text-white">03</span>
                        Role Akses Pengguna
                        <span class="text-[#EC5928]">*</span>
                    </label>

                    <div class="grid gap-3 sm:grid-cols-3">
                        @foreach($roles as $value => $meta)
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="role"
                                    value="{{ $value }}"
                                    class="peer sr-only"
                                    {{ $oldRole === $value ? 'checked' : '' }}
                                    required
                                >
                                <div class="rounded-xl border-2 border-[#1A1D2B] bg-white p-3.5 transition
                                            peer-checked:bg-[#237DE1] peer-checked:text-white peer-checked:shadow-[3px_3px_0px_#1A1D2B]
                                            hover:bg-[#F2E7C9]/50">
                                    <div class="flex items-center justify-between">
                                        <span class="font-mono text-[12px] font-extrabold uppercase tracking-wider">
                                            {{ $meta['label'] }}
                                        </span>
                                        <span class="h-3.5 w-3.5 rounded-full border-2 border-[#1A1D2B] bg-white peer-checked:bg-[#EC5928]"></span>
                                    </div>
                                    <p class="mt-1 font-mono text-[10px] font-medium opacity-75 leading-relaxed">
                                        {{ $meta['desc'] }}
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error('role')
                        <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">
                            ✕ {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- === PEMBATAS PASSWORD SECTION === --}}
                <div class="border-t-2 border-dashed border-[#1A1D2B]/30 pt-4 mt-2">
                    <div class="flex items-center gap-2">
                        <span class="rounded border border-[#1A1D2B] bg-[#EC5928] px-1.5 py-0.5 font-mono text-[9px] font-extrabold text-white">SECURE</span>
                        <h3 class="font-mono text-[12px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                            Pembaruan Password
                        </h3>
                    </div>
                    <p class="font-mono text-[11px] text-[#5C6475] mt-1">
                        Kosongkan bagian password di bawah ini jika tidak ingin mengubah password akun.
                    </p>
                </div>

                {{-- === 04 & 05 PASSWORD BARU === --}}
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                            <span class="rounded border border-[#1A1D2B] bg-[#5C6475] px-1.5 py-0.5 text-[9px] text-white">04</span>
                            Password Baru
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            placeholder="Min. 8 karakter (opsional)"
                            class="w-full rounded-xl border-2 {{ $errors->has('password') ? 'border-[#EC5928] bg-[#EC5928]/5' : 'border-[#1A1D2B] bg-white' }} px-4 py-2.5
                                   font-mono text-[13.5px] font-medium text-[#1A1D2B]
                                   placeholder:text-[#5C6475]
                                   focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow"
                        >

                        @error('password')
                            <p class="mt-1.5 font-mono text-[11px] font-bold text-[#EC5928]">
                                ✕ {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 flex items-center gap-2 font-mono text-[11px] font-extrabold uppercase tracking-wider text-[#1A1D2B]">
                            <span class="rounded border border-[#1A1D2B] bg-[#5C6475] px-1.5 py-0.5 text-[9px] text-white">05</span>
                            Konfirmasi Password Baru
                        </label>

                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            placeholder="Ulangi password baru"
                            class="w-full rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5
                                   font-mono text-[13.5px] font-medium text-[#1A1D2B]
                                   placeholder:text-[#5C6475]
                                   focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow"
                        >
                    </div>
                </div>

            </div>

            {{-- ==== ACTIONS ==== --}}
            <div class="mt-8 flex flex-col-reverse items-stretch gap-3 border-t-2 border-[#1A1D2B] pt-5 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('admin.users.index') }}"
                   class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2.5 font-mono text-[12px] font-bold text-[#1A1D2B] shadow-[2px_2px_0px_#1A1D2B] hover:bg-[#F2E7C9]">
                    ← Batal
                </a>

                <div class="flex items-center gap-3">
                    <span class="hidden font-mono text-[10px] font-bold text-[#5C6475] sm:inline">
                        Tips: Tekan <kbd class="rounded border border-[#1A1D2B] bg-[#F2E7C9] px-1 py-0.5 text-[9px]">Ctrl + Enter</kbd>
                    </span>
                    <button type="submit"
                        class="tactile-btn inline-flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-5 py-2.5 font-mono text-[12px] font-extrabold uppercase tracking-wider text-white shadow-[3px_3px_0px_#1A1D2B] hover:bg-[#1d69bf]">
                        <span>Simpan Perubahan</span>
                        <span>→</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== SIDE PANEL (Panduan & Live Preview) ===== --}}
    <aside class="space-y-4">

        {{-- Card: Meta Akun --}}
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-[#F2E7C9] shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">
            <div class="border-b-2 border-[#1A1D2B] bg-[#1A1D2B] px-4 py-2.5 text-white">
                <p class="font-mono text-[11px] font-extrabold uppercase tracking-widest">
                    // Metadata Akun
                </p>
            </div>
            <div class="space-y-3 p-4 font-mono text-[11px] text-[#1A1D2B]">
                <div>
                    <span class="font-bold opacity-60">Dibuat Sejak:</span>
                    <p class="font-semibold">{{ $user->created_at->translatedFormat('d M Y, H:i') }}</p>
                </div>
                <div>
                    <span class="font-bold opacity-60">Terakhir Diperbarui:</span>
                    <p class="font-semibold">{{ $user->updated_at->translatedFormat('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Live Preview Akun --}}
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white shadow-[4px_4px_0px_#1A1D2B] overflow-hidden">
            <div class="border-b-2 border-[#1A1D2B] bg-[#EC5928] px-4 py-2.5 text-white">
                <p class="font-mono text-[11px] font-extrabold uppercase tracking-widest">
                    // Live Preview
                </p>
            </div>
            <div class="flex flex-col items-center gap-3 p-5 text-center">
                <div id="preview-initial"
                     class="flex h-14 w-14 items-center justify-center rounded-xl border-2 border-[#1A1D2B]
                            bg-[#237DE1] font-mono text-xl font-extrabold text-white shadow-[2px_2px_0px_#1A1D2B]">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0 w-full">
                    <p id="preview-name" class="truncate text-[14px] font-extrabold text-[#1A1D2B]">
                        {{ $user->name }}
                    </p>
                    <p id="preview-email" class="mt-0.5 font-mono text-[11px] text-[#5C6475] truncate">
                        {{ $user->email }}
                    </p>
                </div>
                <span id="preview-role"
                      class="inline-block rounded-md border-2 border-[#1A1D2B] px-2.5 py-0.5
                             font-mono text-[10px] font-extrabold uppercase tracking-wider shadow-[2px_2px_0px_#1A1D2B]
                             {{ $user->role === 'admin' ? 'bg-[#EC5928] text-white' : ($user->role === 'petugas' ? 'bg-[#237DE1] text-white' : 'bg-[#F2E7C9] text-[#1A1D2B]') }}">
                    ROLE: {{ strtoupper($user->role) }}
                </span>
            </div>
        </div>

    </aside>
</div>

{{-- ============ LIVE PREVIEW SCRIPT ============ --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nameInput  = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const roleRadios = document.querySelectorAll('input[name="role"]');

        const pInitial = document.getElementById('preview-initial');
        const pName    = document.getElementById('preview-name');
        const pEmail   = document.getElementById('preview-email');
        const pRole    = document.getElementById('preview-role');

        function updatePreview() {
            const name  = nameInput.value.trim();
            const email = emailInput.value.trim();
            const role  = document.querySelector('input[name="role"]:checked');

            pInitial.textContent = name ? name.charAt(0).toUpperCase() : '?';
            pName.textContent    = name || 'Nama Pengguna';
            pEmail.textContent   = email || 'email@domain.com';

            if (role) {
                const roleLabel = {
                    admin: 'ADMIN',
                    petugas: 'PETUGAS',
                    user: 'PEMINJAM'
                };

                pRole.textContent = 'ROLE: ' + roleLabel[role.value];
                pRole.style.backgroundColor =
                    role.value === 'admin'   ? '#EC5928' :
                    role.value === 'petugas' ? '#237DE1' : '#F2E7C9';
                pRole.style.color =
                    role.value === 'user' ? '#1A1D2B' : '#FFFFFF';
            } else {
                pRole.textContent = 'ROLE: -';
                pRole.style.backgroundColor = '#F2E7C9';
                pRole.style.color = '#1A1D2B';
            }
        }

        nameInput?.addEventListener('input', updatePreview);
        emailInput?.addEventListener('input', updatePreview);
        roleRadios.forEach(r => r.addEventListener('change', updatePreview));

        // Shortcut Ctrl + Enter untuk submit form
        document.addEventListener('keydown', function (e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'Enter') {
                e.preventDefault();
                document.querySelector('form')?.requestSubmit();
            }
        });
    });
</script>

@endsection