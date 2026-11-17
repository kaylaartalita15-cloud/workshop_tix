<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WorkshopTix - Pendaftaran dan Tiket Digital IT')</title>
    <!-- Tailwind CSS & Font Awesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#10b981',
                            600: '#059669',
                        },
                        dark: {
                            800: '#0f172a',
                            900: '#090d16',
                            950: '#040711',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        heading: ['Space Grotesk', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .heading-font {
            font-family: 'Space Grotesk', sans-serif;
        }
        @media print {
            .no-print, aside {
                display: none !important;
            }
            main {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#0b0f19] text-slate-100 flex h-screen overflow-hidden antialiased">

    <!-- SIDEBAR KIRI (Menu Navigasi di Kiri sesuai arahan guru) -->
    <aside class="w-64 bg-[#080c14] border-r border-slate-800/80 flex flex-col justify-between flex-shrink-0 z-30 transition-all select-none">
        <div>
            <!-- Logo Header -->
            <div class="h-20 flex items-center px-6 border-b border-slate-800/80">
                <a href="{{ route('workshops.index') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-xl shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                    <div>
                        <div class="heading-font font-black tracking-wider text-lg text-white leading-tight">
                            WORKSHOP<span class="text-emerald-400">TIX</span>
                        </div>
                        <span class="text-[10px] uppercase font-semibold text-slate-400 tracking-wider">SMK PESAT RPL</span>
                    </div>
                </a>
            </div>

            <!-- Navigasi Menu Kiri -->
            <div class="px-4 py-6 space-y-7 overflow-y-auto max-h-[calc(100vh-170px)]">
                <!-- Group: Umum -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Menu Utama</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('workshops.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('workshops.index') || request()->routeIs('workshops.show') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                            <i class="fa-solid fa-shapes text-sm w-4"></i>
                            <span>Katalog Workshop</span>
                        </a>

                        @auth
                            @if(auth()->user()->isPeserta())
                                <a href="{{ route('registrations.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('registrations.*') || request()->routeIs('tickets.*') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                                    <i class="fa-solid fa-receipt text-sm w-4"></i>
                                    <span>Tiket Saya</span>
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Group: Khusus Panitia Admin -->
                @auth
                    @if(auth()->user()->isPanitia())
                        <div>
                            <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-emerald-400/90 flex items-center gap-1.5">
                                <i class="fa-solid fa-shield-halved text-[9px]"></i>
                                <span>Panel Panitia</span>
                            </span>
                            <div class="mt-2 space-y-1">
                                <a href="{{ route('panitia.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('panitia.dashboard') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                                    <i class="fa-solid fa-chart-pie text-sm w-4"></i>
                                    <span>Dashboard Admin</span>
                                </a>

                                <a href="{{ route('panitia.checkin.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('panitia.checkin.*') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                                    <i class="fa-solid fa-qrcode text-sm w-4"></i>
                                    <span>Scanner & Check-In</span>
                                </a>

                                <a href="{{ route('panitia.workshops.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('panitia.workshops.*') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                                    <i class="fa-solid fa-laptop-code text-sm w-4"></i>
                                    <span>Kelola Workshop</span>
                                </a>

                                <a href="{{ route('panitia.pendaftar') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('panitia.pendaftar') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                                    <i class="fa-solid fa-users-viewfinder text-sm w-4"></i>
                                    <span>Data Peserta (N+1)</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Sidebar Footer: Akun / Status Login -->
        <div class="p-4 border-t border-slate-800/80 bg-[#060910]">
            @auth
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-9 h-9 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-xs text-emerald-400 flex-shrink-0">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="overflow-hidden">
                            <div class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</div>
                            <span class="inline-block text-[9px] uppercase font-mono px-1.5 py-0.2 rounded font-semibold {{ auth()->user()->isPanitia() ? 'bg-amber-400/20 text-amber-300' : 'bg-emerald-400/20 text-emerald-300' }}">
                                {{ auth()->user()->role }}
                            </span>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition" title="Logout">
                            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                        </button>
                    </form>
                </div>
            @else
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="block text-center w-full py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-xl transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="block text-center w-full py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black rounded-xl transition shadow-lg shadow-emerald-500/20">
                        Daftar Akun
                    </a>
                </div>
            @endauth
        </div>
    </aside>

    <!-- KONTEN UTAMA KANAN -->
    <div class="flex-1 flex flex-col overflow-y-auto bg-[#0b0f19]">
        <!-- Topbar Sederhana & Status -->
        <header class="h-16 border-b border-slate-800/80 bg-[#090d16]/80 backdrop-blur sticky top-0 z-20 flex items-center justify-between px-6 sm:px-8">
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-mono text-slate-300 font-semibold tracking-wide">
                    WORKSHOPTIX • SISTEM TIKET RESMI IT
                </span>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->isPanitia())
                        <span class="text-[11px] bg-amber-400/10 text-amber-300 border border-amber-400/20 px-3 py-1 rounded-full font-semibold">
                            <i class="fa-solid fa-lock-open mr-1"></i> Mode Panitia Administrator
                        </span>
                    @else
                        <a href="{{ route('registrations.index') }}" class="text-xs bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-300 border border-emerald-500/30 px-3 py-1.5 rounded-xl font-bold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-ticket"></i>
                            <span>Tiket Saya</span>
                        </a>
                    @endif
                @endauth
            </div>
        </header>

        <!-- Flash Alert Messages -->
        <div class="px-6 sm:px-8 pt-4 w-full no-print">
            @if(session('success'))
                <div class="bg-emerald-950/60 border border-emerald-500/40 text-emerald-200 p-4 rounded-2xl shadow-lg flex items-start space-x-3 mb-4 backdrop-blur">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-lg mt-0.5"></i>
                    <div class="text-sm font-medium">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-950/60 border border-rose-500/40 text-rose-200 p-4 rounded-2xl shadow-lg flex items-start space-x-3 mb-4 backdrop-blur">
                    <i class="fa-solid fa-circle-xmark text-rose-400 text-lg mt-0.5"></i>
                    <div class="text-sm font-medium">{{ session('error') }}</div>
                </div>
            @endif

            @if(session('warning'))
                <div class="bg-amber-950/60 border border-amber-500/40 text-amber-200 p-4 rounded-2xl shadow-lg flex items-start space-x-3 mb-4 backdrop-blur">
                    <i class="fa-solid fa-triangle-exclamation text-amber-400 text-lg mt-0.5"></i>
                    <div class="text-sm font-medium">{{ session('warning') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-950/60 border border-rose-500/40 text-rose-200 p-4 rounded-2xl shadow-lg mb-4 backdrop-blur">
                    <div class="font-bold text-sm mb-1">Periksa kembali data Anda:</div>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Render View -->
        <main class="p-6 sm:p-8 flex-1">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
