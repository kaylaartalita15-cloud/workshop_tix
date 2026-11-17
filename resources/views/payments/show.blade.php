@extends('layouts.app')

@section('title', 'Pembayaran Tiket Midtrans - WorkshopTix')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-[#0f172a] rounded-3xl border border-slate-800 overflow-hidden shadow-2xl">
        <!-- Banner Gambar Workshop yang Dibeli -->
        <div class="relative h-44 w-full overflow-hidden bg-slate-900">
            <img src="{{ $registration->workshop->gambar_url }}" alt="{{ $registration->workshop->nama_workshop }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/60 to-transparent"></div>

            <div class="absolute bottom-4 left-6 right-6 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold px-2.5 py-0.5 bg-emerald-500 text-slate-950 rounded uppercase">
                        {{ $registration->workshop->kategori }}
                    </span>
                    <h2 class="heading-font text-lg font-bold text-white mt-1">{{ $registration->workshop->nama_workshop }}</h2>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-slate-400 block uppercase font-mono">Order ID</span>
                    <span class="font-mono text-xs text-emerald-400 font-bold">{{ $payment->order_id }}</span>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            <!-- Rincian Pesanan & Pengurangan Tiket -->
            <div class="bg-[#090d16] border border-slate-800 rounded-2xl p-5 space-y-4">
                <div class="flex justify-between items-center text-xs border-b border-slate-800 pb-3">
                    <span class="text-slate-400">Nama Peserta:</span>
                    <span class="font-bold text-white">{{ $registration->user->name }} ({{ $registration->user->email }})</span>
                </div>

                <div class="flex justify-between items-center text-xs border-b border-slate-800 pb-3">
                    <span class="text-slate-400">Jadwal & Lokasi:</span>
                    <span class="font-bold text-slate-200">{{ \Carbon\Carbon::parse($registration->workshop->tanggal)->translatedFormat('d M Y') }} • {{ $registration->workshop->lokasi }}</span>
                </div>

                <div class="flex justify-between items-center text-xs border-b border-slate-800 pb-3">
                    <span class="text-slate-400">Status Stok Tiket:</span>
                    <span class="font-mono text-emerald-400 font-bold bg-emerald-500/10 px-2.5 py-1 rounded border border-emerald-500/20">
                        1 Tiket dialokasikan untuk Anda
                    </span>
                </div>

                <div class="flex justify-between items-center text-sm pt-2">
                    <span class="font-bold text-slate-300">Total Pembayaran:</span>
                    <span class="heading-font text-2xl font-black text-emerald-400">
                        Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Tombol Bayar & Simulator Sandbox Sesuai LKPD 04 -->
            <div class="space-y-4">
                <button id="pay-button" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black py-4 px-4 rounded-xl shadow-lg shadow-emerald-500/25 transition duration-200 flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Bayar Sekarang (Midtrans Snap Sandbox)</span>
                </button>

                <!-- Panel Simulasi Pembayaran Sandbox (Sesuai panduan LKPD: verifikasi status melalui backend) -->
                <div class="p-4 bg-slate-900 rounded-2xl border border-slate-800">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-vial text-emerald-400 text-sm"></i>
                        <h5 class="text-xs font-bold text-white uppercase tracking-wider">Simulasi Verifikasi Sandbox (LKPD Drill)</h5>
                    </div>
                    <p class="text-xs text-slate-400 mb-3">
                        Gunakan tombol di bawah ini untuk mensimulasikan pembayaran langsung di backend jika Snap popup offline:
                    </p>
                    <form action="{{ route('payments.simulate-success', $payment->id) }}" method="POST">
                        @csrf
                        <div class="flex gap-2">
                            <select name="payment_type" class="bg-[#090d16] border border-slate-700 text-xs rounded-xl px-3 py-2 text-slate-300 flex-1 focus:outline-none">
                                <option value="bank_transfer (BCA Virtual Account)">BCA Virtual Account (Sandbox)</option>
                                <option value="gopay">GoPay / QRIS Instant (Sandbox)</option>
                                <option value="credit_card">Kartu Kredit Test (Sandbox)</option>
                            </select>
                            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-emerald-400 border border-emerald-500/30 font-bold px-4 py-2 rounded-xl text-xs transition shadow flex items-center gap-1.5 whitespace-nowrap">
                                <i class="fa-solid fa-check-double"></i>
                                <span>Verifikasi Lunas</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(!str_starts_with($snapToken, 'DEMO_SNAP_'))
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
@endif
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        var snapToken = "{{ $snapToken }}";
        if (typeof window.snap !== 'undefined' && snapToken && !snapToken.startsWith('DEMO_SNAP_')) {
            window.snap.pay(snapToken, {
                onSuccess: function(result){
                    var form = document.querySelector('form[action*="simulate-success"]');
                    if (form) form.submit();
                },
                onPending: function(result){
                    alert("Menunggu pembayaran dari Anda.");
                    location.reload();
                },
                onError: function(result){
                    alert("Pembayaran gagal! Silakan gunakan tombol simulasi verifikasi.");
                },
                onClose: function(){
                    alert('Anda menutup popup pembayaran tanpa menyelesaikan transaksi.');
                }
            });
        } else {
            if (confirm("Mode Sandbox Aktif. Apakah Anda ingin mensimulasikan pembayaran lunas sekarang?")) {
                var form = document.querySelector('form[action*="simulate-success"]');
                if (form) form.submit();
            }
        }
    };
</script>
@endpush
