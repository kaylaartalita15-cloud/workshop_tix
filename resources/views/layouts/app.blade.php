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
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- Header / Navbar -->
    <nav class="bg-indigo-900 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('workshops.index') }}" class="flex items-center space-x-2 text-xl font-bold tracking-tight text-white hover:text-indigo-200 transition">
                        <span class="bg-amber-400 text-indigo-950 p-2 rounded-xl text-lg shadow">
                            <i class="fa-solid fa-ticket"></i>
                        </span>
                        <span>WORKSHOP<span class="text-amber-400">TIX</span></span>
                    </a>
                    <span class="hidden md:inline-block text-xs bg-indigo-800 text-indigo-200 px-2.5 py-1 rounded-full border border-indigo-700">
                        SMK Pesat • RPL
                    </span>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('workshops.index') }}" class="text-sm font-medium hover:text-amber-400 transition">
                        <i class="fa-solid fa-compass mr-1"></i> Jelajah Workshop
                    </a>

                    @auth
                        @if(auth()->user()->isPanitia())
                            <a href="{{ route('panitia.dashboard') }}" class="text-sm font-semibold bg-amber-400 text-indigo-950 px-3 py-1.5 rounded-lg hover:bg-amber-300 transition shadow-sm">
                                <i class="fa-solid fa-chart-line mr-1"></i> Dashboard Panitia
                            </a>
                            <a href="{{ route('panitia.workshops.index') }}" class="hidden sm:inline-block text-sm font-medium hover:text-amber-400 transition">
                                Kelola Workshop
                            </a>
                            <a href="{{ route('panitia.pendaftar') }}" class="hidden sm:inline-block text-sm font-medium hover:text-amber-400 transition">
                                Data Pendaftar
                            </a>
                        @else
                            <a href="{{ route('registrations.index') }}" class="text-sm font-medium hover:text-amber-400 transition">
                                <i class="fa-solid fa-receipt mr-1"></i> Tiket Saya
                            </a>
                        @endif

                        <div class="relative flex items-center space-x-3 border-l border-indigo-700 pl-4">
                            <div class="text-right hidden sm:block">
                                <div class="text-xs font-semibold leading-none">{{ auth()->user()->name }}</div>
                                <div class="text-[10px] text-amber-300 uppercase mt-0.5">{{ auth()->user()->role }}</div>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs bg-red-600/80 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg transition" title="Logout">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-amber-300 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold bg-amber-400 text-indigo-950 px-4 py-2 rounded-lg hover:bg-amber-300 transition shadow">
                            Daftar Akun
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r-lg shadow-sm flex items-start space-x-3 mb-4">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg mt-0.5"></i>
                <div class="text-sm">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-r-lg shadow-sm flex items-start space-x-3 mb-4">
                <i class="fa-solid fa-circle-xmark text-rose-500 text-lg mt-0.5"></i>
                <div class="text-sm">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-800 p-4 rounded-r-lg shadow-sm flex items-start space-x-3 mb-4">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-lg mt-0.5"></i>
                <div class="text-sm">{{ session('warning') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-r-lg shadow-sm mb-4">
                <div class="font-semibold text-sm mb-1">Perhatian:</div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12 py-6 text-center text-sm text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p class="font-medium text-slate-700">WORKSHOPTIX &copy; {{ date('Y') }} - Pendaftaran dan Tiket Digital Workshop IT</p>
            <p class="text-xs text-slate-400 mt-1">LKPD XI RPL • Drill Pra Sertifikasi Kejuruan RPL SMK Pesat</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
