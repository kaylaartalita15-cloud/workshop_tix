@extends('layouts.app')

@section('title', 'Tambah Workshop Baru - Panitia WorkshopTix')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('panitia.workshops.index') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Kelola Workshop
    </a>

    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-800 mb-6">Tambah Workshop IT Baru</h1>

        <form action="{{ route('panitia.workshops.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="nama_workshop" class="block text-sm font-semibold text-slate-700 mb-1">Nama Workshop</label>
                <input type="text" name="nama_workshop" id="nama_workshop" required value="{{ old('nama_workshop') }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none"
                    placeholder="Contoh: Mastering Laravel 12 & RESTful API">
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Lengkap</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none"
                    placeholder="Jelaskan silabus materi yang akan dipelajari peserta...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="pemateri" class="block text-sm font-semibold text-slate-700 mb-1">Nama Pemateri</label>
                    <input type="text" name="pemateri" id="pemateri" required value="{{ old('pemateri') }}"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none"
                        placeholder="Contoh: Kayla Artalita (Senior Backend Eng)">
                </div>

                <div>
                    <label for="lokasi" class="block text-sm font-semibold text-slate-700 mb-1">Lokasi Workshop</label>
                    <input type="text" name="lokasi" id="lokasi" required value="{{ old('lokasi') }}"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none"
                        placeholder="Lab Komputer RPL 1 / Zoom Meeting">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal') }}"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="waktu" class="block text-sm font-semibold text-slate-700 mb-1">Waktu Mulai</label>
                    <input type="time" name="waktu" id="waktu" required value="{{ old('waktu', '09:00') }}"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="harga" class="block text-sm font-semibold text-slate-700 mb-1">Biaya (Rp)</label>
                    <input type="number" name="harga" id="harga" min="0" required value="{{ old('harga', '50000') }}"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="kuota" class="block text-sm font-semibold text-slate-700 mb-1">Kuota Peserta</label>
                    <input type="number" name="kuota" id="kuota" min="1" required value="{{ old('kuota', '30') }}"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-1">Status</label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none">
                        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif (Buka)</option>
                        <option value="tutup" {{ old('status') === 'tutup' ? 'selected' : '' }}>Tutup</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('panitia.workshops.index') }}" class="px-5 py-2.5 text-slate-600 font-semibold text-sm hover:bg-slate-100 rounded-xl transition">Batal</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl transition shadow">
                    Simpan Workshop
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
