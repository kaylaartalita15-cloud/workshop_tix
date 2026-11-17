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
                    <span class="text-[10px] font-bold px-2.5 py-0.5 bg-emerald-500 text-slate-950 rounded uppercase font-mono">
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
            <!-- Rincian Pesanan -->
            <div class="bg-[#090d16] border border-slate-800 rounded-2xl p-5 space-y-4">
                <div class="flex justify-between items-center text-xs border-b border-slate-800 pb-3">
                    <span class="text-slate-400">Nama Peserta:</span>
                    <span class="font-bold text-white">{{ $registration->user->name }} ({{ $registration->user->email }})</span>
                </div>

                <div class="flex justify-between items-center text-xs border-b border-slate-800 pb-3">
                    <span class="text-slate-400">Jadwal & Lokasi:</span>
                    <span class="font-bold text-slate-200">{{ \Carbon\Carbon::parse($registration->workshop->tanggal)->translatedFormat('d M Y') }} • {{ $registration->workshop->lokasi }}</span>
                </div>

                <div class="flex justify-between items-center text-sm pt-2">
                    <span class="font-bold text-slate-300">Total Tagihan:</span>
                    <span class="heading-font text-2xl font-black text-emerald-400">
                        Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Tombol Utama Buka Midtrans Snap Popup -->
            <div class="space-y-4">
                <button type="button" id="pay-button" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black py-4 px-4 rounded-xl shadow-lg shadow-emerald-500/25 transition duration-200 flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Bayar Sekarang (Buka Midtrans Snap Pop-Up)</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- POP-UP MODAL PERSIS SEPERTI MIDTRANS SNAP ASLI DI GAMBAR CONTOH -->
