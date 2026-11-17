@extends('layouts.app')

@section('title', 'Kelola Workshop IT - Panitia WorkshopTix')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-5">
        <div>
            <h1 class="heading-font text-2xl font-black text-white">Manajemen Workshop IT</h1>
            <p class="text-xs text-slate-400 mt-1">Kelola jadwal, biaya tiket, kuota awal, dan status penerimaan peserta</p>
        </div>
        <a href="{{ route('panitia.workshops.create') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs px-4 py-2.5 rounded-xl transition shadow-lg shadow-emerald-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Workshop Baru</span>
        </a>
    </div>

    <div class="bg-[#0f172a] rounded-3xl border border-slate-800 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-300">
                <thead class="text-[10px] text-slate-400 uppercase bg-[#090d16] border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Workshop</th>
                        <th class="px-6 py-4">Pemateri</th>
                        <th class="px-6 py-4">Jadwal & Waktu</th>
                        <th class="px-6 py-4">Biaya Tiket</th>
                        <th class="px-6 py-4">Terdaftar / Kuota</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-sans">
                    @forelse($workshops as $item)
                        <tr class="hover:bg-slate-800/30">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-900 border border-slate-800 flex-shrink-0">
                                        <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_workshop }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span class="font-bold text-white text-xs block">{{ $item->nama_workshop }}</span>
                                        <span class="text-[10px] font-mono text-emerald-400">{{ $item->kategori }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-300">{{ $item->pemateri }}</td>
                            <td class="px-6 py-4 text-xs font-mono">
                                <div class="text-slate-200">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</div>
                                <div class="text-slate-500 text-[10px]">{{ date('H:i', strtotime($item->waktu)) }} WIB</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-emerald-400 font-mono text-xs">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono">
                                <span class="text-white font-bold">{{ $item->registrations_count }}</span>
                                <span class="text-slate-500">/ {{ $item->kuota }} pcs</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status === 'aktif')
                                    <span class="bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold px-2.5 py-0.5 rounded-full font-mono">AKTIF</span>
                                @else
                                    <span class="bg-rose-500/15 text-rose-300 border border-rose-500/30 text-[10px] font-bold px-2.5 py-0.5 rounded-full font-mono">TUTUP</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('panitia.workshops.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/10 rounded-lg transition" title="Edit Workshop">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('panitia.workshops.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus workshop ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition" title="Hapus Workshop">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-500 text-xs">Belum ada workshop yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-800 bg-[#090d16]">
            {{ $workshops->links() }}
        </div>
    </div>
</div>
@endsection
