@extends('layouts.app')

@section('title', 'Pembayaran Midtrans Real - WorkshopTix')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-[#0f172a] rounded-3xl border border-slate-800 overflow-hidden shadow-2xl">
        <!-- Banner Workshop -->
        <div class="relative h-48 w-full overflow-hidden bg-slate-900">
            <img src="{{ $registration->workshop->gambar_url }}" alt=""
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
                    <span class="text-slate-400">Jadwal Workshop:</span>
                    <span class="font-bold text-slate-200">{{ \Carbon\Carbon::parse($registration->workshop->tanggal)->translatedFormat('d M Y') }} • {{ $registration->workshop->lokasi }}</span>
                </div>

                <div class="flex justify-between items-center text-xs border-b border-slate-800 pb-3">
                    <span class="text-slate-400">Status Pembayaran:</span>
                    <span class="font-mono text-amber-400 font-bold bg-amber-500/10 px-2.5 py-1 rounded border border-amber-500/20 uppercase">
                        <i class="fa-solid fa-clock mr-1"></i> {{ $payment->status }}
                    </span>
                </div>

                <div class="flex justify-between items-center text-sm pt-2">
                    <span class="font-bold text-slate-300">Total Biaya:</span>
                    <span class="heading-font text-2xl font-black text-emerald-400">
                        Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Area Aksi Pembayaran Midtrans Asli -->
            <div class="space-y-4">
                <!-- Tombol 1: Buka Jendela Snap Asli Midtrans -->
                @if($payment->payment_url)
                    <a href="{{ $payment->payment_url }}" target="_blank"
                       class="w-full bg-[#00aed6] hover:bg-[#0098bc] text-white font-black py-4 px-4 rounded-xl shadow-lg transition duration-200 flex items-center justify-center gap-2 text-sm">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        <span>Buka Halaman Bayar Midtrans Real (Tab Baru)</span>
                    </a>
                @endif

                <button type="button" onclick="openSnapDirect()"
                        class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black py-4 px-4 rounded-xl shadow-lg shadow-emerald-500/25 transition duration-200 flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Bayar Sekarang (Midtrans Snap Pop-Up)</span>
                </button>

                <!-- Tombol 2: Cek Status / Simulasi Verifikasi Langsung -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <!-- Tombol Cek Status Real ke Server Midtrans -->
                    <form action="{{ route('payments.check-status', $payment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs rounded-xl border border-slate-700 transition flex items-center justify-center gap-1.5 shadow">
                            <i class="fa-solid fa-rotate text-emerald-400"></i>
                            <span>Cek Status Midtrans</span>
                        </button>
                    </form>

                    <!-- Tombol Cepat Lunas untuk Uji Sertifikasi Langsung -->
                    <form action="{{ route('payments.simulate-success', $payment->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_type" value="QRIS Midtrans Sandbox">
                        <button type="submit" class="w-full py-3 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 font-bold text-xs rounded-xl border border-emerald-500/30 transition flex items-center justify-center gap-1.5 shadow">
                            <i class="fa-solid fa-check-double text-emerald-400"></i>
                            <span>Verifikasi Lunas Instan</span>
                        </button>
                    </form>
                </div>

                <!-- Petunjuk Simulator QRIS Midtrans Resmi Sesuai Gambar Pengguna -->
                <div class="p-4 bg-slate-900 rounded-2xl border border-slate-800 text-xs text-slate-400 space-y-2">
                    <div class="flex items-center gap-2 text-slate-200 font-bold">
                        <i class="fa-solid fa-circle-info text-emerald-400"></i>
                        <span>Cara Uji Bayar di Simulator Midtrans Asli:</span>
                    </div>
                    <ol class="list-decimal list-inside space-y-1 text-[11px] text-slate-400">
                        <li>Klik tombol biru <strong>"Buka Halaman Bayar Midtrans Real"</strong> di atas.</li>
                        <li>Pilih metode pembayaran <strong>GoPay QRIS</strong> (akan tampil barcode QRIS asli Midtrans).</li>
                        <li>Klik kanan pada QR Code tersebut $\rightarrow$ pilih <strong>"Copy image address"</strong>.</li>
                        <li>Buka simulator resmi: <a href="https://simulator.sandbox.midtrans.com/v2/qris/index" target="_blank" class="text-emerald-400 underline font-mono">simulator.sandbox.midtrans.com/v2/qris/index</a>.</li>
                        <li>Paste link QR pada kolom <em>QR Code Image Url</em> lalu klik <strong>Scan QR</strong> $\rightarrow$ <strong>Bayar</strong>.</li>
                        <li>Kembali ke sini dan klik tombol <strong>"Cek Status Midtrans"</strong> untuk menerbitkan tiket!</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-W2Q1Z3rP_w-xYzAb"></script>
<script type="text/javascript">
    function openSnapDirect() {
        var snapToken = "{{ $payment->snap_token }}";
        if (typeof window.snap !== 'undefined' && snapToken) {
            window.snap.pay(snapToken, {
                onSuccess: function(result){
                    window.location.reload();
                },
                onPending: function(result){
                    alert("Menunggu pembayaran diselesaikan.");
                    window.location.reload();
                },
                onError: function(result){
                    alert("Gagal memuat snap.");
                },
                onClose: function(){
                    // user close
                }
            });
        } else {
            @if($payment->payment_url)
                window.open("{{ $payment->payment_url }}", "_blank");
            @endif
        }
    }
</script>
@endpush
