<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Sistem Peminjaman Alat</title>

        <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@700;800&family=Plus+Jakarta+Sans:wght@500;700;800&display=swap" rel="stylesheet">

        <!-- Scripts & CSS Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Kustomisasi gaya Neo-Brutalist & Font Global */
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
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
                transition: all 0.1s ease;
            }
            .tactile-btn:hover {
                transform: translate(-1px, -1px);
                box-shadow: 5px 5px 0px #1A1D2B;
            }
            .tactile-btn:active {
                transform: translate(2px, 2px);
                box-shadow: 1px 1px 0px #1A1D2B;
            }
        </style>
    </head>
    
    <body class="bg-[#F2E7C9] text-[#1A1D2B] antialiased selection:bg-[#237DE1] selection:text-white">

        <div class="min-h-screen flex flex-col justify-center items-center py-10 px-4 sm:px-6">
            
            {{-- KEPALA / LOGO BRANDING APLIKASI --}}
            <div class="mb-6 text-center space-y-2">
                <a href="/" class="inline-block group">
                    <div class="h-16 w-16 mx-auto rounded-2xl border-2 border-[#1A1D2B] bg-[#237DE1] flex items-center justify-center hard-shadow group-hover:bg-[#1d69bf] transition">
                        <span class="font-mono-tech text-2xl font-extrabold text-white">LAB</span>
                    </div>
                </a>
                <div>
                    <h1 class="font-mono-tech text-base sm:text-lg font-extrabold text-[#1A1D2B] tracking-tight uppercase">
                        Sistem Inventaris Alat
                    </h1>
                    <p class="font-mono-tech text-[11px] font-bold text-[#5C6475]">
                        // PORTAL AUTENTIKASI RESMI
                    </p>
                </div>
            </div>

            {{-- KOTAK UTAMA KONTEN ( SLOT FORM ) --}}
            <div class="w-full sm:max-w-md">
                {{ $slot }}
            </div>

            {{-- FOOTER KECIL --}}
            <div class="mt-8 text-center font-mono-tech text-[11px] font-bold text-[#5C6475]">
                &copy; {{ date('Y') }} Laboratorium Kampus / Sekolah. All rights reserved.
            </div>

        </div>

    </body>
</html>