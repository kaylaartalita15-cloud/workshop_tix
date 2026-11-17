@extends('layouts.app')

@section('title', 'Daftar Akun Baru - WorkshopTix')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="bg-[#0f172a] p-8 rounded-3xl shadow-2xl border border-slate-800">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-2xl mb-3">
                <i class="fa-solid fa-user-plus text-xl"></i>
            </div>
            <h1 class="heading-font text-2xl font-black text-white">Daftar Akun Peserta</h1>
            <p class="text-xs text-slate-400 mt-1">Registrasi akun untuk memesan tiket workshop IT SMK Pesat</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-xs font-bold text-slate-300 uppercase mb-1">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                        <i class="fa-regular fa-user"></i>
                    </span>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none transition"
                        placeholder="Contoh: Kayla Artalita">
                </div>
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-300 uppercase mb-1">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none transition"
                        placeholder="nama@email.com">
                </div>
            </div>

            <div>
                <label for="no_telp" class="block text-xs font-bold text-slate-300 uppercase mb-1">No. WhatsApp / HP</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                        <i class="fa-solid fa-phone"></i>
                    </span>
                    <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp') }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none transition"
                        placeholder="081234567890">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-300 uppercase mb-1">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" id="password" required
                        class="w-full pl-10 pr-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none transition"
                        placeholder="Minimal 6 karakter">
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-300 uppercase mb-1">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full pl-10 pr-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none transition"
                        placeholder="Ulangi kata sandi">
                </div>
            </div>

            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black py-3 px-4 rounded-xl shadow-lg shadow-emerald-500/20 transition duration-200 text-xs">
                Daftar Sebagai Peserta
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-800 text-center text-xs text-slate-400">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="font-bold text-emerald-400 hover:underline">Masuk</a>
        </div>
    </div>
</div>
@endsection
