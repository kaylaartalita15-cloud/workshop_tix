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

        $peserta3 = User::updateOrCreate(
            ['email' => 'siti@workshoptix.test'],
            [
                'name' => 'Siti Rahmawati',
                'role' => 'peserta',
                'no_telp' => '085233445566',
                'password' => Hash::make('password123'),
            ]
        );

        // 3. Workshop IT Beragam Tema Lengkap dengan Gambar HD
        $ws1 = Workshop::updateOrCreate(
            ['nama_workshop' => 'Mastering Laravel 12 & RESTful API Architecture'],
            [
                'kategori' => 'Backend Development',
                'deskripsi' => "Pelatihan mendalam membangun Web Application & RESTful API standar industri menggunakan Laravel 12.\n\nMateri Pembelajaran:\n- Architecture Pattern & Clean Code\n- Sanctum Token Authentication & RBAC Middleware\n- Database Eager Loading Optimization (Anti N+1)\n- Midtrans Payment Gateway Integration\n- Deployment ke Cloud VPS",
                'pemateri' => 'Kayla Artalita (Senior Backend Architect)',
                'tanggal' => now()->addDays(5)->toDateString(),
                'waktu' => '08:30:00',
                'lokasi' => 'Lab Komputer RPL 1 SMK Pesat',
                'harga' => 50000,
                'kuota' => 50,
                'gambar' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=900&auto=format&fit=crop&q=80',
                'status' => 'aktif',
            ]
        );

        $ws2 = Workshop::updateOrCreate(
            ['nama_workshop' => 'Modern UI/UX Design & Frontend dengan Tailwind CSS'],
            [
                'kategori' => 'UI/UX & Frontend',
                'deskripsi' => "Kupas tuntas implementasi antarmuka web modern, responsif, dan interaktif dengan Tailwind CSS dan Alpine.js.\n\nMateri:\n- Merancang Design System & Wireframing di Figma\n- Utility-First CSS Workflow dengan Tailwind\n- Micro-interactions & Mobile Responsive Layouts\n- Optimasi Asset & Accessibility (a11y)",
                'pemateri' => 'Ahmad Fauzi (Lead Product Designer)',
                'tanggal' => now()->addDays(10)->toDateString(),
                'waktu' => '09:00:00',
                'lokasi' => 'Auditorium Utama Lantai 3 SMK Pesat',
                'harga' => 35000,
                'kuota' => 40,
                'gambar' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?w=900&auto=format&fit=crop&q=80',
                'status' => 'aktif',
            ]
        );

        $ws3 = Workshop::updateOrCreate(
            ['nama_workshop' => 'DevOps Bootcamp: Docker Container & CI/CD Pipeline'],
            [
                'kategori' => 'DevOps & Cloud',
                'deskripsi' => "Praktik kontainerisasi aplikasi modern menggunakan Docker Engine, Docker Compose, serta setup automated testing & deployment via GitHub Actions ke cloud server.",
                'pemateri' => 'Rian Pratama (DevOps Specialist)',
                'tanggal' => now()->addDays(15)->toDateString(),
                'waktu' => '13:00:00',
                'lokasi' => 'Online via Zoom & Lab Jaringan',
                'harga' => 75000,
                'kuota' => 35,
                'gambar' => 'https://images.unsplash.com/photo-1607799279861-4dd421887fb3?w=900&auto=format&fit=crop&q=80',
                'status' => 'aktif',
            ]
        );

        $ws4 = Workshop::updateOrCreate(
            ['nama_workshop' => 'Cybersecurity Essentials: Ethical Hacking & Web Defense'],
            [
                'kategori' => 'Cybersecurity',
                'deskripsi' => "Mengenal kerentanan umum web aplikasi (OWASP Top 10), SQL Injection, XSS, serta teknik pengamanan vulnerability dan hardening server Linux.",
                'pemateri' => 'Dimas Wicaksono (Security Analyst)',
                'tanggal' => now()->addDays(20)->toDateString(),
                'waktu' => '10:00:00',
                'lokasi' => 'Lab Cyber SMK Pesat',
                'harga' => 60000,
                'kuota' => 25,
                'gambar' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=900&auto=format&fit=crop&q=80',
                'status' => 'aktif',
            ]
        );

        $ws5 = Workshop::updateOrCreate(
            ['nama_workshop' => 'AI Prompt Engineering & Generative AI for Developers'],
            [
                'kategori' => 'Artificial Intelligence',
                'deskripsi' => "Pelajari bagaimana memanfaatkan model AI generasi terbaru untuk mempercepat alur kerja coding, debugging, dan integrasi API AI langsung ke aplikasi Laravel.",
                'pemateri' => 'Nadia Safitri (AI Researcher)',
                'tanggal' => now()->addDays(25)->toDateString(),
                'waktu' => '13:30:00',
                'lokasi' => 'Ruang Multimedia SMK Pesat',
                'harga' => 45000,
                'kuota' => 30,
                'gambar' => 'https://images.unsplash.com/photo-1677442136019-21780efad99a?w=900&auto=format&fit=crop&q=80',
                'status' => 'aktif',
            ]
        );

        // 4. Seeding Data Registrasi & Tiket Nyata untuk dashboard admin yang kaya data
        // Peserta 1 mendaftar Workshop 1 (LUNAS)
        $reg1 = Registration::updateOrCreate(
            ['user_id' => $peserta1->id, 'workshop_id' => $ws1->id],
            ['kode_pendaftaran' => 'REG-LARAVEL-101', 'status' => 'lunas']
        );
        $pay1 = Payment::updateOrCreate(
            ['registration_id' => $reg1->id],
            [
                'metode_pembayaran' => 'BCA Virtual Account (Sandbox)',
                'order_id' => 'TRX-PAY-101',
                'snap_token' => 'DEMO_SNAP_101',
                'jumlah' => $ws1->harga,
                'status' => 'success',
                'paid_at' => now()->subHours(2),
            ]
        );
        Ticket::updateOrCreate(
            ['registration_id' => $reg1->id],
            [
                'kode_tiket' => 'TIX-PESAT-1001',
                'qr_code' => 'TIX-PESAT-1001',
                'status' => 'aktif',
            ]
        );

        // Peserta 2 mendaftar Workshop 2 (LUNAS & SUDAH CHECK-IN)
        $reg2 = Registration::updateOrCreate(
            ['user_id' => $peserta2->id, 'workshop_id' => $ws2->id],
            ['kode_pendaftaran' => 'REG-UIUX-202', 'status' => 'lunas']
        );
        $pay2 = Payment::updateOrCreate(
            ['registration_id' => $reg2->id],
            [
                'metode_pembayaran' => 'GoPay QRIS (Sandbox)',
                'order_id' => 'TRX-PAY-202',
                'snap_token' => 'DEMO_SNAP_202',
                'jumlah' => $ws2->harga,
                'status' => 'success',
                'paid_at' => now()->subDay(),
            ]
        );
        Ticket::updateOrCreate(
            ['registration_id' => $reg2->id],
            [
                'kode_tiket' => 'TIX-PESAT-2002',
                'qr_code' => 'TIX-PESAT-2002',
                'status' => 'digunakan',
            ]
        );

        // Peserta 3 mendaftar Workshop 3 (MENUNGGU PEMBAYARAN)
        $reg3 = Registration::updateOrCreate(
            ['user_id' => $peserta3->id, 'workshop_id' => $ws3->id],
            ['kode_pendaftaran' => 'REG-DEVOPS-303', 'status' => 'menunggu_pembayaran']
        );
        Payment::updateOrCreate(
            ['registration_id' => $reg3->id],
            [
                'metode_pembayaran' => 'midtrans',
                'order_id' => 'TRX-PAY-303',
                'snap_token' => 'DEMO_SNAP_303',
                'jumlah' => $ws3->harga,
                'status' => 'pending',
                'paid_at' => null,
            ]
        );
    }
}