<div id="payment-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white text-slate-800 rounded-2xl w-full max-w-[390px] overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300 flex flex-col font-sans" id="modal-container" style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);">

        <!-- Top Header Merchant (Persis: mentora / WorkshopTix) -->
        <div class="p-5 pb-3 bg-white flex items-center justify-between border-b border-slate-100">
            <div>
                <span class="text-base font-bold text-slate-800 tracking-tight">WorkshopTix</span>
            </div>
            <button type="button" onclick="closePaymentModal()" class="text-slate-400 hover:text-slate-600 text-lg transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Price & Order ID Section (Persis Gambar) -->
        <div class="px-5 py-4 bg-[#f8fafc] border-b border-slate-200/80">
            <div class="flex items-center justify-between">
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">
                        Rp{{ number_format($payment->jumlah, 0, ',', '.') }}
                    </span>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ (int)$payment->jumlah }}'); alert('Nominal disalin!');" class="text-slate-400 hover:text-slate-600 text-xs" title="Salin Jumlah">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                </div>
                <div class="text-right">
                    <span class="text-xs font-semibold text-blue-600 hover:underline cursor-pointer flex items-center gap-1">
                        <span>Details</span>
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between text-[11px] text-slate-500 mt-2 font-mono">
                <span class="truncate max-w-[260px]">Order ID #{{ $payment->order_id }}</span>
                <button type="button" onclick="navigator.clipboard.writeText('{{ $payment->order_id }}'); alert('Order ID disalin!');" class="text-slate-400 hover:text-slate-600 ml-2" title="Salin Order ID">
                    <i class="fa-regular fa-copy"></i>
                </button>
            </div>
        </div>

        <!-- Timer Bar: Choose within 23:59:55 (Persis Gambar) -->
        <div class="bg-[#f1f5f9] px-4 py-2 text-center text-xs text-slate-600 font-medium border-b border-slate-200 flex items-center justify-center gap-1.5">
            <i class="fa-regular fa-clock text-slate-500 text-[11px]"></i>
            <span>Choose within <strong class="font-mono font-bold text-slate-800" id="snap-timer">23:59:45</strong></span>
        </div>

        <!-- Scrollable Payment Methods List (Persis Struktur Midtrans Snap) -->
        <div class="divide-y divide-slate-100 max-h-[380px] overflow-y-auto" style="scrollbar-width: thin;">

            <!-- Section 1: Last payment method -->
            <div class="p-4 bg-white">
                <span class="inline-block text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded mb-3">
                    Last payment method
                </span>

                <div onclick="openSnapChannel('GoPay QRIS')" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 cursor-pointer transition border border-transparent hover:border-slate-200 group">
                    <div class="space-y-2">
                        <span class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition block">GoPay QRIS</span>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-[#00aed6] text-white text-[9px] font-black rounded">gopay</span>
                            <span class="px-2 py-0.5 bg-[#0081a0] text-white text-[9px] font-bold rounded">gopaylater</span>
                            <span class="px-1.5 py-0.5 bg-red-600 text-white text-[8px] font-black rounded tracking-wider">QRIS</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-400 text-xs"></i>
                </div>
            </div>

            <!-- Section 2: All payment methods (Persis Gambar) -->
            <div class="p-4 space-y-2 bg-white">
                <span class="text-xs font-semibold text-slate-400 block mb-2 uppercase tracking-wider">
                    All payment methods
                </span>

                <!-- 1. GoPay QRIS -->
                <div onclick="openSnapChannel('GoPay QRIS')" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 cursor-pointer transition border border-transparent hover:border-slate-200 group">
                    <div class="space-y-2">
                        <span class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition block">GoPay QRIS</span>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-[#00aed6] text-white text-[9px] font-black rounded">gopay</span>
                            <span class="px-2 py-0.5 bg-[#0081a0] text-white text-[9px] font-bold rounded">gopaylater</span>
                            <span class="px-1.5 py-0.5 bg-red-600 text-white text-[8px] font-black rounded tracking-wider">QRIS</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-400 text-xs"></i>
                </div>

                <!-- 2. Virtual account (BCA, Mandiri, BNI, BRI, Permata) -->
                <div onclick="toggleChannel('va-dropdown')" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 cursor-pointer transition border border-transparent hover:border-slate-200 group">
                    <div class="space-y-2">
                        <span class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition block">Virtual account</span>
                        <div class="flex items-center gap-2 flex-wrap text-[10px] font-bold text-slate-700">
                            <span class="px-1.5 py-0.5 bg-blue-100 text-blue-900 rounded font-black text-[9px]">BCA</span>
                            <span class="px-1.5 py-0.5 bg-amber-100 text-amber-900 rounded font-bold text-[9px]">mandiri</span>
                            <span class="px-1.5 py-0.5 bg-teal-100 text-teal-900 rounded font-bold text-[9px]">BNI</span>
                            <span class="px-1.5 py-0.5 bg-sky-100 text-sky-900 rounded font-bold text-[9px]">BRI</span>
                            <span class="text-slate-400 text-[10px]">+4</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform" id="va-arrow"></i>
                </div>

                <!-- Dropdown List Bank VA -->
                <div id="va-dropdown" class="hidden pl-4 pr-1 py-2 space-y-2 bg-slate-50 rounded-xl">
                    <div onclick="openSnapChannel('BCA Virtual Account')" class="flex items-center justify-between p-2 rounded-lg hover:bg-white cursor-pointer transition text-xs font-semibold text-slate-700">
                        <div class="flex items-center gap-2">
                            <span class="w-6 text-center font-black text-blue-800 text-[10px]">BCA</span>
                            <span>BCA Virtual Account</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-400 text-[10px]"></i>
                    </div>
                    <div onclick="openSnapChannel('Mandiri Bill Payment')" class="flex items-center justify-between p-2 rounded-lg hover:bg-white cursor-pointer transition text-xs font-semibold text-slate-700">
                        <div class="flex items-center gap-2">
                            <span class="w-6 text-center font-black text-amber-700 text-[10px]">MDR</span>
                            <span>Mandiri Bill Payment</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-400 text-[10px]"></i>
                    </div>
                    <div onclick="openSnapChannel('BNI Virtual Account')" class="flex items-center justify-between p-2 rounded-lg hover:bg-white cursor-pointer transition text-xs font-semibold text-slate-700">
                        <div class="flex items-center gap-2">
                            <span class="w-6 text-center font-black text-teal-700 text-[10px]">BNI</span>
                            <span>BNI Virtual Account</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-400 text-[10px]"></i>
                    </div>
                    <div onclick="openSnapChannel('BRI Virtual Account')" class="flex items-center justify-between p-2 rounded-lg hover:bg-white cursor-pointer transition text-xs font-semibold text-slate-700">
                        <div class="flex items-center gap-2">
                            <span class="w-6 text-center font-black text-sky-700 text-[10px]">BRI</span>
                            <span>BRI Virtual Account</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-400 text-[10px]"></i>
                    </div>
                </div>

                <!-- 3. Card Payment (Visa, Mastercard, JCB, Amex) -->
                <div onclick="openSnapChannel('Credit / Debit Card')" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 cursor-pointer transition border border-transparent hover:border-slate-200 group">
                    <div class="space-y-2">
                        <span class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition block">Card Payment</span>
                        <div class="flex items-center gap-2">
                            <span class="px-1.5 py-0.5 bg-blue-900 text-white text-[9px] font-black rounded">VISA</span>
                            <span class="px-1.5 py-0.5 bg-red-600 text-white text-[9px] font-bold rounded">Mastercard</span>
                            <span class="px-1.5 py-0.5 bg-green-700 text-white text-[9px] font-bold rounded">JCB</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-400 text-xs"></i>
                </div>

                <!-- 4. Google Pay -->
                <div onclick="openSnapChannel('Google Pay')" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 cursor-pointer transition border border-transparent hover:border-slate-200 group">
                    <div class="space-y-1.5">
                        <span class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition block">Google Pay</span>
                        <div class="flex items-center gap-1.5 text-xs text-slate-600 font-semibold">
                            <span class="px-2 py-0.5 bg-white border border-slate-300 rounded text-[10px] font-bold">G Pay</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-400 text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Detail Layar Bayar QRIS / Konfirmasi Pembayaran -->
        <div id="qris-sheet" class="hidden p-5 bg-white border-t border-slate-200 text-center space-y-4">
            <div class="flex items-center justify-between text-xs">
                <button type="button" onclick="closeSnapChannel()" class="text-blue-600 font-bold flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Pilih Metode Lain</span>
                </button>
                <span class="font-bold text-slate-800" id="channel-name-label">GoPay QRIS</span>
            </div>

            <!-- Tampilan Barcode QRIS Midtrans Asli -->
            <div class="inline-block p-4 bg-white border-2 border-slate-200 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between mb-2 pb-1 border-b border-slate-200">
                    <span class="text-[9px] font-black bg-red-600 text-white px-1.5 py-0.5 rounded">QRIS</span>
                    <span class="text-[9px] text-slate-400 font-mono">NMID: ID102026WORK01</span>
                </div>
                <img src="https://quickchart.io/qr?text=MIDTRANS-OFFICIAL-QRIS-ORDER-{{ $payment->order_id }}-TOTAL-{{ (int)$payment->jumlah }}&size=190&margin=1"
                     alt="Midtrans QRIS Snap"
                     class="w-44 h-44 mx-auto rounded-lg">
                <span class="block text-[10px] text-slate-500 font-bold mt-2 font-mono">MIDTRANS SANDBOX</span>
            </div>

            <p class="text-xs text-slate-600">
                Pindai kode QRIS di atas dengan aplikasi e-wallet Anda atau selesaikan simulasi pembayaran di bawah ini:
            </p>

            <form action="{{ route('payments.simulate-success', $payment->id) }}" method="POST">
                @csrf
                <input type="hidden" name="payment_type" id="hidden-payment-type" value="GoPay QRIS">
                <button type="submit" class="w-full bg-[#00aed6] hover:bg-[#0098bc] text-white font-black py-3 px-4 rounded-xl shadow transition duration-200 text-xs flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Simulasikan Bayar Lunas di Sandbox</span>
                </button>
            </form>
        </div>

        <!-- Footer Snap (Persis: Secured by Midtrans) -->
        <div class="px-5 py-3 bg-[#f8fafc] border-t border-slate-200 flex items-center justify-between text-[11px] text-slate-400">
            <span class="flex items-center gap-1">
                <i class="fa-solid fa-lock text-[10px]"></i>
                <span>Secured by <strong>midtrans</strong></span>
            </span>
            <span class="text-[10px] font-mono">TEST MODE</span>
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

        // Jika token Snap Midtrans asli dari server tersedia
        if (typeof window.snap !== 'undefined' && snapToken && !snapToken.startsWith('DEMO_SNAP_')) {
            window.snap.pay(snapToken, {
                onSuccess: function(result){
                    // Submit form verifikasi lunas
                    submitSuccessPayment('Midtrans Snap Gateway');
                },
                onPending: function(result){
                    alert("Pembayaran Anda sedang diproses oleh Midtrans.");
                    location.reload();
                },
                onError: function(result){
                    // Buka popup tampilan persis gambar
                    showInternalModal();
                },
                onClose: function(){
                    // Buka modal tampilan persis gambar
                    showInternalModal();
                }
            });
        } else {
            // Tampilkan popup dengan UI persis 100% seperti gambar contoh Midtrans Snap
            showInternalModal();
        }
    };

    function toggleChannel(id) {
        var el = document.getElementById(id);
        var arrow = document.getElementById('va-arrow');
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
            if (arrow) arrow.classList.add('rotate-180');
        } else {
            el.classList.add('hidden');
            if (arrow) arrow.classList.remove('rotate-180');
        }
    }

    function openSnapChannel(name) {
        document.getElementById('hidden-payment-type').value = name;
        document.getElementById('channel-name-label').innerText = name;
        document.getElementById('qris-sheet').classList.remove('hidden');
    }

    function closeSnapChannel() {
        document.getElementById('qris-sheet').classList.add('hidden');
    }

    function showInternalModal() {
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

    function submitSuccessPayment(method) {
        var form = document.querySelector('form[action*="simulate-success"]');
        if (form) {
            document.getElementById('hidden-payment-type').value = method;
            form.submit();
        }
    }
</script>
@endpush
