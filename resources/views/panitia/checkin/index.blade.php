@extends('layouts.app')

@section('title', 'Scan & Check-In Tiket Digital - Panitia WorkshopTix')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800">Scanner & Check-In Tiket</h1>
            <p class="text-sm text-slate-500 mt-1">Verifikasi keaslian tiket peserta workshop IT saat registrasi ulang di lokasi</p>
        </div>
        <a href="{{ route('panitia.dashboard') }}" class="text-xs font-bold text-slate-600 hover:text-indigo-600 transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    <!-- Scanner Box & Input Form -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Input Scanner Manual / Barcode Scanner -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-barcode"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Input / Scan Kode Tiket</h3>
                <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                    Arahkan scanner barcode/QR ke tiket peserta atau ketikkan kode tiket secara manual (contoh: <strong class="font-mono text-indigo-600">TIX-PESAT-1001</strong>).
                </p>

                <form action="{{ route('panitia.checkin.process') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="kode_tiket" class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Tiket Peserta</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-qrcode"></i>
                            </span>
                            <input type="text" name="kode_tiket" id="kode_tiket" required autofocus
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border-2 border-indigo-200 rounded-xl text-base font-mono font-bold uppercase tracking-wider focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none"
                                placeholder="TIX-XXXX-XXXX">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Verifikasi & Check-In</span>
                    </button>
                </form>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <span class="text-[11px] text-slate-400">Status tiket yang sudah check-in akan langsung berubah menjadi <strong>DIGUNAKAN</strong> untuk mencegah pemakaian ulang.</span>
            </div>
        </div>

        <!-- Camera QR Scanner Simulator -->
        <div class="bg-slate-900 rounded-3xl p-6 sm:p-8 text-white flex flex-col justify-between relative overflow-hidden">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-semibold px-2.5 py-1 bg-amber-400 text-indigo-950 rounded-lg">
                        <i class="fa-solid fa-camera mr-1"></i> OPTICAL SCANNER
                    </span>
                    <span class="text-xs text-emerald-400 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Live Ready
                    </span>
                </div>

                <h3 class="text-lg font-bold mb-2">Simulasi Deteksi Kamera</h3>
                <p class="text-xs text-slate-400 mb-6">Pilih salah satu kode tiket sampel yang tersedia di bawah ini untuk menguji proses check-in instan:</p>

                <div class="space-y-2">
                    <button type="button" onclick="quickFill('TIX-PESAT-1001')" class="w-full text-left p-3 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 transition flex items-center justify-between">
                        <div>
                            <span class="font-mono font-bold text-xs text-amber-300">TIX-PESAT-1001</span>
                            <span class="block text-[11px] text-slate-400">Kayla Artalita (Mastering Laravel 12)</span>
                        </div>
                        <span class="text-[10px] bg-indigo-950 text-indigo-300 border border-indigo-700 px-2 py-0.5 rounded">Isi Otomatis</span>
                    </button>
                </div>
            </div>

            <div class="mt-6 border-t border-slate-800 pt-4 flex items-center justify-between text-xs text-slate-400">
                <span>Algoritma: Strict Hash Matching</span>
                <i class="fa-solid fa-shield-halved text-emerald-400"></i>
            </div>
        </div>
    </div>

    <!-- Riwayat Check-In Terbaru -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-800">Daftar Tiket yang Sudah Check-In</h3>
            <span class="text-xs font-semibold text-slate-400">Terakhir Diperbarui Real-time</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Kode Tiket</th>
                        <th class="px-6 py-3">Nama Peserta</th>
                        <th class="px-6 py-3">Workshop</th>
                        <th class="px-6 py-3">Waktu Masuk</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentCheckins as $chk)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-mono font-bold text-indigo-600 text-xs">{{ $chk->kode_tiket }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $chk->registration->user->name }}</td>
                            <td class="px-6 py-4">{{ $chk->registration->workshop->nama_workshop }}</td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $chk->updated_at->translatedFormat('d M Y, H:i:s') }} WIB</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full">
                                    <i class="fa-solid fa-check-double mr-1"></i> MASUK
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-xs">Belum ada peserta yang melakukan check-in hari ini.</td>
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
