@extends('layouts.app')

@section('title', 'Katalog Workshop IT - WorkshopTix')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    <!-- Hero Banner Futuristik Berwarna Elegan -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-950 via-[#0a1622] to-slate-900 border border-emerald-500/30 p-8 sm:p-12 shadow-2xl">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 uppercase tracking-widest mb-4">
                <i class="fa-solid fa-graduation-cap"></i> Drill Pra Sertifikasi Kejuruan RPL
            </span>
            <h1 class="heading-font text-3xl sm:text-5xl font-black text-white leading-tight mb-4 tracking-tight">
                Tingkatkan Skill Coding dengan <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">Workshop IT Terarah</span>.
            </h1>
            <p class="text-slate-300 text-sm sm:text-base mb-6 leading-relaxed">
                Pilih topik keahlian software development, lakukan pendaftaran, bayar instan via Midtrans Sandbox, dan dapatkan tiket digital resmi ber-QR Code untuk sertifikasi Anda.
            </p>
            <div class="flex flex-wrap gap-4 items-center">
                <a href="#katalog" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-6 py-3 rounded-xl transition shadow-lg shadow-emerald-500/25 flex items-center gap-2 text-sm">
                    <span>Lihat Workshop & Tiket</span>
                    <i class="fa-solid fa-arrow-down"></i>
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none text-emerald-400">
            <i class="fa-solid fa-microchip text-[320px]"></i>
        </div>
    </div>

    <!-- Section Katalog Workshop -->
    <div id="katalog" class="space-y-6 pt-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-4">
            <div>
                <h2 class="heading-font text-2xl font-black text-white">Workshop IT Tersedia</h2>
                <p class="text-xs text-slate-400 mt-1">Sisa tiket berkurang secara real-time setiap ada transaksi masuk</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-3 py-1.5 rounded-xl">
                <i class="fa-solid fa-ticket"></i>
                <span>Sistem Kuota Otomatis Aktif</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($workshops as $item)
                @php
                    $sisa = $item->sisa_tiket;
                @endphp
                <div class="bg-[#0f172a] rounded-2xl border border-slate-800 overflow-hidden shadow-lg hover:border-emerald-500/50 hover:shadow-emerald-500/10 transition-all flex flex-col justify-between group">
                    <!-- Gambar Workshop Berdasarkan Tema IT -->
                    <div class="relative h-48 w-full overflow-hidden bg-slate-900">
                        <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_workshop }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-transparent to-transparent"></div>

                        <!-- Tag Kategori & Sisa Tiket -->
                        <div class="absolute top-3 left-3">
                            <span class="text-[11px] font-bold px-3 py-1 bg-slate-950/80 backdrop-blur text-emerald-400 rounded-lg border border-emerald-500/30">
                                {{ $item->kategori ?? 'Programming' }}
                            </span>
                        </div>

                        <div class="absolute top-3 right-3">
                            <span class="text-[11px] font-black px-3 py-1 rounded-lg backdrop-blur border {{ $sisa > 0 ? 'bg-emerald-950/90 text-emerald-300 border-emerald-500/40' : 'bg-rose-950/90 text-rose-300 border-rose-500/40' }}">
                                <i class="fa-solid fa-ticket mr-1"></i> {{ $sisa > 0 ? "Sisa $sisa Tiket" : 'Tiket Habis' }}
                            </span>
                        </div>
                    </div>

                    <!-- Detail Isi -->
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="text-[11px] text-slate-400 flex items-center gap-1.5 mb-2">
                                <i class="fa-regular fa-calendar-check text-emerald-400"></i>
                                <span>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }} • {{ date('H:i', strtotime($item->waktu)) }} WIB</span>
                            </div>

                            <h3 class="heading-font text-lg font-bold text-white mb-2 group-hover:text-emerald-400 transition leading-snug">
                                <a href="{{ route('workshops.show', $item->id) }}">{{ $item->nama_workshop }}</a>
                            </h3>

                            <p class="text-slate-400 text-xs mb-4 line-clamp-2 leading-relaxed">
                                {{ $item->deskripsi }}
                            </p>
                        </div>

                        <div class="space-y-2 text-xs text-slate-400 border-t border-slate-800/80 pt-4">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-user-tie text-emerald-400 w-4"></i>
                                <span>Pemateri: <strong class="text-slate-200">{{ $item->pemateri }}</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-emerald-400 w-4"></i>
                                <span class="truncate">{{ $item->lokasi }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Kartu -->
                    <div class="px-6 py-4 bg-[#090d16] border-t border-slate-800 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase font-semibold block">Harga Tiket</span>
                            <span class="text-lg font-black text-emerald-400">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('workshops.show', $item->id) }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black px-4 py-2.5 rounded-xl transition shadow">
                            <span>Daftar</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-[#0f172a] p-12 text-center rounded-2xl border border-slate-800">
                    <i class="fa-solid fa-chalkboard-user text-4xl text-slate-600 mb-3"></i>
                    <p class="text-slate-400 font-medium text-sm">Belum ada workshop yang dibuka saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
