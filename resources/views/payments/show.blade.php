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
                    Klik tombol di atas untuk memunculkan pop-up jendela metode pembayaran resmi.
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
                    <h3 class="heading-font text-base font-bold text-white leading-tight">Payment Gateway Pop-Up</h3>
                    <span class="text-[11px] font-mono text-emerald-400 font-semibold">Midtrans Sandbox Integrated</span>
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
                    <span class="text-[10px] text-slate-400 uppercase font-bold block">Nominal</span>
                    <span class="text-base font-black text-emerald-400">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Pilihan Metode Pembayaran Sandbox -->
            <form id="simulate-form" action="{{ route('payments.simulate-success', $payment->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2">Pilih Kanal Pembayaran Sandbox:</label>
                    <div class="space-y-2">
                        <!-- Pilihan 1: Virtual Account -->
                        <label class="flex items-center justify-between p-3.5 rounded-xl bg-[#090d16] border border-slate-800 hover:border-emerald-500/50 cursor-pointer transition">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="payment_type" value="BCA Virtual Account (Sandbox)" checked class="text-emerald-500 focus:ring-0">
                                <div>
                                    <span class="text-xs font-bold text-white block">Bank BCA Virtual Account</span>
                                    <span class="text-[10px] text-slate-400 font-mono">VA: 8277 0812 3456 7890</span>
                                </div>
                            </div>
                            <span class="text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded font-mono">Otomatis</span>
                        </label>

                        <!-- Pilihan 2: QRIS / GoPay -->
                        <label class="flex items-center justify-between p-3.5 rounded-xl bg-[#090d16] border border-slate-800 hover:border-emerald-500/50 cursor-pointer transition">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="payment_type" value="QRIS / GoPay (Sandbox)" class="text-emerald-500 focus:ring-0">
                                <div>
                                    <span class="text-xs font-bold text-white block">QRIS Instant (GoPay / Dana / OVO)</span>
                                    <span class="text-[10px] text-slate-400">Pindai QR simulator via ponsel</span>
                                </div>
                            </div>
                            <span class="text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded font-mono">Instant</span>
                        </label>

                        <!-- Pilihan 3: Kartu Kredit Test -->
                        <label class="flex items-center justify-between p-3.5 rounded-xl bg-[#090d16] border border-slate-800 hover:border-emerald-500/50 cursor-pointer transition">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="payment_type" value="Credit Card Test (Sandbox)" class="text-emerald-500 focus:ring-0">
                                <div>
                                    <span class="text-xs font-bold text-white block">Kartu Kredit / Debit Online Test</span>
                                    <span class="text-[10px] text-slate-400 font-mono">4811-1111-1111-1114</span>
                                </div>
                            </div>
                            <span class="text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded font-mono">3DS Secure</span>
                        </label>
                    </div>
                </div>

                <!-- Tombol Konfirmasi Bayar Lunas di dalam Pop-up -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black py-3.5 px-4 rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center justify-center gap-2 text-xs">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Konfirmasi Pembayaran Lunas Sekarang</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Pop-up -->
        <div class="px-6 py-3 bg-[#090d16] border-t border-slate-800 flex items-center justify-between text-[10px] text-slate-500">
            <span>Enkripsi 256-Bit SSL Secured</span>
            <button type="button" onclick="closePaymentModal()" class="text-slate-400 hover:text-white">Batal</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(!str_starts_with($snapToken, 'DEMO_SNAP_'))
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
@endif
<script type="text/javascript">
    function openPaymentModal() {
        var snapToken = "{{ $snapToken }}";
        
        // Jika token Snap Midtrans tersedia dan bukan demo offline, coba buka Snap Popup asli terlebih dahulu
        if (typeof window.snap !== 'undefined' && snapToken && !snapToken.startsWith('DEMO_SNAP_')) {
            window.snap.pay(snapToken, {
                onSuccess: function(result){
                    document.getElementById('simulate-form').submit();
                },
                onPending: function(result){
                    alert("Menunggu pembayaran dari Anda.");
                    location.reload();
                },
                onError: function(result){
                    // Jika Snap gateway error, otomatis tampilkan modal popup internal
                    showInternalModal();
                },
                onClose: function(){
                    // User menutup Snap, tampilkan modal popup cadangan
                    showInternalModal();
                }
            });
        } else {
            // Langsung tampilkan modal pop-up interaktif
            showInternalModal();
        }
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
</script>
@endpush
