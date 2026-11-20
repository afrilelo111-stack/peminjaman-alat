<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Katalog Publik') — Peminjaman Alat</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F5EE;
            color: #1A1D2B;
            -webkit-tap-highlight-color: transparent;
        }
        .font-mono-tech {
            font-family: 'JetBrains Mono', monospace;
        }
        .hard-shadow {
            box-shadow: 4px 4px 0px #1A1D2B;
        }
        .hard-shadow-sm {
            box-shadow: 2px 2px 0px #1A1D2B;
        }
        .tactile-btn {
            transition: all 0.08s ease-in-out;
        }
        .tactile-btn:hover {
            transform: translate(-1px, -1px);
            box-shadow: 2px 2px 0px #1A1D2B;
        }
        .tactile-btn:active {
            transform: translate(1px, 1px);
            box-shadow: 0px 0px 0px #1A1D2B;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between selection:bg-[#237DE1] selection:text-white pb-16 md:pb-0">

    <div class="w-full">
        {{-- NAVBAR UTAMA ATAS (E-COMMERCE STYLE / GUEST) --}}
        <nav class="sticky top-0 z-40 border-b-2 border-[#1A1D2B] bg-white px-3 sm:px-6 py-2.5 sm:py-3.5 w-full">
            <div class="w-full flex items-center justify-between gap-3 sm:gap-4">
                
                {{-- 1. Logo & Brand --}}
                <a href="{{ url('/') }}" class="flex items-center gap-2 group shrink-0">
                    <div class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] font-mono-tech text-white font-bold text-sm hard-shadow-sm group-hover:bg-[#1A1D2B] transition-colors">
                        P
                    </div>
                    <div class="leading-tight">
                        <span class="font-extrabold text-[13px] sm:text-[15px] uppercase tracking-tight text-[#1A1D2B] block">
                            Peminjaman Alat
                        </span>
                        <span class="font-mono-tech text-[9px] sm:text-[10px] font-bold text-[#5C6475]">
                            Katalog Publik
                        </span>
                    </div>
                </a>

                {{-- 2. Search Bar & Filter Kategori Panjang di Tengah --}}
                <div class="flex-1 max-w-3xl mx-2">
                    <form action="{{ url('/') }}" method="GET" class="relative w-full flex items-stretch rounded-xl border-2 border-[#1A1D2B] bg-[#F8F5EE] overflow-hidden hard-shadow-sm">
                        
                        {{-- Dropdown Kategori --}}
                        <div class="border-r-2 border-[#1A1D2B] bg-white shrink-0 hidden sm:block">
                            <select name="kategori_id" 
                                    class="h-full bg-transparent px-3 py-2 font-mono-tech text-[11.5px] font-bold text-[#1A1D2B] focus:outline-none cursor-pointer">
                                <option value="">Kategori</option>
                                @if(isset($kategoris))
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" @selected(request('kategori_id') == $kategori->id)>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Input Kata Kunci Pencarian --}}
                        <div class="relative flex-1 flex items-center min-w-0">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari nama atau kode alat..." 
                                   class="w-full bg-transparent px-3 py-2 text-[12px] sm:text-[13px] font-medium text-[#1A1D2B] placeholder:text-gray-400 focus:outline-none truncate">
                        </div>
                        
                        {{-- Tombol Cari --}}
                        <button type="submit" class="bg-[#237DE1] px-3.5 sm:px-6 text-white font-mono-tech text-[11px] sm:text-xs font-bold hover:bg-[#1d69bf] border-l-2 border-[#1A1D2B] transition shrink-0 flex items-center justify-center">
                            <span>CARI</span>
                        </button>
                    </form>
                </div>

                {{-- 3. Menu Kanan Versi Desktop (Masuk & Daftar) --}}
                <div class="hidden md:flex items-center gap-3 shrink-0">
                    <a href="{{ route('login') }}" 
                       class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-white px-4 py-2 font-mono-tech text-[11.5px] font-bold text-[#1A1D2B] hard-shadow-sm hover:bg-[#F2E7C9]">
                        Masuk
                    </a>
                    
                    @if (Illuminate\Support\Facades\Route::has('register'))
                        <a href="{{ route('register') }}" 
                           class="tactile-btn rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] px-4 py-2 font-mono-tech text-[11.5px] font-bold text-white hard-shadow-sm hover:bg-[#d94b1c]">
                            Daftar Akun
                        </a>
                    @endif
                </div>

            </div>
        </nav>
    </div>

    {{-- KONTEN UTAMA --}}
    <main class="flex-grow max-w-7xl w-full mx-auto px-3 sm:px-6 lg:px-8 py-5 sm:py-8">
        
        {{-- Header Halaman --}}
        @hasSection('header')
            <div class="mb-4 sm:mb-6 pb-3 border-b-2 border-[#1A1D2B]/20 flex flex-col sm:flex-row sm:items-center justify-between gap-1.5">
                <h1 class="text-lg sm:text-2xl font-extrabold text-[#1A1D2B] tracking-tight">
                    @yield('header')
                </h1>
                <span class="font-mono-tech text-[11px] sm:text-xs font-bold text-[#5C6475]">
                    {{ now()->translatedFormat('d F Y') }}
                </span>
            </div>
        @endif

        {{-- Isi Konten --}}
        @yield('content')

    </main>

    {{-- FOOTER DESKTOP --}}
    <footer class="border-t-2 border-[#1A1D2B] bg-white py-5 px-4 sm:px-8 mt-12 w-full hidden md:block">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <p class="font-mono-tech text-[11px] font-bold text-[#5C6475]">
                &copy; {{ date('Y') }} Sistem Peminjaman Alat. Hak cipta dilindungi.
            </p>
            <p class="font-mono-tech text-[11px] font-bold text-[#1A1D2B]">
                Portal Publik v2.0
            </p>
        </div>
    </footer>

    {{-- BOTTOM NAVIGATION BAR KHUSUS HP (UNTUK TAMU / GUEST) --}}
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 border-t-2 border-[#1A1D2B] bg-white px-3 py-2 flex items-center justify-around shadow-[0_-4px_0px_rgba(26,29,43,0.1)] gap-2">
        
        {{-- Tombol Beranda / Katalog --}}
        <a href="{{ url('/') }}" class="flex flex-col items-center justify-center text-[#1A1D2B] hover:text-[#237DE1] w-1/2 py-1">
            <svg class="h-5 w-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="font-mono-tech text-[10px] font-bold">Katalog</span>
        </a>

        {{-- Tombol Masuk --}}
        <a href="{{ route('login') }}" class="flex flex-col items-center justify-center text-white bg-[#237DE1] border-2 border-[#1A1D2B] rounded-xl w-1/2 py-1.5 hard-shadow-sm">
            <span class="font-mono-tech text-[11px] font-extrabold uppercase">Masuk Akun</span>
        </a>

    </div>

</body>
</html>