<x-guest-layout>

    <div class="max-w-md mx-auto space-y-5">

        {{-- KARTU UTAMA LOGIN DENGAN ESTETIKA NEO-BRUTALIST & RETRO --}}
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-white hard-shadow overflow-hidden">
            
            {{-- TOP ACCENT BAR (Kombinasi 3 Warna Utama) --}}
            <div class="bg-[#237DE1] border-b-2 border-[#1A1D2B] px-6 py-2.5 flex items-center justify-between">
                <span class="font-mono-tech text-[10px] font-extrabold uppercase tracking-widest text-white">
                    // PORTAL AKSES PENGGUNA
                </span>
                <div class="flex items-center gap-1.5">
                    <span class="h-2.5 w-2.5 rounded-full border border-[#1A1D2B] bg-[#F2E7C9]"></span>
                    <span class="h-2.5 w-2.5 rounded-full border border-[#1A1D2B] bg-[#EC5928]"></span>
                    <span class="h-2.5 w-2.5 rounded-full border border-[#1A1D2B] bg-emerald-300"></span>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">

                {{-- HEADER JUDUL --}}
                <div class="space-y-1.5 text-center border-b-2 border-dashed border-[#1A1D2B]/20 pb-5">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#1A1D2B] uppercase tracking-tight">
                        Selamat Datang Kembali
                    </h2>
                    <p class="text-xs font-medium text-[#5C6475] leading-relaxed">
                        Masuk menggunakan akun terdaftar untuk mengajukan peminjaman dan memantau status alat.
                    </p>
                </div>

                {{-- STATUS SESI --}}
                @if (session('status'))
                    <div class="rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] p-3.5 hard-shadow-sm flex items-center gap-2.5">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-white font-mono-tech text-xs font-extrabold text-[#237DE1]">✓</span>
                        <p class="font-mono-tech text-xs font-bold text-[#1A1D2B]">{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4" onsubmit="const btn = this.querySelector('button[type=submit]'); btn.disabled = true; btn.innerHTML = 'MEMVERIFIKASI AKUN...';">
                    @csrf

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
                                   autofocus 
                                   autocomplete="username" 
                                   placeholder="nama@instansi.ac.id" />
                        </div>
                        
                        @error('email')
                            <p class="font-mono-tech text-[11px] font-bold text-[#EC5928] mt-1">
                                ! {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- KATA SANDI DENGAN TOMBOL INTIP (SHOW/HIDE) --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block font-mono-tech text-xs font-extrabold text-[#1A1D2B] uppercase">
                                Kata Sandi <span class="text-[#EC5928]">*</span>
                            </label>
                        </div>

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
                                   autocomplete="current-password" 
                                   placeholder="Masukkan kata sandi" />

                            <button type="button" 
                                    onclick="togglePasswordVisibility()"
                                    title="Tampilkan / Sembunyikan Sandi"
                                    class="absolute right-2.5 rounded-lg border border-transparent p-1 text-[#5C6475] hover:border-[#1A1D2B] hover:bg-[#F2E7C9] hover:text-[#1A1D2B] transition">
                                <svg id="eye-icon-open" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-icon-closed" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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

                    {{-- REMEMBER ME & TOMBOL MENU LUPA SANDI (FORGOT PASSWORD) --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pt-1">
                        <label for="remember_me" class="inline-flex items-center gap-2.5 cursor-pointer select-none group">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   class="h-4 w-4 rounded border-2 border-[#1A1D2B] bg-[#F8F5EE] text-[#237DE1] focus:ring-0 focus:ring-offset-0 cursor-pointer" 
                                   name="remember">
                            <span class="font-mono-tech text-xs font-bold text-[#5C6475] group-hover:text-[#1A1D2B] transition">
                                Ingat sesi masuk saya
                            </span>
                        </label>

                        @if (\Illuminate\Support\Facades\Route::has('password.request'))
                            <a href="{{ route('password.request') }}" 
                               class="inline-flex items-center gap-1 font-mono-tech text-xs font-extrabold text-[#EC5928] hover:text-[#237DE1] transition">
                                <span>Lupa Kata Sandi?</span>
                                <span>&rarr;</span>
                            </a>
                        @endif
                    </div>

                    {{-- TOMBOL SUBMIT (Warna Utama #237DE1) --}}
                    <div class="pt-2">
                        <button type="submit" 
                                class="tactile-btn w-full flex items-center justify-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] py-3.5 px-6 font-mono-tech text-xs font-extrabold uppercase tracking-wider text-white hard-shadow hover:bg-[#1d69bf] disabled:opacity-60 transition">
                            <span>MASUK KE AKUN</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        {{-- KOTAK INFORMASI / REGISTRASI AKUN (Background #F2E7C9 & Tombol Aksi #EC5928) --}}
        <div class="rounded-2xl border-2 border-[#1A1D2B] bg-[#F2E7C9] p-4 hard-shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            @if (\Illuminate\Support\Facades\Route::has('register'))
                <div>
                    <p class="font-mono-tech text-xs font-extrabold text-[#1A1D2B]">Belum punya akun?</p>
                    <p class="text-[11px] font-medium text-[#5C6475]">Daftar sekarang untuk meminjam inventaris.</p>
                </div>
                <a href="{{ route('register') }}" 
                   class="tactile-btn shrink-0 rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] px-4 py-2 font-mono-tech text-xs font-extrabold text-white hard-shadow-sm hover:bg-[#d94c20] transition">
                    DAFTAR AKUN &rarr;
                </a>
            @else
                <div class="w-full text-center">
                    <p class="font-mono-tech text-[11.5px] font-bold text-[#1A1D2B]">
                        Belum memiliki akun akses? <span class="text-[#EC5928]">Hubungi petugas laboratorium</span> untuk aktivasi.
                    </p>
                </div>
            @endif
        </div>

    </div>

    {{-- SCRIPT TOGGLE SHOW/HIDE PASSWORD --}}
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-icon-open');
            const eyeClosed = document.getElementById('eye-icon-closed');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>

</x-guest-layout>