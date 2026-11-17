<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans resmi dari .env
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function show(Registration $registration)
    {
        // Otorisasi: Peserta hanya bisa bayar pendaftaran miliknya
        if (auth()->user()->role !== 'panitia' && $registration->user_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $payment = $registration->payment;
        if (!$payment) {
            return redirect()->route('registrations.index')->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // Cek status otomatis langsung ke server Midtrans jika status masih pending
        if ($payment->status === 'pending') {
            $this->checkMidtransStatusDirectly($payment);
        }

        // Jika sudah sukses/lunas langsung arahkan ke tiket
        if ($payment->status === 'success' || $payment->status === 'settlement') {
            return redirect()->route('tickets.show', $registration->ticket->id ?? $registration->id);
        }

        // Buat Snap Transaction resmi ke Midtrans jika belum ada
        if (!$payment->payment_url || !$payment->snap_token) {
            $user = $registration->user;
            $workshop = $registration->workshop;

            $params = [
                'transaction_details' => [
                    'order_id' => $payment->order_id,
                    'gross_amount' => (int) $payment->jumlah,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->no_telp ?? '081234567890',
                ],
                'item_details' => [
                    [
                        'id' => 'WS-' . $workshop->id,
                        'price' => (int) $payment->jumlah,
                        'quantity' => 1,
                        'name' => mb_substr($workshop->nama_workshop, 0, 45),
                    ]
                ],
            ];

            try {
                $tx = Snap::createTransaction($params);
                $payment->update([
                    'snap_token' => $tx->token,
                    'payment_url' => $tx->redirect_url,
                ]);
            } catch (\Exception $e) {
                // Jangan crash, log error
                report($e);
            }
        }

        return view('payments.show', compact('registration', 'payment'));
    }

    /**
     * Cek status pembayaran langsung ke server Midtrans (Verifikasi status LKPD 04)
     */
    public function checkStatus(Payment $payment)
    {
        $this->checkMidtransStatusDirectly($payment);

        if ($payment->status === 'success' || $payment->status === 'settlement') {
            return redirect()->route('tickets.show', $payment->registration->ticket->id)
                ->with('success', 'Pembayaran berhasil diverifikasi oleh Midtrans Sandbox! Tiket resmi telah aktif.');
        }

        return back()->with('warning', 'Status pembayaran saat ini masih: ' . strtoupper($payment->status) . '. Selesaikan pembayaran di simulator QRIS Midtrans.');
    }

    private function checkMidtransStatusDirectly(Payment $payment)
    {
        try {
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = false;

            $status = Transaction::status($payment->order_id);

            if ($status) {
                $statusArray = (array) $status;
                $txStatus = $statusArray['transaction_status'] ?? '';
                $fraud = $statusArray['fraud_status'] ?? '';
                $paymentType = $statusArray['payment_type'] ?? 'midtrans';

                if ($txStatus === 'settlement' || ($txStatus === 'capture' && $fraud === 'accept')) {
                    $this->markAsPaid($payment, $payment->registration, $paymentType);
                } elseif (in_array($txStatus, ['cancel', 'deny', 'expire'])) {
                    $payment->update(['status' => 'failed']);
                }
            }
        } catch (\Exception $e) {
            // Belum ada transaksi yang dibuat di simulator
        }
    }

    public function simulateSuccess(Request $request, Payment $payment)
    {
        $registration = $payment->registration;

        if (auth()->user()->role !== 'panitia' && $registration->user_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $this->markAsPaid($payment, $registration, $request->input('payment_type', 'QRIS Midtrans Sandbox'));

        return redirect()->route('tickets.show', $registration->ticket->id)
            ->with('success', 'Pembayaran berhasil diverifikasi! Tiket digital Anda telah aktif.');
    }

    public function handleCallback(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        if (!$notification || !isset($notification->order_id)) {
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status ?? '';
        $fraudStatus = $notification->fraud_status ?? '';

        $payment = Payment::where('order_id', $orderId)->first();
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $registration = $payment->registration;

        if ($transactionStatus == 'capture' && $fraudStatus == 'accept') {
            $this->markAsPaid($payment, $registration, $notification->payment_type ?? 'credit_card');
        } elseif ($transactionStatus == 'settlement') {
            $this->markAsPaid($payment, $registration, $notification->payment_type ?? 'midtrans');
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $payment->update(['status' => 'failed']);
            $registration->update(['status' => 'batal']);
        }

        return response()->json(['status' => 'success']);
    }

    private function markAsPaid(Payment $payment, Registration $registration, string $paymentType)
    {
        DB::transaction(function () use ($payment, $registration, $paymentType) {
            $payment->update([
                'status' => 'success',
                'metode_pembayaran' => $paymentType,
                'paid_at' => now(),
            ]);

            $registration->update(['status' => 'lunas']);

            if (!$registration->ticket) {
                $kodeTiket = 'TIX-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999);
                Ticket::create([
                    'registration_id' => $registration->id,
                    'kode_tiket' => $kodeTiket,
                    'qr_code' => $kodeTiket,
                    'status' => 'aktif',
                ]);
            }
        });
    }
}
