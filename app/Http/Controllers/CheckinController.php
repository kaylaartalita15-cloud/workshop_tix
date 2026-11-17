<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function index()
    {
        $recentCheckins = Ticket::with(['registration.user', 'registration.workshop'])
            ->where('status', 'digunakan')
            ->latest('updated_at')
            ->take(10)
            ->get();

        return view('panitia.checkin.index', compact('recentCheckins'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'kode_tiket' => 'required|string',
        ]);

        $kodeTiket = trim($request->input('kode_tiket'));

        $ticket = Ticket::with(['registration.user', 'registration.workshop', 'registration.payment'])
            ->where('kode_tiket', $kodeTiket)
            ->first();

        if (!$ticket) {
            return back()->with('error', "Tiket dengan kode '{$kodeTiket}' TIDAK DITEMUKAN dalam sistem!");
        }

        // Cek apakah status pembayaran lunas
        if ($ticket->registration->payment->status !== 'success' && $ticket->registration->payment->status !== 'settlement') {
            return back()->with('error', "PERINGATAN: Tiket ini belum lunas! Status: {$ticket->registration->payment->status}");
        }

        // Cek apakah tiket sudah pernah digunakan
        if ($ticket->status === 'digunakan') {
            return back()->with('warning', "TIKET INI SUDAH PERNAH DIGUNAKAN (CHECK-IN) sebelumnya pada: {$ticket->updated_at->format('d/m/Y H:i:s')} WIB untuk peserta: {$ticket->registration->user->name}");
        }

        // Validasi lolos, tandai tiket sebagai digunakan
        $ticket->update([
            'status' => 'digunakan',
        ]);

        return back()->with('success', "CHECK-IN BERHASIL! Tiket valid atas nama {$ticket->registration->user->name} untuk workshop: {$ticket->registration->workshop->nama_workshop}");
    }
}
