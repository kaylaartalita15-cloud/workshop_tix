@extends('layouts.app')

@section('title', 'Dashboard Panitia IT - WorkshopTix')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    <!-- Header Admin -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-5">
        <div>
            <h1 class="heading-font text-2xl sm:text-3xl font-black text-white">Dashboard Monitoring Panitia</h1>
            <p class="text-xs text-slate-400 mt-1">Rekapitulasi kuota tiket, transaksi pembayaran Midtrans, dan check-in peserta</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('panitia.checkin.index') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs px-4 py-2.5 rounded-xl transition shadow-lg shadow-emerald-500/20 flex items-center gap-2">
                <i class="fa-solid fa-qrcode"></i>
                <span>Scanner Tiket</span>
            </a>
            <a href="{{ route('panitia.workshops.create') }}" class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition border border-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-plus text-emerald-400"></i>
                <span>Tambah Workshop</span>
            </a>
        </div>
    </div>

    <!-- 6 Kartu Statistik Lengkap & Kaya Data -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <!-- 1. Total Workshop -->
        <div class="bg-[#0f172a] p-5 rounded-2xl border border-slate-800">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider">Total Workshop</span>
                <i class="fa-solid fa-laptop-code text-emerald-400"></i>
            </div>
            <div class="heading-font text-2xl font-black text-white">{{ $totalWorkshop }}</div>
            <span class="text-[10px] text-slate-500 mt-1 block">Kelas Pelatihan Aktif</span>
        </div>

        <!-- 2. Total Pendaftar -->
        <div class="bg-[#0f172a] p-5 rounded-2xl border border-slate-800">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider">Pendaftar</span>
                <i class="fa-solid fa-users text-sky-400"></i>
            </div>
            <div class="heading-font text-2xl font-black text-white">{{ $totalPendaftar }}</div>
            <span class="text-[10px] text-slate-500 mt-1 block">Total Peserta Terdaftar</span>
        </div>

        <!-- 3. Tiket Lunas -->
        <div class="bg-[#0f172a] p-5 rounded-2xl border border-slate-800">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider">Tiket Terbit</span>
                <i class="fa-solid fa-ticket text-emerald-400"></i>
            </div>
            <div class="heading-font text-2xl font-black text-emerald-400">{{ $totalLunas }}</div>
            <span class="text-[10px] text-slate-500 mt-1 block">Pembayaran Berhasil</span>
        </div>

        <!-- 4. Menunggu Bayar -->
        <div class="bg-[#0f172a] p-5 rounded-2xl border border-slate-800">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider">Pending</span>
                <i class="fa-solid fa-clock text-amber-400"></i>
            </div>
            <div class="heading-font text-2xl font-black text-amber-400">{{ $totalPending }}</div>
            <span class="text-[10px] text-slate-500 mt-1 block">Menunggu Konfirmasi</span>
        </div>

        <!-- 5. Check-In Masuk -->
        <div class="bg-[#0f172a] p-5 rounded-2xl border border-slate-800">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider">Hadir (Check-In)</span>
                <i class="fa-solid fa-check-double text-teal-400"></i>
            </div>
            <div class="heading-font text-2xl font-black text-teal-300">{{ $totalCheckin }}</div>
            <span class="text-[10px] text-slate-500 mt-1 block">Sudah di Lokasi</span>
        </div>

        <!-- 6. Total Pendapatan -->
        <div class="bg-[#0f172a] p-5 rounded-2xl border border-slate-800">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider">Pendapatan</span>
                <i class="fa-solid fa-wallet text-emerald-400"></i>
            </div>
            <div class="heading-font text-xl font-black text-emerald-400 truncate">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">Midtrans Verified</span>
        </div>
    </div>

    <!-- Monitoring Kuota & Sisa Tiket Real-time per Workshop -->
    <div class="bg-[#0f172a] rounded-3xl border border-slate-800 p-6 sm:p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="heading-font text-lg font-bold text-white">Status Kuota & Pengurangan Sisa Tiket per Workshop</h3>
                <p class="text-xs text-slate-400 mt-0.5">Sisa tiket berkurang otomatis setiap ada pesanan masuk</p>
            </div>
            <a href="{{ route('panitia.workshops.index') }}" class="text-xs font-bold text-emerald-400 hover:underline">
                Kelola Semua &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($workshopsSummary as $ws)
                <div class="bg-[#090d16] rounded-2xl p-5 border border-slate-800 flex flex-col justify-between">
                    <div>
                        <!-- Gambar Workshop Thumbnail -->
                        <div class="h-32 w-full rounded-xl overflow-hidden mb-3 relative bg-slate-900">
                            <img src="{{ $ws->gambar_url }}" alt="{{ $ws->nama_workshop }}" class="w-full h-full object-cover">
                            <span class="absolute top-2 right-2 text-[10px] font-mono font-bold bg-slate-950/80 px-2 py-0.5 rounded text-emerald-400 border border-emerald-500/30">
                                {{ $ws->kategori }}
                            </span>
                        </div>

                        <h4 class="font-bold text-white text-sm mb-1 leading-snug line-clamp-1">{{ $ws->nama_workshop }}</h4>
                        <span class="text-[11px] text-slate-400 block mb-3">Rp {{ number_format($ws->harga, 0, ',', '.') }} • Kuota Awal: {{ $ws->kuota }} pcs</span>

                        <!-- Progress Bar Terisi -->
                        <div class="space-y-1.5 mb-4">
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-400">Terjual: <strong class="text-white">{{ $ws->total_terdaftar }}</strong></span>
                                <span class="text-emerald-400 font-bold font-mono">Sisa: {{ $ws->sisa_kuota }} pcs</span>
                            </div>
                            <div class="w-full h-2 bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500" style="width: {{ $ws->persentase_terisi }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                        <span class="text-slate-400 text-[11px]">Tiket Lunas: <strong class="text-emerald-400">{{ $ws->total_lunas }}</strong></span>
                        <a href="{{ route('panitia.workshops.edit', $ws->id) }}" class="text-emerald-400 hover:text-emerald-300 font-bold text-xs">
                            Edit Kuota
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Grid Data Bawah: Pendaftar Terbaru & Check-in Terkini -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 2 Kolom: Pendaftar Terbaru -->
        <div class="lg:col-span-2 bg-[#0f172a] rounded-3xl border border-slate-800 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="heading-font text-base font-bold text-white">Transaksi Pendaftar Terbaru</h3>
                    <p class="text-xs text-slate-400">Data pendaftar masuk secara real-time</p>
                </div>
                <a href="{{ route('panitia.pendaftar') }}" class="text-xs font-bold text-emerald-400 hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-slate-300">
                    <thead class="text-[10px] text-slate-400 uppercase bg-[#090d16] border-b border-slate-800">
                        <tr>
                            <th class="px-5 py-3">Kode Reg</th>
                            <th class="px-5 py-3">Nama Peserta</th>
                            <th class="px-5 py-3">Workshop</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 font-mono">
                        @forelse($latestRegistrations as $reg)
                            <tr class="hover:bg-slate-800/30 font-sans">
                                <td class="px-5 py-3.5 font-mono font-bold text-emerald-400 text-xs">{{ $reg->kode_pendaftaran }}</td>
                                <td class="px-5 py-3.5 font-bold text-white">{{ $reg->user->name }}</td>
                                <td class="px-5 py-3.5 text-slate-300 max-w-[200px] truncate">{{ $reg->workshop->nama_workshop }}</td>
                                <td class="px-5 py-3.5">
                                    @if($reg->status === 'lunas')
                                        <span class="bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Lunas</span>
                                    @else
                                        <span class="bg-amber-500/15 text-amber-300 border border-amber-500/30 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Pending</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-[11px] text-slate-500">{{ $reg->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-500">Belum ada transaksi pendaftaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 1 Kolom: Log Check-In Terkini di Lapangan -->
        <div class="bg-[#0f172a] rounded-3xl border border-slate-800 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-4">
                    <div>
                        <h3 class="heading-font text-base font-bold text-white">Log Check-In Masuk</h3>
                        <p class="text-xs text-slate-400">Verifikasi tiket peserta di lokasi</p>
                    </div>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                </div>

                <div class="space-y-3">
                    @forelse($recentCheckins as $chk)
                        <div class="p-3 bg-[#090d16] rounded-xl border border-slate-800 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-mono font-bold text-xs text-emerald-400">{{ $chk->kode_tiket }}</span>
                                <span class="text-[9px] bg-emerald-500/20 text-emerald-300 px-2 py-0.5 rounded uppercase font-bold">MASUK</span>
                            </div>
                            <div class="font-bold text-white text-xs">{{ $chk->registration->user->name }}</div>
                            <div class="text-[11px] text-slate-400 truncate">{{ $chk->registration->workshop->nama_workshop }}</div>
                            <div class="text-[10px] text-slate-500 text-right">{{ $chk->updated_at->format('H:i:s') }} WIB</div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-500 text-xs">
                            <i class="fa-solid fa-qrcode text-2xl text-slate-600 mb-2 block"></i>
                            Belum ada aktivitas check-in.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800 mt-4">
                <a href="{{ route('panitia.checkin.index') }}" class="block text-center w-full py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs rounded-xl transition shadow">
                    Buka Scanner QR Code
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
