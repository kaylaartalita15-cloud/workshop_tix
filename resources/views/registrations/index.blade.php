@extends('layouts.app')

@section('title', 'Tiket & Pendaftaran Saya - WorkshopTix')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-5">
        <div>
            <h1 class="heading-font text-2xl font-black text-white tracking-tight">Tiket & Pendaftaran Saya</h1>
            <p class="text-xs text-slate-400 mt-1">Daftar workshop IT yang telah Anda ikuti dan unduh e-ticket QR Code</p>
        </div>
        <a href="{{ route('workshops.index') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black px-4 py-2.5 rounded-xl transition shadow flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Daftar Workshop Lain</span>
        </a>
    </div>

    <!-- Grid Kartu Tiket Rapi & Bersih -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($registrations as $reg)
            @php
                $isPaid = $reg->payment && in_array($reg->payment->status, ['success', 'settlement']);
            @endphp
            <div class="bg-[#0f172a] rounded-2xl border border-slate-800 overflow-hidden shadow-xl flex flex-col justify-between hover:border-slate-700 transition group">

                <!-- Bagian Gambar Thumbnail dengan Fallback Elegan -->
                <div class="relative h-44 w-full bg-slate-900 overflow-hidden">
                    <img src="{{ $reg->workshop->gambar_url }}"
                         alt=""
                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop&q=80';"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                    <!-- Overlay Gradient Halus -->
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/40 to-transparent"></div>

                    <!-- Badge Kode Pendaftaran -->
                    <div class="absolute top-3 left-3 z-10">
                        <span class="text-[10px] font-mono font-bold px-2.5 py-1 bg-slate-950/85 backdrop-blur text-slate-200 rounded-lg border border-slate-700/80 shadow">
                            {{ $reg->kode_pendaftaran }}
                        </span>
                    </div>

                    <!-- Badge Status Lunas / Pending -->
                    <div class="absolute top-3 right-3 z-10">
                        @if($isPaid)
                            <span class="text-[10px] font-bold px-3 py-1 bg-emerald-500 text-slate-950 rounded-full flex items-center gap-1.5 font-mono shadow-md">
                                <i class="fa-solid fa-check text-[9px]"></i> LUNAS
                            </span>
                        @else
                            <span class="text-[10px] font-bold px-3 py-1 bg-amber-400 text-slate-950 rounded-full flex items-center gap-1.5 font-mono shadow-md">
                                <i class="fa-solid fa-clock text-[9px]"></i> MENUNGGU BAYAR
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Konten Informasi Workshop -->
                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <span class="text-[10px] font-mono font-bold text-emerald-400 uppercase tracking-wider block">
                            {{ $reg->workshop->kategori }}
                        </span>

                        <h3 class="heading-font text-lg font-bold text-white leading-snug group-hover:text-emerald-400 transition">
                            {{ $reg->workshop->nama_workshop }}
                        </h3>

                        <div class="space-y-2 text-xs text-slate-400 pt-1">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-calendar text-emerald-400 w-4"></i>
                                <span>{{ \Carbon\Carbon::parse($reg->workshop->tanggal)->translatedFormat('d M Y') }} • {{ date('H:i', strtotime($reg->workshop->waktu)) }} WIB</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-emerald-400 w-4"></i>
                                <span class="truncate">{{ $reg->workshop->lokasi }}</span>
                            </div>
                        </div>

                        <div class="heading-font text-lg font-black text-emerald-400 pt-1">
                            Rp {{ number_format($reg->workshop->harga, 0, ',', '.') }}
                        </div>
                    </div>

                    <!-- Footer Kartu: Tanggal Daftar & Tombol Aksi -->
                    <div class="border-t border-slate-800/80 pt-4 flex items-center justify-between">
                        <span class="text-[11px] text-slate-500 font-mono">
                            Didaftarkan: {{ $reg->created_at->format('d/m/Y') }}
                        </span>

                        @if($isPaid && $reg->ticket)
                            <a href="{{ route('tickets.show', $reg->ticket->id) }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black px-4 py-2.5 rounded-xl transition shadow-lg shadow-emerald-500/20">
                                <i class="fa-solid fa-qrcode text-sm"></i>
                                <span>Buka Tiket QR</span>
                            </a>
                        @else
                            <a href="{{ route('payments.show', $reg->id) }}" class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-950 text-xs font-black px-4 py-2.5 rounded-xl transition shadow-lg shadow-amber-400/20">
                                <i class="fa-solid fa-credit-card text-sm"></i>
                                <span>Bayar Sekarang</span>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full bg-[#0f172a] p-12 text-center rounded-3xl border border-slate-800">
                <i class="fa-solid fa-ticket text-4xl text-slate-600 mb-3 block"></i>
                <h3 class="text-base font-bold text-white mb-1">Belum Ada Tiket yang Dipesan</h3>
                <p class="text-xs text-slate-400 mb-5">Pilih workshop IT yang Anda minati untuk memulai pendaftaran.</p>
                <a href="{{ route('workshops.index') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black px-5 py-2.5 rounded-xl transition shadow inline-block">
                    Cari Workshop IT
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
