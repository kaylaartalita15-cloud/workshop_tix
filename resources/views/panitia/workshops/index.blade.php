@extends('layouts.app')

@section('title', 'Kelola Workshop IT - Panitia WorkshopTix')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Workshop IT</h1>
            <p class="text-sm text-slate-500">Kelola jadwal, biaya pendaftaran, kuota, dan status aktif workshop</p>
        </div>
        <a href="{{ route('panitia.workshops.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Workshop</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Nama Workshop</th>
                        <th class="px-6 py-3.5">Pemateri</th>
                        <th class="px-6 py-3.5">Jadwal & Waktu</th>
                        <th class="px-6 py-3.5">Biaya</th>
                        <th class="px-6 py-3.5">Kuota / Terdaftar</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($workshops as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $item->nama_workshop }}</td>
                            <td class="px-6 py-4">{{ $item->pemateri }}</td>
                            <td class="px-6 py-4 text-xs">
                                <div>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</div>
                                <div class="text-slate-400">{{ date('H:i', strtotime($item->waktu)) }} WIB</div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold">
                                <span class="text-indigo-600">{{ $item->registrations_count }}</span> / {{ $item->kuota }}
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status === 'aktif')
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full">Aktif</span>
                                @else
                                    <span class="bg-slate-100 text-slate-600 text-xs font-bold px-2.5 py-1 rounded-full">Tutup</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('panitia.workshops.edit', $item->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('panitia.workshops.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus workshop ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-400 text-xs">Belum ada workshop yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $workshops->links() }}
        </div>
    </div>
</div>
@endsection
