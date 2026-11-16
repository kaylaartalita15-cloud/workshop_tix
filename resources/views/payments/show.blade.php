@extends('layouts.app')

@section('title', 'Pembayaran Midtrans Sandbox - WorkshopTix')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="bg-slate-900 text-white p-6 sm:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-amber-400 text-indigo-950 rounded-lg">
                        <i class="fa-solid fa-shield mr-1"></i> MIDTRANS SANDBOX
                    </span>
                    <h1 class="text-xl sm:text-2xl font-bold mt-3">Pembayaran Tiket Workshop</h1>
                    <p class="text-xs text-slate-400 mt-1">Selesaikan transaksi untuk menerbitkan tiket digital resmi Anda</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-slate-400 block uppercase">Order ID</span>
                    <span class="font-mono text-xs text-amber-300">{{ $payment->order_id }}</span>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            <!-- Rincian Pesanan -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-4">
                <div class="flex justify-between items-start border-b border-slate-200 pb-3">
                    <div>
                        <h4 class="font-bold text-slate-800 text-base">{{ $registration->workshop->nama_workshop }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Pemateri: {{ $registration->workshop->pemateri }}</p>
                        <p class="text-xs text-slate-500">Jadwal: {{ \Carbon\Carbon::parse($registration->workshop->tanggal)->translatedFormat('d M Y') }} - {{ date('H:i', strtotime($registration->workshop->waktu)) }} WIB</p>
                    </div>
                </div>

                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-600">Peserta:</span>
                    <span class="font-semibold text-slate-800">{{ $registration->user->name }} ({{ $registration->user->email }})</span>
                </div>

                <div class="flex justify-between items-center text-sm border-t border-slate-200 pt-3">
                    <span class="font-bold text-slate-700">Total Tagihan:</span>
                    <span class="text-2xl font-extrabold text-indigo-900">
                        Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Tombol Bayar Snap Popup & Simulator Sandbox Sesuai LKPD Point 04 -->
            <div class="space-y-4">
                <!-- Midtrans Snap Popup Button -->
                <button id="pay-button" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition duration-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Bayar dengan Midtrans Snap (Sandbox)</span>
                </button>

                <!-- Panel Simulasi Pembayaran Sandbox (Sesuai panduan LKPD: verifikasi status melalui backend) -->
                <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-vial text-amber-600 text-sm"></i>
                        <h5 class="text-xs font-bold text-amber-900 uppercase tracking-wider">Simulasi Bayar Sandbox (Pengujian LKPD)</h5>
                    </div>
                    <p class="text-xs text-amber-800 mb-3">
                        Gunakan tombol simulator di bawah ini untuk mensimulasikan pembayaran lunas di backend jika popup Snap tidak terbuka karena koneksi sandbox demo:
                    </p>
                    <form action="{{ route('payments.simulate-success', $payment->id) }}" method="POST">
                        @csrf
                        <div class="flex gap-2">
                            <select name="payment_type" class="bg-white border border-amber-300 text-xs rounded-lg px-3 py-2 text-slate-700 flex-1">
                                <option value="bank_transfer (BCA Virtual Account)">BCA Virtual Account (Sandbox)</option>
                                <option value="gopay">GoPay / QRIS (Sandbox)</option>
                                <option value="credit_card">Kartu Kredit Test (Sandbox)</option>
                            </select>
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-indigo-950 font-bold px-4 py-2 rounded-lg text-xs transition shadow flex items-center gap-1.5 whitespace-nowrap">
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
                    // Submit simulasi verifikasi backend
                    var form = document.querySelector('form[action*="simulate-success"]');
                    if (form) form.submit();
                },
                onPending: function(result){
                    alert("Menunggu pembayaran dari Anda.");
                    location.reload();
                },
                onError: function(result){
                    alert("Pembayaran gagal! Silakan gunakan tombol simulasi verifikasi sandbox.");
                },
                onClose: function(){
                    alert('Anda menutup popup pembayaran tanpa menyelesaikan transaksi.');
                }
            });
        } else {
            // Jika Snap JS sandbox offline / demo mode
            if (confirm("Mode Sandbox Aktif. Apakah Anda ingin mensimulasikan pembayaran lunas sekarang?")) {
                var form = document.querySelector('form[action*="simulate-success"]');
                if (form) form.submit();
            }
        }
    };
</script>
@endpush
