<x-guest-layout>

    <div class="max-w-md mx-auto space-y-5">

        {{-- KARTU UTAMA REGISTRASI --}}
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white hard-shadow overflow-hidden">
            
            {{-- TOP ACCENT BAR (Kombinasi Warna Serasi) --}}
            <div class="bg-[#EC5928] border-b-2 border-[#1A1D2B] px-6 py-2.5 flex items-center justify-between">
                <span class="font-mono-tech text-[10px] font-extrabold uppercase tracking-widest text-white">
                    // PENDAFTARAN AKUN BARU
                </span>
                <div class="flex items-center gap-1.5">
                    <span class="h-2.5 w-2.5 rounded-full border border-[#1A1D2B] bg-[#F2E7C9]"></span>
                    <span class="h-2.5 w-2.5 rounded-full border border-[#1A1D2B] bg-[#237DE1]"></span>
                    <span class="h-2.5 w-2.5 rounded-full border border-[#1A1D2B] bg-emerald-300"></span>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">

                {{-- HEADER JUDUL --}}
                <div class="space-y-1.5 text-center border-b-2 border-dashed border-[#1A1D2B]/20 pb-5">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#1A1D2B] uppercase tracking-tight">
                        Buat Akun Inventaris
                    </h2>
                    <p class="text-xs font-medium text-[#5C6475] leading-relaxed">
                        Lengkapi data diri Anda untuk mulai mengajukan peminjaman alat laboratorium.
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4" onsubmit="const btn = this.querySelector('button[type=submit]'); btn.disabled = true; btn.innerHTML = 'MENDAFTARKAN AKUN...';">
                    @csrf

                    {{-- NAMA LENGKAP --}}
                    <div class="space-y-1.5">
                        <label for="name" class="block font-mono-tech text-xs font-extrabold text-[#1A1D2B] uppercase">
                            Nama Lengkap <span class="text-[#EC5928]">*</span>
                        </label>
                        
                        <div class="relative flex items-center">
                            <span class="absolute left-3.5 text-[#5C6475] pointer-events-none">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input id="name" 
                                   class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] pl-10 pr-3.5 py-2.5 font-mono-tech text-xs font-bold text-[#1A1D2B] placeholder:text-gray-400 placeholder:font-medium focus:bg-white focus:outline-none hard-shadow-sm transition" 
                                   type="text" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="name" 
                                   placeholder="Nama lengkap Anda" />
                        </div>
                        
                        @error('name')
                            <p class="font-mono-tech text-[11px] font-bold text-[#EC5928] mt-1">
                                ! {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- ALAMAT EMAIL --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block font-mono-tech text-xs font-extrabold text-[#1A1D2B] uppercase">
                            Alamat Email <span class="text-[#EC5928]">*</span>
                        </label>
                        
                        <div class="relative flex items-center">
                            <span class="absolute left-3.5 text-[#5C6475] pointer-events-none">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </span>
                            <input id="email" 
                                   class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] pl-10 pr-3.5 py-2.5 font-mono-tech text-xs font-bold text-[#1A1D2B] placeholder:text-gray-400 placeholder:font-medium focus:bg-white focus:outline-none hard-shadow-sm transition" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="username" 
                                   placeholder="nama@instansi.ac.id" />
                        </div>
                        
                        @error('email')
                            <p class="font-mono-tech text-[11px] font-bold text-[#EC5928] mt-1">
                                ! {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- KATA SANDI --}}
                    <div class="space-y-1.5">
                        <label for="password" class="block font-mono-tech text-xs font-extrabold text-[#1A1D2B] uppercase">
                            Kata Sandi <span class="text-[#EC5928]">*</span>
                        </label>

                        <div class="relative flex items-center">
                            <span class="absolute left-3.5 text-[#5C6475] pointer-events-none">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>

                            <input id="password" 
                                   class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] pl-10 pr-11 py-2.5 font-mono-tech text-xs font-bold text-[#1A1D2B] placeholder:text-gray-400 placeholder:font-medium focus:bg-white focus:outline-none hard-shadow-sm transition"
                                   type="password"
                                   name="password"
                                   required 
                                   autocomplete="new-password" 
                                   placeholder="Minimal 8 karakter" />

                            <button type="button" 
                                    onclick="toggleVisibility('password', 'eye-open-1', 'eye-closed-1')"
                                    title="Tampilkan / Sembunyikan Sandi"
                                    class="absolute right-2.5 rounded-lg border border-transparent p-1 text-[#5C6475] hover:border-[#1A1D2B] hover:bg-[#F2E7C9] hover:text-[#1A1D2B] transition">
                                <svg id="eye-open-1" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-closed-1" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>

                        @error('password')
                            <p class="font-mono-tech text-[11px] font-bold text-[#EC5928] mt-1">
                                ! {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- KONFIRMASI KATA SANDI --}}
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block font-mono-tech text-xs font-extrabold text-[#1A1D2B] uppercase">
                            Konfirmasi Kata Sandi <span class="text-[#EC5928]">*</span>
                        </label>

                        <div class="relative flex items-center">
                            <span class="absolute left-3.5 text-[#5C6475] pointer-events-none">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>

                            <input id="password_confirmation" 
                                   class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] pl-10 pr-11 py-2.5 font-mono-tech text-xs font-bold text-[#1A1D2B] placeholder:text-gray-400 placeholder:font-medium focus:bg-white focus:outline-none hard-shadow-sm transition"
                                   type="password"
                                   name="password_confirmation"
                                   required 
                                   autocomplete="new-password" 
                                   placeholder="Ulangi kata sandi" />

                            <button type="button" 
                                    onclick="toggleVisibility('password_confirmation', 'eye-open-2', 'eye-closed-2')"
                                    title="Tampilkan / Sembunyikan Konfirmasi Sandi"
                                    class="absolute right-2.5 rounded-lg border border-transparent p-1 text-[#5C6475] hover:border-[#1A1D2B] hover:bg-[#F2E7C9] hover:text-[#1A1D2B] transition">
                                <svg id="eye-open-2" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-closed-2" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>

                        @error('password_confirmation')
                            <p class="font-mono-tech text-[11px] font-bold text-[#EC5928] mt-1">
                                ! {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- TOMBOL SUBMIT (Warna Utama #EC5928) --}}
                    <div class="pt-3">
                        <button type="submit" 
                                class="tactile-btn w-full flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] py-3.5 px-6 font-mono-tech text-xs font-extrabold uppercase tracking-wider text-white hard-shadow hover:bg-[#d94c20] transition">
                            <span>DAFTARKAN AKUN</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        {{-- KOTAK KEMBALI KE HALAMAN LOGIN (Background #F2E7C9 & Tombol #237DE1) --}}
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-[#F2E7C9] p-4 hard-shadow-sm flex items-center justify-between">
            <div>
                <p class="font-mono-tech text-xs font-extrabold text-[#1A1D2B]">Sudah memiliki akun?</p>
                <p class="text-[11px] font-medium text-[#5C6475]">Masuk untuk mulai mengelola peminjaman.</p>
            </div>
            <a href="{{ route('login') }}" 
               class="tactile-btn shrink-0 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-2 font-mono-tech text-xs font-extrabold text-white hard-shadow-sm hover:bg-[#1d69bf] transition">
                MASUK &rarr;
            </a>
        </div>

    </div>

    {{-- SCRIPT TOGGLE SHOW/HIDE PASSWORD --}}
    <script>
        function toggleVisibility(fieldId, openIconId, closedIconId) {
            const field = document.getElementById(fieldId);
            const openIcon = document.getElementById(openIconId);
            const closedIcon = document.getElementById(closedIconId);

            if (field.type === 'password') {
                field.type = 'text';
                openIcon.classList.add('hidden');
                closedIcon.classList.remove('hidden');
            } else {
                field.type = 'password';
                openIcon.classList.remove('hidden');
                closedIcon.classList.add('hidden');
            }
        }
    </script>

</x-guest-layout>