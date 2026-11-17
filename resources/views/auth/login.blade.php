@extends('layouts.app')

@section('title', 'Masuk Akun - WorkshopTix')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="bg-[#0f172a] p-8 rounded-3xl shadow-2xl border border-slate-800">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-2xl mb-3">
                <i class="fa-solid fa-lock text-xl"></i>
            </div>
            <h1 class="heading-font text-2xl font-black text-white">Masuk Akun</h1>
            <p class="text-xs text-slate-400 mt-1">Masuk untuk memesan tiket workshop IT dan melihat e-ticket</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-slate-300 uppercase mb-1">Email</label>
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
                <label for="password" class="block text-xs font-bold text-slate-300 uppercase mb-1">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                        <i class="fa-solid fa-key"></i>
                    </span>
                    <input type="password" name="password" id="password" required
                        class="w-full pl-10 pr-4 py-2.5 bg-[#090d16] border border-slate-700 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none transition"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-400">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-emerald-500 focus:ring-0">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black py-3 px-4 rounded-xl shadow-lg shadow-emerald-500/20 transition duration-200 text-xs">
                Masuk Sekarang
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-800 text-center text-xs text-slate-400">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="font-bold text-emerald-400 hover:underline">Daftar sekarang</a>
        </div>
    </div>
</div>
@endsection
