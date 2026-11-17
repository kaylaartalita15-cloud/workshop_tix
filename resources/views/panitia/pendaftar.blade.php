@extends('layouts.app')

@section('title', 'Data Pendaftar (Uji N+1) - Panitia WorkshopTix')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-5">
        <div>
            <h1 class="heading-font text-2xl font-black text-white">Data Seluruh Pendaftar Workshop</h1>
            <p class="text-xs text-slate-400 mt-1">Menampilkan data pendaftar, workshop IT, status bayar, dan tiket terbit</p>
        </div>

        <!-- Tombol Uji N+1 Query Problem Sesuai LKPD 05 -->
        <div class="bg-[#090d16] border border-slate-800 p-1.5 rounded-2xl flex items-center gap-1.5">
            <span class="text-[10px] font-bold text-slate-400 px-2 uppercase">Mode Query (LKPD 05):</span>
            <a href="{{ route('panitia.pendaftar', ['eager_loading' => '1']) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $mode === '1' ? 'bg-emerald-500 text-slate-950 font-black shadow-lg shadow-emerald-500/20' : 'text-slate-400 hover:text-white' }}">
               <i class="fa-solid fa-bolt mr-1"></i> Eager Loading (Anti N+1)
            </a>
            <a href="{{ route('panitia.pendaftar', ['eager_loading' => '0']) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $mode === '0' ? 'bg-rose-500 text-white font-black shadow-lg shadow-rose-500/20' : 'text-slate-400 hover:text-white' }}">
               <i class="fa-solid fa-triangle-exclamation mr-1"></i> Lazy Loading (N+1 Test)
            </a>
        </div>
    </div>

    <!-- Alert Edukasi Query LKPD 05 -->
    @if($mode === '1')
        <div class="bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 p-4 rounded-2xl text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-base text-emerald-400"></i>
                <span><strong>Eager Loading Aktif (Rekomendasi):</strong> Menggunakan <code>Registration::with(['user', 'workshop', 'payment', 'ticket'])</code>. Menyatukan data via relasi sekaligus sehingga query database tetap konstan (hanya 5 query) berapapun banyaknya baris peserta.</span>
            </div>
        </div>
    @else
        <div class="bg-rose-950/40 border border-rose-500/30 text-rose-300 p-4 rounded-2xl text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-base text-rose-400"></i>
                <span><strong>Lazy Loading Aktif (Simulasi Masalah N+1):</strong> Setiap baris pendaftar memanggil query database berulang untuk mencari user, workshop, payment, dan tiket ($1 + 4 \times N$ query).</span>
            </div>
        </div>
    @endif

    <div class="bg-[#0f172a] rounded-3xl border border-slate-800 overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-300">
                <thead class="text-[10px] text-slate-400 uppercase bg-[#090d16] border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-3.5">Kode Registrasi</th>
                        <th class="px-6 py-3.5">Nama Peserta & Kontak</th>
                        <th class="px-6 py-3.5">Workshop Terdaftar</th>
                        <th class="px-6 py-3.5">Status Bayar</th>
                        <th class="px-6 py-3.5">Kode Tiket Digital</th>
                        <th class="px-6 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-sans">
                    @forelse($pendaftar as $item)
                        <tr class="hover:bg-slate-800/30">
                            <td class="px-6 py-4 font-mono font-bold text-emerald-400 text-xs">{{ $item->kode_pendaftaran }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-white text-xs">{{ $item->user->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $item->user->email }} • {{ $item->user->no_telp ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-200">{{ $item->workshop->nama_workshop }}</span>
                                <span class="block text-[11px] text-emerald-400 font-mono">Rp {{ number_format($item->workshop->harga, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status === 'lunas')
                                    <span class="bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold px-2.5 py-0.5 rounded-full font-mono">LUNAS</span>
                                @else
                                    <span class="bg-amber-500/15 text-amber-300 border border-amber-500/30 text-[10px] font-bold px-2.5 py-0.5 rounded-full font-mono">PENDING</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">
                                @if($item->ticket)
                                    <span class="text-emerald-400 font-bold">{{ $item->ticket->kode_tiket }}</span>
                                    <span class="block text-[10px] uppercase {{ $item->ticket->status === 'digunakan' ? 'text-teal-400' : 'text-slate-500' }}">({{ $item->ticket->status }})</span>
                                @else
                                    <span class="text-slate-600">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->ticket)
                                    <a href="{{ route('tickets.show', $item->ticket->id) }}" class="text-xs text-emerald-400 hover:text-emerald-300 font-bold underline">
                                        Lihat Tiket QR
                                    </a>
                                @else
                                    <span class="text-[11px] text-slate-500">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500 text-xs">Belum ada peserta terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800">
            {{ $pendaftar->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
