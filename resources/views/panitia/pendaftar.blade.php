@extends('layouts.app')

@section('title', 'Data Pendaftar (Uji N+1) - Panitia WorkshopTix')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Daftar Seluruh Pendaftar Workshop</h1>
            <p class="text-sm text-slate-500">Menampilkan data peserta, workshop terdaftar, dan status tiket</p>
        </div>

        <!-- Tombol Pengujian LKPD Point 05: Periksa N+1 Query Problem -->
        <div class="bg-indigo-50 border border-indigo-200 p-2 rounded-2xl flex items-center gap-2">
            <span class="text-xs font-semibold text-indigo-900 px-2">Mode Query (LKPD 05):</span>
            <a href="{{ route('panitia.pendaftar', ['eager_loading' => '1']) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $mode === '1' ? 'bg-indigo-600 text-white shadow' : 'text-indigo-700 hover:bg-white' }}">
               <i class="fa-solid fa-bolt mr-1"></i> Eager Loading (Optimized)
            </a>
            <a href="{{ route('panitia.pendaftar', ['eager_loading' => '0']) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $mode === '0' ? 'bg-rose-600 text-white shadow' : 'text-rose-700 hover:bg-white' }}">
               <i class="fa-solid fa-triangle-exclamation mr-1"></i> Lazy Loading (N+1 Test)
            </a>
        </div>
    </div>

    <!-- Alert Edukasi LKPD 05 -->
    @if($mode === '1')
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-base text-emerald-600"></i>
                <span><strong>Eager Loading Aktif:</strong> Menggunakan <code>Registration::with(['user', 'workshop', 'payment', 'ticket'])</code>. Menghindari N+1 query problem sehingga hanya butuh ~2-3 query database terlepas dari berapapun jumlah baris data.</span>
            </div>
        </div>
    @else
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-base text-rose-600"></i>
                <span><strong>Lazy Loading Aktif (Pengujian N+1):</strong> Setiap iterasi baris pada tabel melakukan query relasi satu per satu ke database (N query bertambah seiring jumlah pendaftar).</span>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Kode Registrasi</th>
                        <th class="px-6 py-3.5">Nama Peserta & Kontak</th>
                        <th class="px-6 py-3.5">Workshop Diikuti</th>
                        <th class="px-6 py-3.5">Status Bayar</th>
                        <th class="px-6 py-3.5">Kode Tiket</th>
                        <th class="px-6 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendaftar as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-mono font-bold text-slate-800 text-xs">{{ $item->kode_pendaftaran }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $item->user->name }}</div>
                                <div class="text-xs text-slate-400">{{ $item->user->email }} • {{ $item->user->no_telp ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-800">{{ $item->workshop->nama_workshop }}</span>
                                <span class="block text-xs text-slate-400">Rp {{ number_format($item->workshop->harga, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status === 'lunas')
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full">Lunas</span>
                                @else
                                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full">Menunggu</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono text-xs font-semibold">
                                @if($item->ticket)
                                    <span class="text-indigo-600">{{ $item->ticket->kode_tiket }}</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->ticket)
                                    <a href="{{ route('tickets.show', $item->ticket->id) }}" class="text-xs text-indigo-600 hover:text-indigo-900 font-semibold underline">
                                        Lihat Tiket
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400 text-xs">Belum ada peserta terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $pendaftar->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
