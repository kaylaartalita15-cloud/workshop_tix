@extends('layouts.app')

@section('title', 'Daftar Akun Baru - WorkshopTix')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-amber-50 text-amber-600 rounded-xl mb-3">
                <i class="fa-solid fa-user-plus text-xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Daftar Akun</h1>
            <p class="text-sm text-slate-500 mt-1">Buat akun untuk memesan tiket workshop IT</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-regular fa-user"></i>
                    </span>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none transition"
                        placeholder="Contoh: Kayla Artalita">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none transition"
                        placeholder="nama@email.com">
                </div>
            </div>

            <div>
                <label for="no_telp" class="block text-sm font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-phone"></i>
                    </span>
                    <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp') }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none transition"
                        placeholder="081234567890">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" id="password" required
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none transition"
                        placeholder="Minimal 6 karakter">
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none transition"
                        placeholder="Ulangi kata sandi">
                </div>
            </div>

            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-indigo-950 font-bold py-2.5 px-4 rounded-xl shadow transition duration-200">
                Daftar Sebagai Peserta
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-100 text-center text-sm text-slate-600">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:underline">Masuk</a>
        </div>
    </div>
</div>
@endsection
