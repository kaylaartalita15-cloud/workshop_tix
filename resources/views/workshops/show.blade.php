@extends('layouts.app')

@section('title', $workshop->nama_workshop . ' - WorkshopTix')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('workshops.index') }}" class="inline-flex items-center text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Katalog Workshop
    </a>

    <div class="bg-[#0f172a] rounded-3xl border border-slate-800 overflow-hidden shadow-2xl">
        <!-- Hero Header dengan Gambar HD -->
        <div class="relative h-64 sm:h-80 w-full overflow-hidden bg-slate-900">
            <img src="{{ $workshop->gambar_url }}" alt="{{ $workshop->nama_workshop }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/70 to-transparent"></div>

            <div class="absolute bottom-6 left-6 right-6">
                <span class="inline-block px-3 py-1 bg-emerald-500 text-slate-950 font-black text-xs rounded-lg mb-2 uppercase tracking-wide">
                    {{ $workshop->kategori ?? 'Workshop IT' }}
                </span>
                <h1 class="heading-font text-2xl sm:text-4xl font-black text-white leading-tight mb-2">{{ $workshop->nama_workshop }}</h1>
                <p class="text-slate-300 text-xs sm:text-sm flex items-center gap-2">
                    <i class="fa-solid fa-user-tie text-emerald-400"></i> Bersama <strong>{{ $workshop->pemateri }}</strong>
                </p>
            </div>
        </div>

        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-6">
                <div>
                    <h3 class="heading-font text-lg font-bold text-white mb-2">Deskripsi Workshop</h3>
                    <div class="text-slate-300 text-sm leading-relaxed whitespace-pre-line bg-slate-900/60 p-5 rounded-2xl border border-slate-800">
                        {{ $workshop->deskripsi }}
                    </div>
                </div>

                <div class="border-t border-slate-800 pt-6">
                    <h3 class="heading-font text-lg font-bold text-white mb-4">Informasi Pelaksanaan</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3 p-4 bg-slate-900/80 rounded-2xl border border-slate-800">
                            <i class="fa-regular fa-calendar text-emerald-400 text-xl mt-0.5"></i>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold block uppercase">TANGGAL</span>
                                <span class="text-sm font-bold text-white">{{ \Carbon\Carbon::parse($workshop->tanggal)->translatedFormat('l, d F Y') }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-900/80 rounded-2xl border border-slate-800">
                            <i class="fa-regular fa-clock text-emerald-400 text-xl mt-0.5"></i>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold block uppercase">WAKTU</span>
                                <span class="text-sm font-bold text-white">{{ date('H:i', strtotime($workshop->waktu)) }} WIB</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-900/80 rounded-2xl border border-slate-800">
                            <i class="fa-solid fa-location-dot text-emerald-400 text-xl mt-0.5"></i>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold block uppercase">LOKASI</span>
                                <span class="text-sm font-bold text-white">{{ $workshop->lokasi }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-900/80 rounded-2xl border border-slate-800">
                            <i class="fa-solid fa-ticket text-emerald-400 text-xl mt-0.5"></i>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold block uppercase">SISA TIKET</span>
                                <span class="text-sm font-black {{ $workshop->sisa_tiket > 0 ? 'text-emerald-400' : 'text-rose-400' }}">{{ $workshop->sisa_tiket }} Pcs Tersedia</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Beli & Status -->
            <div class="bg-[#090d16] p-6 rounded-3xl border border-slate-800 flex flex-col justify-between space-y-6">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Biaya Pendaftaran</span>
                    <div class="text-3xl font-black text-emerald-400 mt-1">
                        Rp {{ number_format($workshop->harga, 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-slate-400 mt-2">Termasuk E-Sertifikat Kejuruan RPL, Modul Praktik, dan Tiket Digital Resmi QR Code.</p>
                </div>

                <div class="space-y-3">
                    @auth
                        @if($sudahTerdaftar)
                            <div class="p-4 bg-emerald-950/60 border border-emerald-500/40 rounded-2xl text-center space-y-2">
                                <div class="text-xs font-bold text-emerald-300 flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-circle-check"></i> Anda Sudah Terdaftar!
                                </div>
                                <p class="text-xs text-slate-400">Kode: <span class="font-mono font-bold text-white">{{ $registration->kode_pendaftaran }}</span></p>

                                @if($registration->payment && ($registration->payment->status === 'success' || $registration->payment->status === 'settlement'))
                                    <a href="{{ route('tickets.show', $registration->ticket->id) }}" class="block w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black py-2.5 px-4 rounded-xl text-xs transition shadow text-center">
                                        <i class="fa-solid fa-qrcode mr-1"></i> Buka Tiket Digital
                                    </a>
                                @else
                                    <a href="{{ route('payments.show', $registration->id) }}" class="block w-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-black py-2.5 px-4 rounded-xl text-xs transition shadow text-center">
                                        <i class="fa-solid fa-credit-card mr-1"></i> Selesaikan Pembayaran
                                    </a>
                                @endif
                            </div>
                        @elseif($workshop->sisa_tiket <= 0)
                            <button disabled class="w-full bg-slate-800 text-slate-500 font-bold py-3 px-4 rounded-xl text-xs cursor-not-allowed border border-slate-700">
                                <i class="fa-solid fa-ban mr-1"></i> Tiket Habis (Sold Out)
                            </button>
                        @elseif($workshop->status !== 'aktif')
                            <button disabled class="w-full bg-slate-800 text-slate-500 font-bold py-3 px-4 rounded-xl text-xs cursor-not-allowed border border-slate-700">
                                Pendaftaran Ditutup
                            </button>
                        @else
                            <form action="{{ route('registrations.store', $workshop->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black py-3.5 px-4 rounded-xl text-xs transition shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <span>Pesan Tiket Sekarang</span>
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block text-center w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black py-3.5 px-4 rounded-xl text-xs transition shadow-lg shadow-emerald-500/25">
                            Masuk untuk Memesan Tiket
                        </a>
                        <p class="text-[11px] text-center text-slate-400">Belum punya akun? <a href="{{ route('register') }}" class="text-emerald-400 font-bold underline">Daftar akun</a></p>
                    @endauth
                </div>

                <div class="text-[11px] text-slate-400 flex items-center justify-center gap-2 border-t border-slate-800 pt-4">
                    <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                    <span>Midtrans Sandbox Payment Verified</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
