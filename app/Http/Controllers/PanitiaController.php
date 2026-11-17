<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanitiaController extends Controller
{
    public function dashboard()
    {
        // 1. Metrik Utama
        $totalWorkshop = Workshop::count();
        $totalPendaftar = Registration::count();
        $totalLunas = Registration::where('status', 'lunas')->count();
        $totalPending = Registration::where('status', 'menunggu_pembayaran')->count();
        $totalCheckin = Ticket::where('status', 'digunakan')->count();
        $totalPendapatan = Payment::whereIn('status', ['success', 'settlement'])->sum('jumlah');

        // 2. Data Workshop dengan Jumlah Pendaftar dan Sisa Kuota
        $workshopsSummary = Workshop::withCount([
            'registrations as total_terdaftar',
            'registrations as total_lunas' => function ($query) {
                $query->where('status', 'lunas');
            }
        ])->get()->map(function ($ws) {
            $ws->sisa_kuota = max(0, $ws->kuota - $ws->total_terdaftar);
            $ws->persentase_terisi = $ws->kuota > 0 ? min(100, round(($ws->total_terdaftar / $ws->kuota) * 100)) : 0;
            return $ws;
        });

        // 3. Pendaftar Terbaru
        $latestRegistrations = Registration::with(['user', 'workshop', 'payment', 'ticket'])
            ->latest()
            ->take(8)
            ->get();

        // 4. Data Check-in Terbaru
        $recentCheckins = Ticket::with(['registration.user', 'registration.workshop'])
            ->where('status', 'digunakan')
            ->latest('updated_at')
            ->take(5)
            ->get();

        // 5. Total Peserta Terdaftar di Sistem
        $totalUserPeserta = User::where('role', 'peserta')->count();

        return view('panitia.dashboard', compact(
            'totalWorkshop',
            'totalPendaftar',
            'totalLunas',
            'totalPending',
            'totalCheckin',
            'totalPendapatan',
            'totalUserPeserta',
            'workshopsSummary',
            'latestRegistrations',
            'recentCheckins'
        ));
    }

    /**
     * Sesuai LKPD 05: Daftar Pendaftar dengan Uji N+1 Query Problem
     */
    public function pendaftar(Request $request)
    {
        $mode = $request->query('eager_loading', '1');

        if ($mode === '0') {
            // Lazy loading (Menghasilkan N+1 Query Problem untuk pengujian LKPD 05)
            $pendaftar = Registration::latest()->paginate(15);
        } else {
            // Eager Loading (Solusi Anti N+1 Problem yang dianjurkan)
            $pendaftar = Registration::with(['user', 'workshop', 'payment', 'ticket'])
                ->latest()
                ->paginate(15);
        }

        return view('panitia.pendaftar', compact('pendaftar', 'mode'));
    }
}
