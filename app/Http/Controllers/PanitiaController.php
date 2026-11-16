<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Workshop;
use App\Models\User;
use Illuminate\Http\Request;

class PanitiaController extends Controller
{
    public function dashboard()
    {
        $totalWorkshop = Workshop::count();
        $totalPendaftar = Registration::count();
        $totalLunas = Registration::where('status', 'lunas')->count();
        $totalPendapatan = \App\Models\Payment::whereIn('status', ['success', 'settlement'])->sum('jumlah');

        $latestRegistrations = Registration::with(['user', 'workshop', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        return view('panitia.dashboard', compact(
            'totalWorkshop',
            'totalPendaftar',
            'totalLunas',
            'totalPendapatan',
            'latestRegistrations'
        ));
    }

    /**
     * Sesuai LKPD 05: "Pada daftar pendaftar, tampilkan nama peserta dan workshop.
     * Bandingkan jumlah query sebelum dan sesudah eager loading dengan data yang sama."
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
