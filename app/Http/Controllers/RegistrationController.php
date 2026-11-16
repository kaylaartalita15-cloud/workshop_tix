<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = auth()->user()->registrations()
            ->with(['workshop', 'payment', 'ticket'])
            ->latest()
            ->get();

        return view('registrations.index', compact('registrations'));
    }

    public function store(Request $request, Workshop $workshop)
    {
        $user = auth()->user();

        // Validasi Status Workshop & Kuota
        if ($workshop->status !== 'aktif') {
            return back()->with('error', 'Pendaftaran untuk workshop ini sedang ditutup.');
        }

        $terdaftarCount = $workshop->registrations()->count();
        if ($terdaftarCount >= $workshop->kuota) {
            return back()->with('error', 'Mohon maaf, kuota peserta workshop ini sudah penuh.');
        }

        // Validasi Cegah Pendaftaran Ganda (Aturan LKPD: Tolak pendaftaran ganda)
        $existing = Registration::where('user_id', $user->id)
            ->where('workshop_id', $workshop->id)
            ->first();

        if ($existing) {
            return redirect()->route('registrations.show', $existing->id)
                ->with('warning', 'Anda sudah pernah mendaftar pada workshop ini sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Buat Pendaftaran
            $registration = Registration::create([
                'user_id' => $user->id,
                'workshop_id' => $workshop->id,
                'kode_pendaftaran' => 'REG-' . strtoupper(Str::random(6)) . '-' . time(),
                'status' => 'menunggu_pembayaran',
            ]);

            // Buat Payment Record
            $orderId = 'TRX-' . strtoupper(Str::random(5)) . '-' . $registration->id . '-' . time();
            Payment::create([
                'registration_id' => $registration->id,
                'order_id' => $orderId,
                'jumlah' => $workshop->harga,
                'status' => 'pending',
                'metode_pembayaran' => 'midtrans',
            ]);

            DB::commit();

            return redirect()->route('payments.show', $registration->id)
                ->with('success', 'Pendaftaran berhasil dibuat. Silakan selesaikan pembayaran tiket Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pendaftaran: ' . $e->getMessage());
        }
    }

    public function show(Registration $registration)
    {
        // Otorisasi: Peserta hanya bisa melihat pendaftarannya sendiri
        if (auth()->user()->role !== 'panitia' && $registration->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat pendaftaran ini.');
        }

        $registration->load(['workshop', 'payment', 'ticket', 'user']);

        return view('registrations.show', compact('registration'));
    }
}
