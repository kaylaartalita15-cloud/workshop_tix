@extends('layouts.app')

@section('title', 'Edit Workshop - Panitia WorkshopTix')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('panitia.workshops.index') }}" class="inline-flex items-center text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Kelola Workshop
    </a>

    <div class="bg-[#0f172a] rounded-3xl border border-slate-800 p-8 shadow-2xl">
        <div class="flex items-center space-x-3 mb-6 border-b border-slate-800 pb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center text-lg">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h1 class="heading-font text-xl font-bold text-white">Edit Workshop IT</h1>
                <p class="text-xs text-slate-400">Perbarui informasi jadwal, kuota tiket, dan materi</p>
            </div>
        </div>

        <form action="{{ route('panitia.workshops.update', $workshop->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_workshop" class="block text-xs font-bold text-slate-300 uppercase mb-1">Nama Workshop</label>
                <input type="text" name="nama_workshop" id="nama_workshop" required value="{{ old('nama_workshop', $workshop->nama_workshop) }}"
                    class="w-full px-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="kategori" class="block text-xs font-bold text-slate-300 uppercase mb-1">Kategori Workshop</label>
                    <select name="kategori" id="kategori" required
                        class="w-full px-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                        <option value="Backend Development" {{ old('kategori', $workshop->kategori) === 'Backend Development' ? 'selected' : '' }}>Backend Development</option>
                        <option value="UI/UX & Frontend" {{ old('kategori', $workshop->kategori) === 'UI/UX & Frontend' ? 'selected' : '' }}>UI/UX & Frontend</option>
                        <option value="DevOps & Cloud" {{ old('kategori', $workshop->kategori) === 'DevOps & Cloud' ? 'selected' : '' }}>DevOps & Cloud</option>
                        <option value="Cybersecurity" {{ old('kategori', $workshop->kategori) === 'Cybersecurity' ? 'selected' : '' }}>Cybersecurity</option>
                        <option value="Artificial Intelligence" {{ old('kategori', $workshop->kategori) === 'Artificial Intelligence' ? 'selected' : '' }}>Artificial Intelligence</option>
                    </select>
                </div>

                <div>
                    <label for="pemateri" class="block text-xs font-bold text-slate-300 uppercase mb-1">Nama Pemateri</label>
                    <input type="text" name="pemateri" id="pemateri" required value="{{ old('pemateri', $workshop->pemateri) }}"
                        class="w-full px-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label for="deskripsi" class="block text-xs font-bold text-slate-300 uppercase mb-1">Deskripsi Lengkap Silabus</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" required
                    class="w-full px-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">{{ old('deskripsi', $workshop->deskripsi) }}</textarea>
            </div>

            <div>
                <label for="gambar" class="block text-xs font-bold text-slate-300 uppercase mb-1">URL Gambar Thumbnail</label>
                <input type="url" name="gambar" id="gambar" value="{{ old('gambar', $workshop->gambar) }}"
                    class="w-full px-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="tanggal" class="block text-xs font-bold text-slate-300 uppercase mb-1">Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal', $workshop->tanggal) }}"
                        class="w-full px-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label for="waktu" class="block text-xs font-bold text-slate-300 uppercase mb-1">Waktu Mulai</label>
                    <input type="time" name="waktu" id="waktu" required value="{{ old('waktu', $workshop->waktu) }}"
                        class="w-full px-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label for="lokasi" class="block text-xs font-bold text-slate-300 uppercase mb-1">Lokasi Workshop</label>
                <input type="text" name="lokasi" id="lokasi" required value="{{ old('lokasi', $workshop->lokasi) }}"
                    class="w-full px-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="harga" class="block text-xs font-bold text-slate-300 uppercase mb-1">Biaya Tiket (Rp)</label>
                    <input type="number" name="harga" id="harga" min="0" required value="{{ old('harga', $workshop->harga) }}"
                        class="w-full px-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white font-mono focus:border-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label for="kuota" class="block text-xs font-bold text-slate-300 uppercase mb-1">Kuota Peserta</label>
                    <input type="number" name="kuota" id="kuota" min="1" required value="{{ old('kuota', $workshop->kuota) }}"
                        class="w-full px-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white font-mono focus:border-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-300 uppercase mb-1">Status</label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                        <option value="aktif" {{ old('status', $workshop->status) === 'aktif' ? 'selected' : '' }}>Aktif (Buka)</option>
                        <option value="tutup" {{ old('status', $workshop->status) === 'tutup' ? 'selected' : '' }}>Tutup</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('panitia.workshops.index') }}" class="px-5 py-2.5 text-slate-400 font-bold text-xs hover:text-white rounded-xl transition">Batal</a>
                <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs px-6 py-2.5 rounded-xl transition shadow-lg shadow-emerald-500/20">
                    Perbarui Workshop
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
