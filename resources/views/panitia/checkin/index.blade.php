@extends('layouts.app')

@section('title', 'Scan & Check-In Tiket - Panitia WorkshopTix')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-5">
        <div>
            <h1 class="heading-font text-2xl sm:text-3xl font-black text-white">Scanner & Check-In Tiket</h1>
            <p class="text-xs text-slate-400 mt-1">Verifikasi keaslian tiket peserta workshop IT saat registrasi ulang di lokasi</p>
        </div>
        <a href="{{ route('panitia.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-emerald-400 transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    <!-- Scanner Box & Input Form -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Input Scanner Manual / Barcode Scanner -->
        <div class="bg-[#0f172a] rounded-3xl border border-slate-800 p-6 sm:p-8 shadow-xl flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-barcode"></i>
                </div>
                <h3 class="heading-font text-lg font-bold text-white mb-2">Input / Scan Kode Tiket</h3>
                <p class="text-xs text-slate-400 mb-6 leading-relaxed">
                    Arahkan scanner ke QR Code tiket peserta atau ketikkan kode tiket secara manual:
                </p>

                <form action="{{ route('panitia.checkin.process') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="kode_tiket" class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">KODE TIKET PESERTA</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                                <i class="fa-solid fa-qrcode"></i>
                            </span>
                            <input type="text" name="kode_tiket" id="kode_tiket" required autofocus
                                class="w-full pl-10 pr-4 py-3 bg-[#090d16] border border-slate-700 rounded-xl text-base font-mono font-bold uppercase tracking-wider text-white focus:border-emerald-500 focus:outline-none"
                                placeholder="TIX-XXXX-XXXX">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black py-3.5 px-4 rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center justify-center gap-2 text-xs">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Verifikasi & Check-In Masuk</span>
                    </button>
                </form>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-800 text-center">
                <span class="text-[10px] text-slate-500">Status tiket yang lolos check-in akan berubah menjadi <strong>DIGUNAKAN</strong> sehingga tidak bisa dipakai ulang.</span>
            </div>
        </div>

        <!-- Panel Cepat Pengujian Scanner -->
        <div class="bg-[#090d16] rounded-3xl p-6 sm:p-8 border border-slate-800 text-white flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-bold px-2.5 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-lg">
                        <i class="fa-solid fa-bolt mr-1"></i> SIMULASI CEPAT CHECK-IN
                    </span>
                    <span class="text-xs text-emerald-400 flex items-center gap-1.5 font-mono">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Ready
                    </span>
                </div>

                <h3 class="heading-font text-lg font-bold mb-2">Uji Tiket Demo</h3>
                <p class="text-xs text-slate-400 mb-6">Klik tombol tiket di bawah ini untuk mengisi form scanner secara otomatis saat demonstrasi sertifikasi:</p>

                <div class="space-y-3">
                    <button type="button" onclick="quickFill('TIX-PESAT-1001')" class="w-full text-left p-3.5 rounded-2xl bg-[#0f172a] hover:border-emerald-500/50 border border-slate-800 transition flex items-center justify-between group">
                        <div>
                            <span class="font-mono font-bold text-xs text-emerald-400">TIX-PESAT-1001</span>
                            <span class="block text-[11px] text-slate-300">Kayla Artalita (Mastering Laravel 12)</span>
                            <span class="text-[10px] text-slate-500 font-mono">Status: Aktif</span>
                        </div>
                        <span class="text-[10px] bg-emerald-500/10 text-emerald-300 border border-emerald-500/30 px-2 py-1 rounded font-bold">Pilih</span>
                    </button>

                    <button type="button" onclick="quickFill('TIX-PESAT-2002')" class="w-full text-left p-3.5 rounded-2xl bg-[#0f172a] hover:border-emerald-500/50 border border-slate-800 transition flex items-center justify-between group">
                        <div>
                            <span class="font-mono font-bold text-xs text-amber-400">TIX-PESAT-2002</span>
                            <span class="block text-[11px] text-slate-300">Budi Santoso (Modern UI/UX Design)</span>
                            <span class="text-[10px] text-slate-500 font-mono">Status: Sudah Dipakai</span>
                        </div>
                        <span class="text-[10px] bg-amber-500/10 text-amber-300 border border-amber-500/30 px-2 py-1 rounded font-bold">Uji Ganda</span>
                    </button>
                </div>
            </div>

            <div class="mt-6 border-t border-slate-800 pt-4 flex items-center justify-between text-xs text-slate-500">
                <span>Tolak Tiket Palsu & Tiket Ganda</span>
                <i class="fa-solid fa-shield-halved text-emerald-400"></i>
            </div>
        </div>
    </div>

    <!-- Riwayat Check-In -->
    <div class="bg-[#0f172a] rounded-3xl border border-slate-800 p-6 sm:p-8 shadow-sm">
        <h3 class="heading-font text-base font-bold text-white mb-4">Daftar Tiket yang Sudah Check-In di Lokasi</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-300">
                <thead class="text-[10px] text-slate-400 uppercase bg-[#090d16] border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-3">Kode Tiket</th>
                        <th class="px-6 py-3">Nama Peserta</th>
                        <th class="px-6 py-3">Workshop</th>
                        <th class="px-6 py-3">Waktu Masuk</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-mono">
                    @forelse($recentCheckins as $chk)
                        <tr class="hover:bg-slate-800/30 font-sans">
                            <td class="px-6 py-4 font-mono font-bold text-emerald-400 text-xs">{{ $chk->kode_tiket }}</td>
                            <td class="px-6 py-4 font-bold text-white">{{ $chk->registration->user->name }}</td>
                            <td class="px-6 py-4 text-slate-300">{{ $chk->registration->workshop->nama_workshop }}</td>
                            <td class="px-6 py-4 text-[11px] text-slate-400 font-mono">{{ $chk->updated_at->translatedFormat('d M Y, H:i:s') }} WIB</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold px-3 py-1 rounded-full font-mono">
                                    <i class="fa-solid fa-check-double mr-1"></i> MASUK
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada peserta yang melakukan check-in.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function quickFill(code) {
        var input = document.getElementById('kode_tiket');
        input.value = code;
        input.focus();
    }
</script>
@endpush
