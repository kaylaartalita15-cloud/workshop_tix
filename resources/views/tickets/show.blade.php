@extends('layouts.app')

@section('title', 'Tiket Digital Resmi - WorkshopTix')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('registrations.index') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Riwayat
        </a>
        <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow flex items-center gap-1.5">
            <i class="fa-solid fa-print"></i>
            <span>Cetak Tiket</span>
        </button>
    </div>

    <!-- Digital Ticket Card (Sesuai LKPD 04: Kode tiket, nama peserta, workshop, dan jadwal) -->
    <div class="bg-white rounded-3xl border-2 border-indigo-900 overflow-hidden shadow-xl relative">
        <!-- Ticket Header -->
        <div class="bg-indigo-950 text-white p-6 relative">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <span class="bg-amber-400 text-indigo-950 p-1.5 rounded-lg text-sm font-bold">
                        <i class="fa-solid fa-ticket"></i>
                    </span>
                    <span class="font-extrabold tracking-wider text-lg">WORKSHOP<span class="text-amber-400">TIX</span></span>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-wider bg-emerald-500 text-white px-2.5 py-0.5 rounded-full">
                    <i class="fa-solid fa-check mr-1"></i> VERIFIED E-TICKET
                </span>
            </div>
            <div class="mt-4">
                <span class="text-[11px] text-amber-400 font-semibold block uppercase">Nama Workshop</span>
                <h1 class="text-xl font-extrabold mt-0.5 text-white">{{ $ticket->registration->workshop->nama_workshop }}</h1>
            </div>
        </div>

        <!-- Ticket Body Details -->
        <div class="p-6 space-y-5 bg-white">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-[11px] text-slate-400 font-semibold block uppercase">Nama Peserta</span>
                    <span class="text-sm font-bold text-slate-800">{{ $ticket->registration->user->name }}</span>
                    <span class="text-xs text-slate-500 block">{{ $ticket->registration->user->email }}</span>
                </div>
                <div>
                    <span class="text-[11px] text-slate-400 font-semibold block uppercase">Pemateri</span>
                    <span class="text-sm font-bold text-slate-800">{{ $ticket->registration->workshop->pemateri }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-3">
                <div>
                    <span class="text-[11px] text-slate-400 font-semibold block uppercase">Jadwal & Waktu</span>
                    <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($ticket->registration->workshop->tanggal)->translatedFormat('d M Y') }}</span>
                    <span class="text-xs text-slate-500 block">{{ date('H:i', strtotime($ticket->registration->workshop->waktu)) }} WIB</span>
                </div>
                <div>
                    <span class="text-[11px] text-slate-400 font-semibold block uppercase">Lokasi Workshop</span>
                    <span class="text-sm font-bold text-slate-800">{{ $ticket->registration->workshop->lokasi }}</span>
                </div>
            </div>

            <!-- Ticket Perforated Divider -->
            <div class="relative py-2 flex items-center justify-between">
                <div class="w-6 h-6 bg-slate-50 border-r-2 border-indigo-900 rounded-full -ml-9"></div>
                <div class="flex-1 border-t-2 border-dashed border-slate-300 mx-2"></div>
                <div class="w-6 h-6 bg-slate-50 border-l-2 border-indigo-900 rounded-full -mr-9"></div>
            </div>

            <!-- QR Code & Ticket Code Section -->
            <div class="text-center space-y-3 pt-1">
                <div class="inline-block p-3 bg-white border-2 border-slate-200 rounded-2xl shadow-sm">
                    <!-- QR Code Generated via QuickChart API / SVG -->
                    <img src="https://quickchart.io/qr?text={{ urlencode($ticket->kode_tiket) }}&size=160&margin=1"
                         alt="QR Code Tiket"
                         class="w-36 h-36 mx-auto rounded-lg">
                </div>
                <div>
                    <span class="text-[11px] text-slate-400 font-semibold block uppercase tracking-wider">KODE TIKET RESMI</span>
                    <span class="font-mono text-2xl font-black text-indigo-950 tracking-widest">{{ $ticket->kode_tiket }}</span>
                </div>
                <p class="text-[11px] text-slate-400">
                    Tunjukkan QR Code ini kepada panitia saat registrasi ulang di lokasi workshop.
                </p>
            </div>
        </div>

        <!-- Ticket Footer -->
        <div class="bg-slate-100 px-6 py-3 border-t border-slate-200 flex justify-between items-center text-[11px] text-slate-500">
            <span>Status Pembayaran: <strong class="text-emerald-700 uppercase">Lunas</strong></span>
            <span>Order ID: <span class="font-mono">{{ $ticket->registration->payment->order_id }}</span></span>
        </div>
    </div>
</div>
@endsection
