@extends('layouts.app')

@section('title', 'Pendaftaran & Tiket Saya - WorkshopTix')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Riwayat Pendaftaran & Tiket</h1>
            <p class="text-sm text-slate-500">Kelola status pembayaran dan unduh tiket workshop IT Anda</p>
        </div>
        <a href="{{ route('workshops.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow">
            <i class="fa-solid fa-plus mr-1"></i> Ikuti Workshop Lain
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($registrations as $reg)
            @php
                $isPaid = $reg->payment && in_array($reg->payment->status, ['success', 'settlement']);
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start gap-2 mb-3">
                        <span class="text-xs font-mono font-bold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg">
                            {{ $reg->kode_pendaftaran }}
                        </span>
                        @if($isPaid)
                            <span class="text-xs font-bold px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> LUNAS
                            </span>
                        @else
                            <span class="text-xs font-bold px-3 py-1 bg-amber-100 text-amber-800 rounded-full flex items-center gap-1">
                                <i class="fa-solid fa-clock text-[10px]"></i> MENUNGGU PEMBAYARAN
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-slate-800 mb-1">
                        {{ $reg->workshop->nama_workshop }}
                    </h3>

                    <div class="space-y-1 text-xs text-slate-500 mb-4">
                        <p><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($reg->workshop->tanggal)->translatedFormat('d M Y') }} • {{ date('H:i', strtotime($reg->workshop->waktu)) }} WIB</p>
                        <p><i class="fa-solid fa-location-dot mr-1"></i> {{ $reg->workshop->lokasi }}</p>
                        <p class="font-bold text-slate-700 text-sm mt-2">Rp {{ number_format($reg->workshop->harga, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Didaftarkan: {{ $reg->created_at->format('d/m/Y H:i') }}</span>

                    @if($isPaid && $reg->ticket)
                        <a href="{{ route('tickets.show', $reg->ticket->id) }}" class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow">
                            <i class="fa-solid fa-qrcode"></i>
                            <span>Buka Tiket</span>
                        </a>
                    @else
                        <a href="{{ route('payments.show', $reg->id) }}" class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-indigo-950 text-xs font-bold px-4 py-2 rounded-xl transition shadow">
                            <i class="fa-solid fa-credit-card"></i>
                            <span>Bayar Sekarang</span>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 text-center rounded-2xl border border-slate-200">
                <i class="fa-solid fa-ticket text-4xl text-slate-300 mb-3"></i>
                <h3 class="text-base font-bold text-slate-700 mb-1">Belum Ada Pendaftaran</h3>
                <p class="text-sm text-slate-500 mb-4">Anda belum mendaftar di workshop manapun.</p>
                <a href="{{ route('workshops.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow inline-block">
                    Cari Workshop IT
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
