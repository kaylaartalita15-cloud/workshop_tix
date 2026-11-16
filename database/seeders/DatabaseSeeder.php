<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Panitia (Admin)
        $panitia = User::updateOrCreate(
            ['email' => 'panitia@workshoptix.test'],
            [
                'name' => 'Panitia Workshop IT',
                'role' => 'panitia',
                'no_telp' => '081234567890',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Akun Peserta Demo
        $peserta1 = User::updateOrCreate(
            ['email' => 'peserta@workshoptix.test'],
            [
                'name' => 'Kayla Artalita',
                'role' => 'peserta',
                'no_telp' => '089876543210',
                'password' => Hash::make('password123'),
            ]
        );

        $peserta2 = User::updateOrCreate(
            ['email' => 'budi@workshoptix.test'],
            [
                'name' => 'Budi Santoso',
                'role' => 'peserta',
                'no_telp' => '081122334455',
                'password' => Hash::make('password123'),
            ]
        );

        // 3. Workshop IT
        $ws1 = Workshop::updateOrCreate(
            ['nama_workshop' => 'Mastering Laravel 12 & RESTful API Architecture'],
            [
                'deskripsi' => "Pelatihan mendalam membangun API standar industri menggunakan Laravel 12. Materi meliputi:\n- MVC & Repository Pattern\n- Middleware & Sanctum Authentication\n- Optimasi Query Database (Eager Loading & Anti N+1)\n- Integrasi Midtrans Payment Gateway",
                'pemateri' => 'Kayla Artalita (Fullstack Developer)',
                'tanggal' => now()->addDays(7)->toDateString(),
                'waktu' => '08:30:00',
                'lokasi' => 'Lab Komputer RPL 1 SMK Pesat',
                'harga' => 50000,
                'kuota' => 30,
                'status' => 'aktif',
            ]
        );

        $ws2 = Workshop::updateOrCreate(
            ['nama_workshop' => 'Modern Frontend Development dengan Tailwind CSS & Alpine.js'],
            [
                'deskripsi' => "Belajar merancang UI/UX interaktif yang responsif dan elegan dengan Tailwind CSS dan Alpine.js untuk web application.",
                'pemateri' => 'Ahmad Fauzi (UI/UX Designer)',
                'tanggal' => now()->addDays(14)->toDateString(),
                'waktu' => '09:00:00',
                'lokasi' => 'Auditorium SMK Pesat',
                'harga' => 35000,
                'kuota' => 40,
                'status' => 'aktif',
            ]
        );

        $ws3 = Workshop::updateOrCreate(
            ['nama_workshop' => 'DevOps Basics: Docker Container & CI/CD Pipeline'],
            [
                'deskripsi' => "Praktik kontainerisasi aplikasi Laravel dengan Docker dan otomatisasi deployment menggunakan GitHub Actions.",
                'pemateri' => 'Rian Pratama (DevOps Specialist)',
                'tanggal' => now()->addDays(21)->toDateString(),
                'waktu' => '13:00:00',
                'lokasi' => 'Online via Zoom Meeting',
                'harga' => 75000,
                'kuota' => 50,
                'status' => 'aktif',
            ]
        );

        // 4. Dummy Pendaftaran & Tiket Lunas untuk Peserta 1
        $existingReg = Registration::where('user_id', $peserta1->id)->where('workshop_id', $ws1->id)->first();
        if (!$existingReg) {
            $reg = Registration::create([
                'user_id' => $peserta1->id,
                'workshop_id' => $ws1->id,
                'kode_pendaftaran' => 'REG-DEMO-001',
                'status' => 'lunas',
            ]);

            Payment::create([
                'registration_id' => $reg->id,
                'metode_pembayaran' => 'BCA Virtual Account (Sandbox)',
                'order_id' => 'TRX-DEMO-' . time(),
                'snap_token' => 'DEMO_SNAP_' . time(),
                'jumlah' => $ws1->harga,
                'status' => 'success',
                'paid_at' => now(),
            ]);

            Ticket::create([
                'registration_id' => $reg->id,
                'kode_tiket' => 'TIX-PESAT-1001',
                'qr_code' => 'TIX-PESAT-1001',
                'status' => 'aktif',
            ]);
        }
    }
}
