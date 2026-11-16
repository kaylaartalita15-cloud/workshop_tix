<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function show(Ticket $ticket)
    {
        $ticket->load(['registration.workshop', 'registration.user', 'registration.payment']);

        $user = auth()->user();

        // Otorisasi Sesuai LKPD:
        // "peserta hanya bisa membuka pendaftaran dan tiket miliknya."
        // "Uji juga pendaftaran ganda, akses tiket milik akun lain, dan transaksi belum lunas."
        if (!$user->isPanitia() && $ticket->registration->user_id !== $user->id) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk melihat tiket ini.');
        }

        // Pastikan tiket hanya bisa dilihat jika pembayaran lunas
        if ($ticket->registration->payment->status !== 'success' && $ticket->registration->payment->status !== 'settlement') {
            return redirect()->route('payments.show', $ticket->registration_id)
                ->with('error', 'Tiket belum dapat diakses karena transaksi belum lunas.');
        }

        return view('tickets.show', compact('ticket'));
    }
}
