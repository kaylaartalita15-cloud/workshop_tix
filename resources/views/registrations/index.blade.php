@extends('layouts.app')

@section('title', 'Riwayat Pendaftaran & Tiket Saya - WorkshopTix')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-5">
        <div>
            <h1 class="heading-font text-2xl font-black text-white">Tiket & Pendaftaran Saya</h1>
            <p class="text-xs text-slate-400 mt-1">Daftar workshop IT yang telah Anda ikuti dan unduh e-ticket QR Code</p>
        </div>
        <a href="{{ route('workshops.index') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black px-4 py-2.5 rounded-xl transition shadow flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Daftar Workshop Lain</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($registrations as $reg)
            @php
                $isPaid = $reg->payment && in_array($reg->payment->status, ['success', 'settlement']);
            @endphp
            <div class="bg-[#0f172a] rounded-2xl border border-slate-800 overflow-hidden shadow-lg flex flex-col justify-between">
                <!-- Thumbnail Workshop -->
                <div class="relative h-36 w-full overflow-hidden bg-slate-900">
                    <img src="{{ $reg->workshop->gambar_url }}" alt="{{ $reg->workshop->nama_workshop }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/60 to-transparent"></div>

                    <div class="absolute top-3 left-3">
                        <span class="text-[10px] font-mono font-bold px-2 py-0.5 bg-slate-950/80 text-slate-300 rounded border border-slate-700">
                            {{ $reg->kode_pendaftaran }}
                        </span>
                    </div>

                    <div class="absolute top-3 right-3">
                        @if($isPaid)
                            <span class="text-[10px] font-bold px-2.5 py-0.5 bg-emerald-500 text-slate-950 rounded-full flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-circle-check text-[9px]"></i> LUNAS
                            </span>
                        @else
                            <span class="text-[10px] font-bold px-2.5 py-0.5 bg-amber-400 text-slate-950 rounded-full flex items-center gap-1 font-mono">
                                <i class="fa-solid fa-clock text-[9px]"></i> MENUNGGU BAYAR
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="heading-font text-base font-bold text-white mb-2 leading-snug">
                            {{ $reg->workshop->nama_workshop }}
                        </h3>

                        <div class="space-y-1.5 text-xs text-slate-400 mb-4">
                            <p class="flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-emerald-400"></i>
                                <span>{{ \Carbon\Carbon::parse($reg->workshop->tanggal)->translatedFormat('d M Y') }} • {{ date('H:i', strtotime($reg->workshop->waktu)) }} WIB</span>
                            </p>
                            <p class="flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-emerald-400"></i>
                                <span class="truncate">{{ $reg->workshop->lokasi }}</span>
                            </p>
                            <p class="font-bold text-emerald-400 text-sm mt-2">
                                Rp {{ number_format($reg->workshop->harga, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="border-t border-slate-800 pt-4 flex items-center justify-between">
                        <span class="text-[11px] text-slate-500">Didaftarkan: {{ $reg->created_at->format('d/m/Y') }}</span>

                        @if($isPaid && $reg->ticket)
                            <a href="{{ route('tickets.show', $reg->ticket->id) }}" class="inline-flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black px-4 py-2 rounded-xl transition shadow">
                                <i class="fa-solid fa-qrcode"></i>
                                <span>Buka Tiket QR</span>
                            </a>
                        @else
                            <a href="{{ route('payments.show', $reg->id) }}" class="inline-flex items-center gap-1.5 bg-amber-400 hover:bg-amber-300 text-slate-950 text-xs font-black px-4 py-2 rounded-xl transition shadow">
                                <i class="fa-solid fa-credit-card"></i>
                                <span>Bayar Sekarang</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-[#0f172a] p-12 text-center rounded-2xl border border-slate-800">
                <i class="fa-solid fa-ticket text-4xl text-slate-600 mb-3"></i>
                <h3 class="text-sm font-bold text-white mb-1">Belum Ada Tiket yang Dipesan</h3>
                <p class="text-xs text-slate-400 mb-4">Pilih workshop IT yang Anda minati untuk memulai pendaftaran.</p>
                <a href="{{ route('workshops.index') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black px-5 py-2.5 rounded-xl transition shadow inline-block">
                    Cari Workshop IT
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
