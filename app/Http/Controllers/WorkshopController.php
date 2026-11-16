<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    public function index()
    {
        $workshops = Workshop::where('status', 'aktif')->latest()->get();
        return view('workshops.index', compact('workshops'));
    }

    public function show(Workshop $workshop)
    {
        $sudahTerdaftar = false;
        $registration = null;

        if (auth()->check()) {
            $registration = auth()->user()->registrations()
                ->where('workshop_id', $workshop->id)
                ->with(['payment', 'ticket'])
                ->first();

            $sudahTerdaftar = (bool) $registration;
        }

        $sisaKuota = $workshop->kuota - $workshop->registrations()->count();

        return view('workshops.show', compact('workshop', 'sudahTerdaftar', 'registration', 'sisaKuota'));
    }

    // Manajemen Workshop oleh Panitia
    public function adminIndex()
    {
        $workshops = Workshop::withCount('registrations')->latest()->paginate(10);
        return view('panitia.workshops.index', compact('workshops'));
    }

    public function create()
    {
        return view('panitia.workshops.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_workshop' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'pemateri' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'lokasi' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:1',
            'status' => 'required|in:aktif,tutup',
        ]);

        Workshop::create($validated);

        return redirect()->route('panitia.workshops.index')->with('success', 'Workshop berhasil ditambahkan!');
    }

    public function edit(Workshop $workshop)
    {
        return view('panitia.workshops.edit', compact('workshop'));
    }

    public function update(Request $request, Workshop $workshop)
    {
        $validated = $request->validate([
            'nama_workshop' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'pemateri' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'lokasi' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:1',
            'status' => 'required|in:aktif,tutup',
        ]);

        $workshop->update($validated);

        return redirect()->route('panitia.workshops.index')->with('success', 'Workshop berhasil diperbarui!');
    }

    public function destroy(Workshop $workshop)
    {
        $workshop->delete();
        return redirect()->route('panitia.workshops.index')->with('success', 'Workshop berhasil dihapus!');
    }
}
