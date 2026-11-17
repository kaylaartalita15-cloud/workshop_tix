@extends('layouts.app')

@section('title', 'Pembayaran Tiket Midtrans - WorkshopTix')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-[#0f172a] rounded-3xl border border-slate-800 overflow-hidden shadow-2xl">
        <!-- Banner Gambar Workshop yang Dibeli -->
        <div class="relative h-48 w-full overflow-hidden bg-slate-900">
            <img src="{{ $registration->workshop->gambar_url }}" alt="{{ $registration->workshop->nama_workshop }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/70 to-transparent"></div>

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
            <!-- Rincian Pesanan & Status Stok Tiket -->
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
                    <span class="text-slate-400">Status Alokasi Tiket:</span>
                    <span class="font-mono text-emerald-400 font-bold bg-emerald-500/10 px-2.5 py-1 rounded border border-emerald-500/20">
                        <i class="fa-solid fa-lock mr-1"></i> 1 Tiket diamankan untuk Anda
                    </span>
                </div>

                <div class="flex justify-between items-center text-sm pt-2">
                    <span class="font-bold text-slate-300">Total Tagihan Tiket:</span>
                    <span class="heading-font text-2xl font-black text-emerald-400">
                        Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Tombol Trigger Pop-up Modal Pembayaran -->
            <div class="space-y-4">
                <button type="button" onclick="openPaymentModal()" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black py-4 px-4 rounded-xl shadow-lg shadow-emerald-500/25 transition duration-200 flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Bayar Sekarang (Buka Pop-Up Midtrans)</span>
                </button>

                <p class="text-[11px] text-center text-slate-400">
                    Klik tombol di atas untuk memilih metode bank pembayaran.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- POP-UP MODAL PEMBAYARAN MIDTRANS SANDBOX -->
<div id="payment-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-[#0f172a] border border-slate-800 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300" id="modal-container">
        <!-- Header Pop-Up -->
        <div class="bg-gradient-to-r from-emerald-950 to-slate-900 p-6 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-lg font-black">
                    <i class="fa-solid fa-shield"></i>
                </div>
                <div>
                    <h3 class="heading-font text-base font-bold text-white leading-tight">Pilih Pembayaran Midtrans</h3>
                    <span class="text-[11px] font-mono text-emerald-400 font-semibold">Sandbox Simulation Mode</span>
                </div>
            </div>
            <button type="button" onclick="closePaymentModal()" class="w-8 h-8 rounded-lg bg-slate-800/80 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body Pop-Up -->
        <div class="p-6 space-y-5">
            <!-- Ringkasan Singkat -->
            <div class="p-4 bg-[#090d16] rounded-2xl border border-slate-800 flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 uppercase font-bold block">Workshop Dipilih</span>
                    <span class="text-xs font-bold text-white">{{ $registration->workshop->nama_workshop }}</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-slate-400 uppercase font-bold block">Total Bayar</span>
                    <span class="text-base font-black text-emerald-400">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Form Pilihan Bank / Metode Pembayaran Interaktif -->
            <form id="simulate-form" action="{{ route('payments.simulate-success', $payment->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2">Pilih Metode Pembayaran:</label>
                    <div class="space-y-2.5">
                        <!-- Pilihan 1: BCA VA -->
                        <div onclick="selectPaymentMethod(this)"
                             class="payment-option p-4 rounded-2xl bg-[#090d16] border-2 border-emerald-500 bg-emerald-500/10 cursor-pointer transition-all duration-200 flex items-center justify-between group">
                            <div class="flex items-center space-x-3.5">
                                <div class="radio-indicator w-5 h-5 rounded-full border-2 border-emerald-400 flex items-center justify-center bg-emerald-500 text-slate-950 text-[10px] font-black">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <input type="radio" name="payment_type" value="BCA Virtual Account (Sandbox)" checked class="hidden">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-white group-hover:text-emerald-400 transition">BCA Virtual Account</span>
                                        <span class="text-[9px] font-bold font-mono bg-emerald-400/20 text-emerald-300 px-1.5 py-0.5 rounded">Rekomendasi</span>
                                    </div>
                                    <span class="text-[11px] text-slate-400 font-mono block mt-0.5">No. VA: 8277 0812 3456 7890</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-building-columns text-emerald-400 text-lg"></i>
                        </div>

                        <!-- Pilihan 2: Mandiri VA -->
                        <div onclick="selectPaymentMethod(this)"
                             class="payment-option p-4 rounded-2xl bg-[#090d16] border-2 border-slate-800 hover:border-slate-700 cursor-pointer transition-all duration-200 flex items-center justify-between group">
                            <div class="flex items-center space-x-3.5">
                                <div class="radio-indicator w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center text-transparent text-[10px]">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <input type="radio" name="payment_type" value="Mandiri Bill Payment (Sandbox)" class="hidden">
                                <div>
                                    <span class="text-xs font-bold text-white group-hover:text-emerald-400 transition">Mandiri Bill Payment</span>
                                    <span class="text-[11px] text-slate-400 font-mono block mt-0.5">Kode Perusahaan: 70012</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-landmark text-slate-500 text-lg"></i>
                        </div>

                        <!-- Pilihan 3: QRIS Instant (GoPay / Dana / OVO) -->
                        <div onclick="selectPaymentMethod(this)"
                             class="payment-option p-4 rounded-2xl bg-[#090d16] border-2 border-slate-800 hover:border-slate-700 cursor-pointer transition-all duration-200 flex items-center justify-between group">
                            <div class="flex items-center space-x-3.5">
                                <div class="radio-indicator w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center text-transparent text-[10px]">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <input type="radio" name="payment_type" value="QRIS / GoPay (Sandbox)" class="hidden">
                                <div>
                                    <span class="text-xs font-bold text-white group-hover:text-emerald-400 transition">QRIS Instant (GoPay / Dana / OVO)</span>
                                    <span class="text-[11px] text-slate-400 block mt-0.5">Scan kode barcode QR secara instan</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-qrcode text-slate-500 text-lg"></i>
                        </div>

                        <!-- Pilihan 4: Kartu Kredit Test -->
                        <div onclick="selectPaymentMethod(this)"
                             class="payment-option p-4 rounded-2xl bg-[#090d16] border-2 border-slate-800 hover:border-slate-700 cursor-pointer transition-all duration-200 flex items-center justify-between group">
                            <div class="flex items-center space-x-3.5">
                                <div class="radio-indicator w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center text-transparent text-[10px]">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <input type="radio" name="payment_type" value="Credit Card Test (Sandbox)" class="hidden">
                                <div>
                                    <span class="text-xs font-bold text-white group-hover:text-emerald-400 transition">Kartu Kredit / Debit Test</span>
                                    <span class="text-[11px] text-slate-400 font-mono block mt-0.5">Visa / Mastercard 3DS Simulator</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-credit-card text-slate-500 text-lg"></i>
                        </div>
                    </div>
                </div>

                <!-- Tombol Konfirmasi Bayar Lunas di dalam Pop-up -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black py-4 px-4 rounded-xl shadow-lg shadow-emerald-500/25 transition flex items-center justify-center gap-2 text-xs">
                        <i class="fa-solid fa-circle-check"></i>
                        <span id="btn-pay-text">Bayar Sekarang via BCA Virtual Account</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Pop-up -->
        <div class="px-6 py-3.5 bg-[#090d16] border-t border-slate-800 flex items-center justify-between text-[11px] text-slate-500">
            <span><i class="fa-solid fa-shield-halved text-emerald-400 mr-1"></i> Sandbox Test Secure</span>
            <button type="button" onclick="closePaymentModal()" class="text-slate-400 hover:text-white font-semibold">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(!str_starts_with($snapToken, 'DEMO_SNAP_'))
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
@endif
<script type="text/javascript">
    function selectPaymentMethod(element) {
        // Reset semua pilihan
        document.querySelectorAll('.payment-option').forEach(function(el) {
            el.classList.remove('border-emerald-500', 'bg-emerald-500/10');
            el.classList.add('border-slate-800');

            var radio = el.querySelector('input[type="radio"]');
            if (radio) radio.checked = false;

            var indicator = el.querySelector('.radio-indicator');
            if (indicator) {
                indicator.classList.remove('border-emerald-400', 'bg-emerald-500', 'text-slate-950');
                indicator.classList.add('border-slate-600', 'text-transparent');
            }
        });

        // Aktifkan pilihan yang di-klik
        element.classList.remove('border-slate-800');
        element.classList.add('border-emerald-500', 'bg-emerald-500/10');

        var currentRadio = element.querySelector('input[type="radio"]');
        if (currentRadio) {
            currentRadio.checked = true;
            document.getElementById('btn-pay-text').innerText = 'Bayar Sekarang via ' + currentRadio.value.replace(' (Sandbox)', '');
        }

        var currentIndicator = element.querySelector('.radio-indicator');
        if (currentIndicator) {
            currentIndicator.classList.remove('border-slate-600', 'text-transparent');
            currentIndicator.classList.add('border-emerald-400', 'bg-emerald-500', 'text-slate-950');
        }
    }

    function openPaymentModal() {
        var modal = document.getElementById('payment-modal');
        var container = document.getElementById('modal-container');
        modal.classList.remove('hidden');
        setTimeout(function() {
            modal.classList.remove('opacity-0');
            container.classList.remove('scale-95');
            container.classList.add('scale-100');
        }, 10);
    }

    function closePaymentModal() {
        var modal = document.getElementById('payment-modal');
        var container = document.getElementById('modal-container');
        modal.classList.add('opacity-0');
        container.classList.remove('scale-100');
        container.classList.add('scale-95');
        setTimeout(function() {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endpush
