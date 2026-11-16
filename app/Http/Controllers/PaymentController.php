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

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
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

        // Jika sudah sukses/lunas langsung arahkan ke tiket
        if ($payment->status === 'success' || $payment->status === 'settlement') {
            return redirect()->route('tickets.show', $registration->ticket->id ?? $registration->id);
        }

        $snapToken = $payment->snap_token;

        // Buat snap token jika belum ada atau kadaluarsa
        if (!$snapToken) {
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
                        'name' => mb_substr($workshop->nama_workshop, 0, 40),
                    ]
                ],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                $payment->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                // Fallback token simulator jika server key sandbox dummy / offline
                $snapToken = 'DEMO_SNAP_' . md5($payment->order_id);
                $payment->update(['snap_token' => $snapToken]);
            }
        }

        $clientKey = config('midtrans.client_key');

        return view('payments.show', compact('registration', 'payment', 'snapToken', 'clientKey'));
    }

    /**
     * Endpoint Simulasi Bayar (Sesuai LKPD Point 04: "Setelah simulasi bayar, verifikasi status melalui backend")
     */
    public function simulateSuccess(Request $request, Payment $payment)
    {
        $registration = $payment->registration;

        if (auth()->user()->role !== 'panitia' && $registration->user_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        DB::beginTransaction();
        try {
            $payment->update([
                'status' => 'success',
                'metode_pembayaran' => $request->input('payment_type', 'bank_transfer_simulator'),
                'paid_at' => now(),
            ]);

            $registration->update([
                'status' => 'lunas',
            ]);

            // Terbitkan Tiket Digital
            if (!$registration->ticket) {
                $kodeTiket = 'TIX-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999);
                Ticket::create([
                    'registration_id' => $registration->id,
                    'kode_tiket' => $kodeTiket,
                    'qr_code' => $kodeTiket,
                    'status' => 'aktif',
                ]);
            }

            DB::commit();

            return redirect()->route('tickets.show', $registration->ticket->id)
                ->with('success', 'Pembayaran berhasil diverifikasi! Tiket digital Anda telah aktif.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Webhook/Notification Handler dari Midtrans
     */
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

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $this->markAsPaid($payment, $registration, $notification->payment_type ?? 'credit_card');
            }
        } elseif ($transactionStatus == 'settlement') {
            $this->markAsPaid($payment, $registration, $notification->payment_type ?? 'midtrans');
        } elseif ($transactionStatus == 'pending') {
            $payment->update(['status' => 'pending']);
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
