@extends('layouts.app')

@section('title', 'Katalog Workshop IT - WorkshopTix')

@section('content')
<div class="space-y-8">
    <!-- Hero Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-950 rounded-3xl p-8 sm:p-12 text-white shadow-xl">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-400 text-indigo-950 uppercase tracking-wider mb-4">
                <i class="fa-solid fa-graduation-cap"></i> Drill Pra Sertifikasi
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4">
                Tingkatkan Skill IT dengan Workshop Terarah.
            </h1>
            <p class="text-indigo-200 text-base sm:text-lg mb-6 leading-relaxed">
                Pilih topik workshop kejuruan RPL, daftarkan diri Anda, lakukan pembayaran otomatis dengan Midtrans, dan dapatkan tiket digital instan ber-QR Code.
            </p>
            <div class="flex flex-wrap gap-4 items-center">
                <a href="#daftar-workshop" class="bg-amber-400 hover:bg-amber-300 text-indigo-950 font-bold px-6 py-3 rounded-xl transition shadow flex items-center gap-2">
                    <span>Lihat Jadwal Workshop</span>
                    <i class="fa-solid fa-arrow-down"></i>
                </a>
            </div>
        </div>
        <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none transform translate-x-10 translate-y-10 text-white">
            <i class="fa-solid fa-laptop-code text-[260px]"></i>
        </div>
    </div>

    <!-- Workshop Grid Section -->
    <div id="daftar-workshop" class="pt-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Daftar Workshop IT Tersedia</h2>
                <p class="text-sm text-slate-500">Pilih workshop yang ingin Anda ikuti sebelum kuota habis</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($workshops as $item)
                @php
                    $terdaftar = $item->registrations()->count();
                    $sisa = $item->kuota - $terdaftar;
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex justify-between items-start gap-2 mb-3">
                            <span class="text-xs font-semibold px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg">
                                <i class="fa-regular fa-calendar-check mr-1"></i> {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </span>
                            <span class="text-xs font-bold px-2 py-0.5 {{ $sisa > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }} rounded-full">
                                {{ $sisa > 0 ? "Sisa $sisa Kuota" : 'Penuh' }}
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-slate-800 mb-2 hover:text-indigo-600 transition">
                            <a href="{{ route('workshops.show', $item->id) }}">{{ $item->nama_workshop }}</a>
                        </h3>

                        <p class="text-slate-600 text-sm mb-4 line-clamp-2">
                            {{ $item->deskripsi }}
                        </p>

                        <div class="space-y-2 text-xs text-slate-500 border-t border-slate-100 pt-3">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-user-tie text-indigo-500 w-4"></i>
                                <span>Pemateri: <strong class="text-slate-700">{{ $item->pemateri }}</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-clock text-indigo-500 w-4"></i>
                                <span>Waktu: {{ date('H:i', strtotime($item->waktu)) }} WIB</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-indigo-500 w-4"></i>
                                <span>Lokasi: {{ $item->lokasi }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] text-slate-400 block font-semibold uppercase">Biaya Registrasi</span>
                            <span class="text-lg font-extrabold text-indigo-900">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('workshops.show', $item->id) }}" class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">
                            <span>Detail</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-12 text-center rounded-2xl border border-slate-200">
                    <i class="fa-solid fa-chalkboard-user text-4xl text-slate-300 mb-3"></i>
                    <p class="text-slate-500 font-medium">Belum ada workshop yang dibuka saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
