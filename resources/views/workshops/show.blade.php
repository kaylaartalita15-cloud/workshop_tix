@extends('layouts.app')

@section('title', $workshop->nama_workshop . ' - WorkshopTix')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('workshops.index') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Workshop
    </a>

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="bg-indigo-900 text-white p-8 sm:p-10 relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-block px-3 py-1 bg-amber-400 text-indigo-950 font-bold text-xs rounded-full mb-3 uppercase tracking-wide">
                    Workshop IT Eksklusif
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold mb-3">{{ $workshop->nama_workshop }}</h1>
                <p class="text-indigo-200 text-sm sm:text-base flex items-center gap-2">
                    <i class="fa-solid fa-user-tie text-amber-400"></i> Bersama <strong>{{ $workshop->pemateri }}</strong>
                </p>
            </div>
            <i class="fa-solid fa-code absolute right-6 -bottom-6 text-9xl text-white/5 pointer-events-none"></i>
        </div>

        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Deskripsi Workshop</h3>
                    <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $workshop->deskripsi }}</p>
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Informasi Pelaksanaan</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl">
                            <i class="fa-regular fa-calendar text-indigo-600 text-lg mt-0.5"></i>
                            <div>
                                <span class="text-xs text-slate-400 font-semibold block">TANGGAL</span>
                                <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('l, d F Y') }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl">
                            <i class="fa-regular fa-clock text-indigo-600 text-lg mt-0.5"></i>
                            <div>
                                <span class="text-xs text-slate-400 font-semibold block">WAKTU</span>
                                <span class="text-sm font-bold text-slate-800">{{ date('H:i', strtotime($workshop->waktu)) }} WIB - Selesai</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl">
                            <i class="fa-solid fa-location-dot text-indigo-600 text-lg mt-0.5"></i>
                            <div>
                                <span class="text-xs text-slate-400 font-semibold block">LOKASI</span>
                                <span class="text-sm font-bold text-slate-800">{{ $workshop->lokasi }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl">
                            <i class="fa-solid fa-users text-indigo-600 text-lg mt-0.5"></i>
                            <div>
                                <span class="text-xs text-slate-400 font-semibold block">SISA KUOTA</span>
                                <span class="text-sm font-bold {{ $sisaKuota > 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $sisaKuota }} Peserta</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Pendaftaran & Harga -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 flex flex-col justify-between space-y-6">
                <div>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Investasi Pelatihan</span>
                    <div class="text-3xl font-extrabold text-indigo-950 mt-1">
                        Rp {{ number_format($workshop->harga, 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Termasuk E-Sertifikat, Source Code Project, dan Tiket Masuk Digital.</p>
                </div>

                <div class="space-y-3">
                    @auth
                        @if($sudahTerdaftar)
                            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-center space-y-2">
                                <div class="text-xs font-bold text-emerald-700 flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-circle-check"></i> Anda Sudah Terdaftar!
                                </div>
                                <p class="text-xs text-slate-600">Kode: <span class="font-mono font-bold">{{ $registration->kode_pendaftaran }}</span></p>

                                @if($registration->payment && ($registration->payment->status === 'success' || $registration->payment->status === 'settlement'))
                                    <a href="{{ route('tickets.show', $registration->ticket->id) }}" class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow text-center">
                                        <i class="fa-solid fa-qrcode mr-1"></i> Buka Tiket Digital
                                    </a>
                                @else
                                    <a href="{{ route('payments.show', $registration->id) }}" class="block w-full bg-amber-500 hover:bg-amber-600 text-indigo-950 font-bold py-2.5 px-4 rounded-xl text-xs transition shadow text-center">
                                        <i class="fa-solid fa-credit-card mr-1"></i> Lanjutkan Pembayaran
                                    </a>
                                @endif
                            </div>
                        @elseif($sisaKuota <= 0)
                            <button disabled class="w-full bg-slate-300 text-slate-500 font-bold py-3 px-4 rounded-xl text-sm cursor-not-allowed">
                                Kuota Penuh
                            </button>
                        @elseif($workshop->status !== 'aktif')
                            <button disabled class="w-full bg-slate-300 text-slate-500 font-bold py-3 px-4 rounded-xl text-sm cursor-not-allowed">
                                Pendaftaran Ditutup
                            </button>
                        @else
                            <form action="{{ route('registrations.store', $workshop->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    <span>Daftar Workshop Ini</span>
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block text-center w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition shadow-md">
                            Masuk untuk Mendaftar
                        </a>
                        <p class="text-[11px] text-center text-slate-400">Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 font-semibold underline">Daftar di sini</a></p>
                    @endauth
                </div>

                <div class="text-[11px] text-slate-400 flex items-center justify-center gap-2 border-t border-slate-200 pt-4">
                    <i class="fa-solid fa-shield-halved text-emerald-500"></i>
                    <span>Pembayaran Aman via Midtrans Sandbox</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
