@extends('layouts.app')

@section('title', 'Dashboard Panitia - WorkshopTix')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800">Dashboard Panitia</h1>
            <p class="text-sm text-slate-500 mt-1">Ringkasan pendaftaran workshop IT dan manajemen tiket</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('panitia.workshops.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Workshop Baru</span>
            </a>
            <a href="{{ route('panitia.pendaftar') }}" class="bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow flex items-center gap-2">
                <i class="fa-solid fa-users"></i>
                <span>Lihat Semua Pendaftar</span>
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase">Total Workshop</span>
                <div class="text-2xl font-bold text-slate-800">{{ $totalWorkshop }}</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-id-card"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase">Total Pendaftar</span>
                <div class="text-2xl font-bold text-slate-800">{{ $totalPendaftar }}</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-ticket"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase">Tiket Lunas</span>
                <div class="text-2xl font-bold text-emerald-600">{{ $totalLunas }}</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase">Total Pendapatan</span>
                <div class="text-2xl font-bold text-amber-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Pendaftar Terakhir -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800 text-lg">Pendaftar Terbaru</h2>
            <a href="{{ route('panitia.pendaftar') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Kode Reg</th>
                        <th class="px-6 py-3">Nama Peserta</th>
                        <th class="px-6 py-3">Workshop IT</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($latestRegistrations as $reg)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-mono font-bold text-slate-800 text-xs">{{ $reg->kode_pendaftaran }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $reg->user->name }}</td>
                            <td class="px-6 py-4">{{ $reg->workshop->nama_workshop }}</td>
                            <td class="px-6 py-4">
                                @if($reg->status === 'lunas')
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-0.5 rounded-full">Lunas</span>
                                @else
                                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-0.5 rounded-full">Menunggu</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">{{ $reg->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-xs">Belum ada transaksi pendaftaran masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
