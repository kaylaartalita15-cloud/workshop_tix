@extends('layouts.app')

@section('title', 'Detail Pendaftaran - WorkshopTix')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('registrations.index') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Pendaftaran
    </a>

    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase">Kode Pendaftaran</span>
                <span class="text-xl font-mono font-bold text-slate-800">{{ $registration->kode_pendaftaran }}</span>
            </div>
            <div>
                @if($registration->payment && in_array($registration->payment->status, ['success', 'settlement']))
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1.5 rounded-full">LUNAS</span>
                @else
                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1.5 rounded-full">MENUNGGU PEMBAYARAN</span>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $registration->workshop->nama_workshop }}</h2>
                <p class="text-sm text-slate-500 mt-1">Pemateri: {{ $registration->workshop->pemateri }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm bg-slate-50 p-4 rounded-xl">
                <div>
                    <span class="text-xs text-slate-400 block">Jadwal</span>
                    <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($registration->workshop->tanggal)->translatedFormat('d M Y') }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Waktu</span>
                    <span class="font-semibold text-slate-700">{{ date('H:i', strtotime($registration->workshop->waktu)) }} WIB</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Lokasi</span>
                    <span class="font-semibold text-slate-700">{{ $registration->workshop->lokasi }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Total Biaya</span>
                    <span class="font-bold text-indigo-900">Rp {{ number_format($registration->workshop->harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="mt-8 border-t border-slate-100 pt-6">
            @if($registration->payment && in_array($registration->payment->status, ['success', 'settlement']))
                <a href="{{ route('tickets.show', $registration->ticket->id) }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl text-center shadow">
                    <i class="fa-solid fa-qrcode mr-2"></i> Buka Tiket Digital Saya
                </a>
            @else
                <a href="{{ route('payments.show', $registration->id) }}" class="block w-full bg-amber-500 hover:bg-amber-600 text-indigo-950 font-bold py-3 px-4 rounded-xl text-center shadow">
                    <i class="fa-solid fa-credit-card mr-2"></i> Lanjutkan ke Pembayaran
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
