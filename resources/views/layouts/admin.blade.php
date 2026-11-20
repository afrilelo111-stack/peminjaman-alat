<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin' }} — Peminjaman Alat</title>

    <!-- Google Fonts untuk karakter tipografi yang lebih tajam -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --blue: #237DE1;
            --orange: #EC5928;
            --cream: #F2E7C9;
            --ink: #1A1D2B;
            --muted: #5C6475;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--cream);
            color: var(--ink);
        }

        .font-mono-tech {
            font-family: 'Space Mono', monospace;
        }

        /* Latar belakang dot-grid halus agar tidak polos */
        .bg-dot-pattern {
            background-image: radial-gradient(rgba(26, 29, 43, 0.14) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* Efek tombol taktil (Neo-Brutalist ringan) */
        .tactile-btn {
            transition: transform 0.12s cubic-bezier(0.2, 0, 0, 1), box-shadow 0.12s cubic-bezier(0.2, 0, 0, 1);
        }
        .tactile-btn:hover {
            transform: translate(-1px, -1px);
            box-shadow: 3px 3px 0px var(--ink);
        }
        .tactile-btn:active {
            transform: translate(1px, 1px);
            box-shadow: 0px 0px 0px var(--ink);
        }

        .hard-shadow {
            box-shadow: 4px 4px 0px var(--ink);
        }
        .hard-shadow-sm {
            box-shadow: 2px 2px 0px var(--ink);
        }

        /* Custom Scrollbar */
        nav::-webkit-scrollbar { width: 5px; }
        nav::-webkit-scrollbar-track { background: transparent; }
        nav::-webkit-scrollbar-thumb { background: var(--ink); border-radius: 99px; }

        /* Accordion */
        .submenu { max-height: 0; overflow: hidden; transition: max-height 0.25s ease-out; }
        .submenu.open { max-height: 300px; }
        .arrow { transition: transform 0.2s ease; }
        .arrow.open { transform: rotate(90deg); }
    </style>
</head>

<body class="min-h-screen antialiased selection:bg-[#EC5928] selection:text-white">

    @php
        $navGroups = [
            [
                'label' => 'Manajemen Data',
                'icon'  => 'folder',
                'items' => [
                    ['name' => 'Pengguna',      'route' => 'admin.users.index',          'active' => 'admin.users.*'],
                    ['name' => 'Kategori Alat', 'route' => 'admin.kategoris.index',      'active' => 'admin.kategoris.*'],
                    ['name' => 'Data Alat',     'route' => 'admin.alats.index',          'active' => 'admin.alats.*'],
                ],
            ],
            [
                'label' => 'Sirkulasi & Transaksi',
                'icon'  => 'chart',
                'items' => [
                    ['name' => 'Peminjaman',   'route' => 'admin.peminjamans.index',    'active' => 'admin.peminjamans.*'],
                    ['name' => 'Pengembalian', 'route' => 'admin.pengembalians.index',  'active' => 'admin.pengembalians.*'],
                ],
            ],
            [
                'label' => 'Sistem',
                'icon'  => 'settings',
                'items' => [
                    ['name' => 'Log Aktivitas','route' => 'admin.activity-logs.index',  'active' => 'admin.activity-logs.*'],
                ],
            ],
        ];
    @endphp

    {{-- OVERLAY MOBILE --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 z-40 hidden bg-[#1A1D2B]/50 backdrop-blur-[2px] lg:hidden"></div>

    {{-- ============ SIDEBAR ============ --}}
    <aside id="admin-sidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-[264px] -translate-x-full flex-col
               bg-white border-r-2 border-[#1A1D2B] transition-transform duration-300 ease-out
               lg:translate-x-0">

        {{-- BRAND --}}
        <div class="flex h-[72px] items-center justify-between border-b-2 border-[#1A1D2B] bg-[#F2E7C9] px-5">
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 group">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-[#EC5928] text-white font-extrabold text-base hard-shadow-sm group-hover:bg-[#237DE1] transition-colors">
                    PA
                </div>
                <div class="leading-none">
                    <span class="block text-[15px] font-extrabold tracking-tight text-[#1A1D2B] uppercase">Inventaris</span>
                    <span class="font-mono-tech inline-block mt-1 rounded bg-[#1A1D2B] px-1.5 py-0.5 text-[10px] font-bold text-[#F2E7C9]">
                        ADMIN PANEL
                    </span>
                </div>
            </a>

            <button id="close-sidebar"
                class="flex h-8 w-8 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-white text-[#1A1D2B] hover:bg-[#EC5928] hover:text-white lg:hidden"
                aria-label="Tutup menu">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- NAVIGATION --}}
        <nav class="flex-1 overflow-y-auto px-3.5 py-5 space-y-2">

            <div class="px-2 pb-1">
                <span class="font-mono-tech text-[10px] font-bold uppercase tracking-widest text-[#5C6475]">Menu Utama</span>
            </div>

            {{-- Dashboard (Gunakan routeIs khusus dashboard jika sudah ada, di sini dicontohkan admin.dashboard) --}}
            @php $isDashboard = request()->routeIs('admin.dashboard'); @endphp
            <a href="{{ route('admin.dashboard') }}"
               class="tactile-btn flex items-center gap-3 rounded-xl border-2 px-3.5 py-2.5 text-[13.5px] font-bold
                      {{ $isDashboard 
                            ? 'border-[#1A1D2B] bg-[#237DE1] text-white hard-shadow-sm' 
                            : 'border-transparent text-[#1A1D2B] hover:border-[#1A1D2B] hover:bg-[#F2E7C9]' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span class="flex-1">Ringkasan</span>
            </a>

            <div class="pt-3 px-2 pb-1">
                <span class="font-mono-tech text-[10px] font-bold uppercase tracking-widest text-[#5C6475]">Modul Sistem</span>
            </div>

            @foreach ($navGroups as $group)
                @php
                    $groupActive = collect($group['items'])->contains(fn($i) => request()->routeIs($i['active']));
                    $groupId = 'group-' . $loop->index;
                @endphp

                <div class="space-y-1">
                    <button type="button"
                        onclick="toggleGroup('{{ $groupId }}')"
                        class="flex w-full items-center gap-3 rounded-xl border-2 px-3.5 py-2.5 text-[13.5px] font-bold transition-colors
                               {{ $groupActive 
                                    ? 'border-[#1A1D2B] bg-[#F2E7C9] text-[#1A1D2B]' 
                                    : 'border-transparent text-[#1A1D2B] hover:bg-[#F2E7C9]/60' }}">
                        <span class="flex h-6 w-6 items-center justify-center rounded-md border border-[#1A1D2B] {{ $groupActive ? 'bg-[#237DE1] text-white' : 'bg-white text-[#1A1D2B]' }}">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                @if($group['icon'] === 'folder')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                @elseif($group['icon'] === 'chart')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                @endif
                            </svg>
                        </span>
                        <span class="flex-1 text-left">{{ $group['label'] }}</span>
                        <svg class="arrow h-3.5 w-3.5 shrink-0 {{ $groupActive ? 'open' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <div id="{{ $groupId }}" class="submenu {{ $groupActive ? 'open' : '' }}">
                        <div class="space-y-1 border-l-2 border-[#1A1D2B] pl-3 ml-6 py-1">
                            @foreach ($group['items'] as $item)
                                @php $active = request()->routeIs($item['active']); @endphp
                                <a href="{{ route($item['route']) }}"
                                   class="flex items-center justify-between rounded-lg border-2 px-3 py-2 text-[13px] transition-all
                                          {{ $active
                                                ? 'border-[#1A1D2B] bg-[#EC5928] text-white font-bold hard-shadow-sm'
                                                : 'border-transparent text-[#5C6475] font-semibold hover:border-[#1A1D2B] hover:bg-white hover:text-[#1A1D2B]' }}">
                                    <span>{{ $item['name'] }}</span>
                                    @if($active)
                                        <span class="h-2 w-2 rounded-full bg-[#F2E7C9]"></span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

        </nav>

        {{-- USER CARD FOOTER --}}
        <div class="border-t-2 border-[#1A1D2B] bg-[#F2E7C9]/50 p-3.5">
            <div class="flex items-center gap-3 rounded-xl border-2 border-[#1A1D2B] bg-white p-2.5 hard-shadow-sm">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-[#237DE1] text-[13px] font-extrabold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="min-w-0 flex-1">
                    <p class="truncate text-[13px] font-bold text-[#1A1D2B]">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="font-mono-tech truncate text-[10px] font-bold uppercase text-[#EC5928]">
                        ● Online · Admin
                    </p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ============ MAIN ============ --}}
    <div class="min-h-screen flex flex-col lg:pl-[264px]">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-30 border-b-2 border-[#1A1D2B] bg-white">
            <div class="flex h-[72px] items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

                {{-- Left --}}
                <div class="flex flex-1 items-center gap-3 max-w-xl">
                    <button id="open-sidebar"
                        class="tactile-btn -ml-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] text-[#1A1D2B] lg:hidden"
                        aria-label="Buka menu">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/>
                        </svg>
                    </button>

                    {{-- Search bar dengan aksen shortcut --}}
                    <div class="relative flex-1 hidden sm:block">
                        <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#1A1D2B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                            id="global-search"
                            placeholder="Cari kode alat, peminjam, atau transaksi..."
                            class="w-full rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9]/60 pl-10 pr-14 py-2 text-[13px] font-medium text-[#1A1D2B] placeholder:text-[#5C6475] focus:bg-white focus:outline-none focus:shadow-[3px_3px_0px_#237DE1] transition-shadow">
                        <kbd class="font-mono-tech pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 rounded border border-[#1A1D2B] bg-white px-1.5 py-0.5 text-[10px] font-bold text-[#1A1D2B]">
                            CTRL K
                        </kbd>
                    </div>
                </div>

                {{-- Right --}}
                <div class="flex shrink-0 items-center gap-2.5">
                    {{-- Notification --}}
                    <button class="tactile-btn relative flex h-10 w-10 items-center justify-center rounded-xl border-2 border-[#1A1D2B] bg-white text-[#1A1D2B] hover:bg-[#F2E7C9]" title="Notifikasi">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -right-1 -top-1 flex h-3.5 w-3.5 items-center justify-center rounded-full border-2 border-[#1A1D2B] bg-[#EC5928]"></span>
                    </button>

                    {{-- Logout dengan label jelas --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="tactile-btn flex h-10 items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] px-3.5 text-[13px] font-bold text-white hover:bg-[#d94b1c]"
                            title="Keluar dari sistem">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="flex-1 bg-dot-pattern px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            {{-- Header Card / Banner Kontekstual --}}
            <div class="mb-6 flex flex-col justify-between gap-4 rounded-2xl border-2 border-[#1A1D2B] bg-white p-5 hard-shadow sm:flex-row sm:items-center">
                <div>
                    {{-- Breadcrumb Pill --}}
                    <nav class="mb-2 inline-flex items-center gap-1.5 rounded-md border border-[#1A1D2B] bg-[#F2E7C9] px-2.5 py-0.5 text-[11px] font-bold text-[#1A1D2B]">
                        <a href="{{ route('admin.users.index') }}" class="hover:text-[#237DE1] underline decoration-1 underline-offset-2">Beranda</a>
                        <span>/</span>
                        <span class="text-[#EC5928]">{{ $header ?? 'Dashboard' }}</span>
                    </nav>

                    <h1 class="text-[22px] sm:text-[26px] font-extrabold tracking-tight text-[#1A1D2B] leading-tight">
                        {{ $header ?? 'Dashboard' }}
                    </h1>
                    <p class="mt-1 text-[13px] font-medium text-[#5C6475]">
                        Pantau sirkulasi aset, ketersediaan alat, dan aktivitas pengguna secara real-time.
                    </p>
                </div>

                {{-- Slot Aksi Cepat (Opsional, bisa diisi tombol "+ Tambah Data" dari tiap halaman) --}}
                @hasSection('header-actions')
                    <div class="flex shrink-0 items-center gap-2">
                        @yield('header-actions')
                    </div>
                @else
                    <div class="font-mono-tech hidden md:flex items-center gap-2 rounded-xl border-2 border-[#1A1D2B] bg-[#F2E7C9] px-3.5 py-2 text-[12px] font-bold text-[#1A1D2B]">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#237DE1]"></span>
                        {{ now()->translatedFormat('d M Y') }}
                    </div>
                @endif
            </div>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 rounded-xl border-2 border-[#1A1D2B] bg-[#237DE1] px-4 py-3.5 text-white hard-shadow-sm">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-white text-[#237DE1]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-[13.5px] font-bold">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 flex items-center gap-3 rounded-xl border-2 border-[#1A1D2B] bg-[#EC5928] px-4 py-3.5 text-white hard-shadow-sm">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border-2 border-[#1A1D2B] bg-white text-[#EC5928]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <p class="text-[13.5px] font-bold">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Main Page Content --}}
            @yield('content')

        </main>

        {{-- FOOTER --}}
        <footer class="border-t-2 border-[#1A1D2B] bg-white px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-2 sm:flex-row">
                <p class="text-[12.5px] font-semibold text-[#1A1D2B]">
                    © {{ date('Y') }} Sistem Peminjaman Alat.
                </p>
                <div class="flex items-center gap-2">
                    <span class="font-mono-tech rounded border border-[#1A1D2B] bg-[#F2E7C9] px-2 py-0.5 text-[11px] font-bold text-[#1A1D2B]">
                        BUILD v3.0
                    </span>
                </div>
            </div>
        </footer>

    </div>

    {{-- SCRIPTS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const openBtn = document.getElementById('open-sidebar');
            const closeBtn = document.getElementById('close-sidebar');
            const searchInput = document.getElementById('global-search');

            const openSidebar = () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };
            const closeSidebar = () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            openBtn?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);
            
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeSidebar();
                // Shortcut Ctrl+K atau Cmd+K untuk fokus ke pencarian
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                    e.preventDefault();
                    searchInput?.focus();
                }
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) closeSidebar();
            });
        });

        function toggleGroup(id) {
            const submenu = document.getElementById(id);
            const btn = submenu.previousElementSibling;
            const arrow = btn.querySelector('.arrow');
            submenu.classList.toggle('open');
            arrow.classList.toggle('open');
        }
    </script>

</body>
</html>