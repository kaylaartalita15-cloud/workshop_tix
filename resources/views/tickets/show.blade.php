@extends('layouts.app')

@section('title', 'Tiket Digital Resmi - WorkshopTix')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('registrations.index') }}" class="inline-flex items-center text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Tiket Saya
        </a>
        <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition border border-slate-700 flex items-center gap-1.5 shadow">
            <i class="fa-solid fa-print text-emerald-400"></i>
            <span>Cetak Tiket Digital</span>
        </button>
    </div>

    <!-- Kartu Tiket Digital Berwarna Elegan & Bergambar Sesuai LKPD 04 -->
    <div class="bg-[#0f172a] rounded-3xl border-2 border-emerald-500/50 overflow-hidden shadow-2xl relative text-white">
        <!-- Header Banner Tiket dengan Gambar Workshop -->
        <div class="relative h-48 w-full overflow-hidden bg-slate-900">
            <img src="{{ $ticket->registration->workshop->gambar_url }}" alt="{{ $ticket->registration->workshop->nama_workshop }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/70 to-transparent"></div>

            <div class="absolute top-4 left-6 right-6 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <span class="bg-emerald-500 text-slate-950 p-1.5 rounded-lg text-xs font-black">
                        <i class="fa-solid fa-ticket"></i>
                    </span>
                    <span class="heading-font font-black tracking-wider text-base">WORKSHOP<span class="text-emerald-400">TIX</span></span>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-500 text-slate-950 px-2.5 py-0.5 rounded-full font-mono">
                    <i class="fa-solid fa-check mr-1"></i> LUNAS VERIFIED
                </span>
            </div>

            <div class="absolute bottom-4 left-6 right-6">
                <span class="text-[10px] text-emerald-400 font-bold block uppercase tracking-wider font-mono">{{ $ticket->registration->workshop->kategori }}</span>
                <h1 class="heading-font text-xl font-black mt-0.5 text-white leading-tight">{{ $ticket->registration->workshop->nama_workshop }}</h1>
            </div>
        </div>

        <!-- Detail Tiket -->
        <div class="p-6 space-y-5 bg-[#0f172a]">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-[10px] text-slate-400 font-bold block uppercase">Nama Peserta</span>
                    <span class="text-sm font-bold text-white">{{ $ticket->registration->user->name }}</span>
                    <span class="text-xs text-slate-400 block truncate">{{ $ticket->registration->user->email }}</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 font-bold block uppercase">Pemateri</span>
                    <span class="text-sm font-bold text-white">{{ $ticket->registration->workshop->pemateri }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 border-t border-slate-800/80 pt-3">
                <div>
                    <span class="text-[10px] text-slate-400 font-bold block uppercase">Jadwal & Waktu</span>
                    <span class="text-sm font-bold text-emerald-400">{{ \Carbon\Carbon::parse($ticket->registration->workshop->tanggal)->translatedFormat('d M Y') }}</span>
                    <span class="text-xs text-slate-400 block">{{ date('H:i', strtotime($ticket->registration->workshop->waktu)) }} WIB</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 font-bold block uppercase">Lokasi Workshop</span>
                    <span class="text-sm font-bold text-white">{{ $ticket->registration->workshop->lokasi }}</span>
                </div>
            </div>

            <!-- Perforated Divider Garis Robek Tiket -->
            <div class="relative py-2 flex items-center justify-between">
                <div class="w-6 h-6 bg-[#0b0f19] border-r-2 border-emerald-500/50 rounded-full -ml-9"></div>
                <div class="flex-1 border-t-2 border-dashed border-slate-700 mx-2"></div>
                <div class="w-6 h-6 bg-[#0b0f19] border-l-2 border-emerald-500/50 rounded-full -mr-9"></div>
            </div>

            <!-- QR Code & Kode Tiket -->
            <div class="text-center space-y-3 pt-1">
                <div class="inline-block p-3 bg-white rounded-2xl shadow-lg">
                    <img src="https://quickchart.io/qr?text={{ urlencode($ticket->kode_tiket) }}&size=160&margin=1"
                         alt="QR Code Tiket"
                         class="w-36 h-36 mx-auto rounded-lg">
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">KODE TIKET RESMI PESERTA</span>
                    <span class="font-mono text-2xl font-black text-emerald-400 tracking-widest">{{ $ticket->kode_tiket }}</span>
                </div>
                <p class="text-[11px] text-slate-400">
                    Tunjukkan barcode QR Code ini kepada panitia saat registrasi ulang di lokasi workshop.
                </p>
            </div>
        </div>

        <!-- Ticket Footer -->
        <div class="bg-[#090d16] px-6 py-3 border-t border-slate-800 flex justify-between items-center text-[10px] text-slate-400">
            <span>Status Tiket: <strong class="text-emerald-400 uppercase font-mono">{{ $ticket->status }}</strong></span>
            <span>Order ID: <span class="font-mono text-slate-300">{{ $ticket->registration->payment->order_id }}</span></span>
        </div>
    </div>
</div>
@endsection
