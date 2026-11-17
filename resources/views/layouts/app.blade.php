<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WorkshopTix - Pendaftaran dan Tiket Digital Workshop IT')</title>
    <!-- Tailwind CSS CDN & Font Awesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- Header / Navbar -->
    <nav class="bg-indigo-950 text-white shadow-md sticky top-0 z-50 border-b border-indigo-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('workshops.index') }}" class="flex items-center space-x-2 text-xl font-black tracking-tight text-white hover:text-indigo-200 transition">
                        <span class="bg-amber-400 text-indigo-950 p-2 rounded-xl text-lg shadow">
                            <i class="fa-solid fa-ticket"></i>
                        </span>
                        <span>WORKSHOP<span class="text-amber-400">TIX</span></span>
                    </a>
                    <span class="hidden md:inline-block text-[11px] font-semibold bg-indigo-900 text-indigo-200 px-3 py-1 rounded-full border border-indigo-800">
                        SMK PESAT • REKAYASA PERANGKAT LUNAK
                    </span>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('workshops.index') }}" class="text-xs sm:text-sm font-semibold hover:text-amber-400 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-compass"></i>
                        <span>Jelajah Workshop</span>
                    </a>

                    @auth
                        @if(auth()->user()->isPanitia())
                            <a href="{{ route('panitia.dashboard') }}" class="text-xs sm:text-sm font-bold bg-amber-400 text-indigo-950 px-3 py-1.5 rounded-xl hover:bg-amber-300 transition shadow-sm flex items-center gap-1.5">
                                <i class="fa-solid fa-chart-pie"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('panitia.checkin.index') }}" class="hidden md:inline-flex text-xs sm:text-sm font-semibold text-emerald-300 hover:text-emerald-200 transition items-center gap-1">
                                <i class="fa-solid fa-qrcode"></i>
                                <span>Scan Tiket</span>
                            </a>
                            <a href="{{ route('panitia.workshops.index') }}" class="hidden sm:inline-block text-xs sm:text-sm font-semibold hover:text-amber-400 transition">
                                Kelola Workshop
                            </a>
                            <a href="{{ route('panitia.pendaftar') }}" class="hidden sm:inline-block text-xs sm:text-sm font-semibold hover:text-amber-400 transition">
                                Pendaftar (N+1)
                            </a>
                        @else
                            <a href="{{ route('registrations.index') }}" class="text-xs sm:text-sm font-bold bg-indigo-800 hover:bg-indigo-700 text-white px-3.5 py-1.5 rounded-xl transition shadow-sm flex items-center gap-1.5">
                                <i class="fa-solid fa-receipt text-amber-400"></i>
                                <span>Tiket Saya</span>
                            </a>
                        @endif

                        <div class="relative flex items-center space-x-3 border-l border-indigo-800 pl-4">
                            <div class="text-right hidden sm:block">
                                <div class="text-xs font-bold leading-none">{{ auth()->user()->name }}</div>
                                <span class="text-[10px] font-mono px-2 py-0.5 rounded uppercase mt-1 inline-block {{ auth()->user()->isPanitia() ? 'bg-amber-400/20 text-amber-300' : 'bg-sky-400/20 text-sky-300' }}">
                                    {{ auth()->user()->role }}
                                </span>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs bg-rose-600 hover:bg-rose-500 text-white font-bold px-3 py-1.5 rounded-xl transition shadow flex items-center gap-1" title="Logout">
                                    <i class="fa-solid fa-power-off"></i>
                                    <span class="hidden sm:inline">Keluar</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold hover:text-amber-300 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-xs sm:text-sm font-bold bg-amber-400 text-indigo-950 px-4 py-2 rounded-xl hover:bg-amber-300 transition shadow">
                            Daftar Akun
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Notification Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full no-print">
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-900 p-4 rounded-r-2xl shadow-sm flex items-start space-x-3 mb-4">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-900 p-4 rounded-r-2xl shadow-sm flex items-start space-x-3 mb-4">
                <i class="fa-solid fa-circle-xmark text-rose-600 text-lg mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-900 p-4 rounded-r-2xl shadow-sm flex items-start space-x-3 mb-4">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('warning') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-900 p-4 rounded-r-2xl shadow-sm mb-4">
                <div class="font-bold text-sm mb-1">Periksa kembali data Anda:</div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12 py-6 text-center text-sm text-slate-500 no-print">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <div class="flex items-center space-x-2">
                    <span class="font-black text-indigo-950">WORKSHOPTIX</span>
                    <span>&copy; {{ date('Y') }} - Pendaftaran & Tiket Digital IT</span>
                </div>
                <div class="text-slate-400">
                    LKPD XI RPL • Drill Pra Sertifikasi Kejuruan RPL SMK Pesat
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
