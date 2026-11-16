# WORKSHOPTIX - Pendaftaran dan Tiket Digital Workshop IT

> **LKPD - XI RPL • DRILL PRA SERTIFIKASI KEJURUAN REKAYASA PERANGKAT LUNAK (SMK PESAT)**

Aplikasi berbasis Laravel untuk manajemen workshop IT, pendaftaran peserta online, pembayaran otomatis menggunakan **Midtrans Sandbox**, penerbitan tiket digital ber-QR Code, serta dashboard panitia dengan pencegahan **N+1 Query Problem** (Eager Loading).

---

## 📌 Anggota Kelompok & Pembagian Tugas (Backlog)

- **Maintainer / Backend Lead**: Kayla Artalita
  - *Tugas*: Menyiapkan repositori, arsitektur MVC, skema database (`users`, `workshops`, `registrations`, `payments`, `tickets`), otorisasi middleware multi-role, dan integrasi Midtrans Sandbox.
- **Frontend / UI Designer**:
  - *Tugas*: Merancang Blade layout responsif dengan Tailwind CSS, halaman katalog workshop, status pembayaran, tiket digital ber-QR Code, dan dashboard panitia.
- **QA & Database Engineer**:
  - *Tugas*: Pengujian alur registrasi, validasi tolak pendaftaran ganda, perlindungan tiket antar-user, serta analisis perbandingan query N+1 vs Eager Loading.

---

## 🛠️ Fitur Utama Sesuai LKPD

1. **Autentikasi & Multi-role Otorisasi (03)**:
   - **Panitia**: Hak akses penuh CRUD Workshop IT, monitoring pendaftar, dan rekap pemasukan tiket.
   - **Peserta**: Memilih workshop, checkout pembayaran, dan hanya bisa membuka pendaftaran serta tiket miliknya sendiri.
2. **Validasi & Tolak Pendaftaran Ganda (03 & 06)**:
   - Sistem mendeteksi jika peserta sudah terdaftar di workshop yang sama dan menolak pendaftaran dobel.
3. **Pembayaran Midtrans Sandbox (04)**:
   - Pembuatan pesanan dengan nominal server-side.
   - Snap Popup & simulator verifikasi backend untuk penerbitan tiket otomatis setelah status lunas.
4. **Tiket Digital Ber-QR Code (04)**:
   - Kode tiket unik, nama peserta, workshop, waktu, jadwal, dan QR Code untuk check-in.
   - Tiket hanya terbit dan bisa dibuka jika status pembayaran telah **lunas / success**.
5. **Optimasi Anti N+1 Problem (05)**:
   - Menggunakan Eager Loading: `Registration::with(['user', 'workshop', 'payment', 'ticket'])`.
   - Tersedia tombol perbandingan langsung di halaman pendaftar panitia.

---

## 🚀 Panduan Menjalankan Aplikasi

### 1. Prasyarat
- PHP >= 8.2 (direkomendasikan PHP 8.2+)
- Composer
- MySQL / MariaDB (Laragon / XAMPP)

### 2. Langkah Instalasi

1. Clone repositori & masuk ke folder:
   ```bash
   git clone <URL_REPO>
   cd Workshop-Tix
   ```

2. Install dependensi composer:
   ```bash
   composer install
   ```

3. Konfigurasi file `.env`:
   Salin file `.env.example` menjadi `.env` lalu sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=Workshop_Tix
   DB_USERNAME=root
   DB_PASSWORD=

   # Midtrans Sandbox Credentials
   MIDTRANS_SERVER_KEY=SB-Mid-server-DemoKeyDummy123456789
   MIDTRANS_CLIENT_KEY=SB-Mid-client-DemoKeyDummy123456789
   MIDTRANS_IS_PRODUCTION=false
   ```

4. Jalankan Migrasi & Database Seeder:
   ```bash
   php artisan migrate --seed
   ```

5. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
   Akses melalui peramban di: `http://127.0.0.1:8000`

---

## 👤 Akun Pengujian Demo (Default Seeder)

| Role | Email | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Panitia** | `panitia@workshoptix.test` | `password123` | Akses Dashboard & CRUD Workshop |
| **Peserta 1** | `peserta@workshoptix.test` | `password123` | Sudah memiliki 1 tiket lunas ber-QR |
| **Peserta 2** | `budi@workshoptix.test` | `password123` | Akun baru untuk uji checkout & tolak akses |

---

## 📊 Hasil Uji N+1 Query Problem (LKPD Point 05)

Pengujian dilakukan pada halaman data seluruh pendaftar (`/panitia/pendaftar`):

### 1. Tanpa Eager Loading (Lazy Loading - Masalah N+1)
- Query: `Registration::all()`
- Pola Eksekusi:
  - 1 Query untuk mengambil semua registrasi.
  - Untuk setiap baris data, dieksekusi query relasi terpisah:
    - 1 Query `SELECT * FROM users WHERE id = ?`
    - 1 Query `SELECT * FROM workshops WHERE id = ?`
    - 1 Query `SELECT * FROM payments WHERE registration_id = ?`
    - 1 Query `SELECT * FROM tickets WHERE registration_id = ?`
- **Total Query**: $1 + (4 \times N)$ query. Jika ada 50 peserta, akan menghasilkan **201 query database**.

### 2. Dengan Eager Loading (Solusi Diimplementasikan)
- Query: `Registration::with(['user', 'workshop', 'payment', 'ticket'])->get()`
- Pola Eksekusi:
  - 1 Query `SELECT * FROM registrations`
  - 1 Query `SELECT * FROM users WHERE id IN (...)`
  - 1 Query `SELECT * FROM workshops WHERE id IN (...)`
  - 1 Query `SELECT * FROM payments WHERE registration_id IN (...)`
  - 1 Query `SELECT * FROM tickets WHERE registration_id IN (...)`
- **Total Query**: Tetap konstan **hanya 5 query** meskipun data pendaftar bertambah hingga ribuan baris.

---

## 🧪 Bukti Pengujian Otomatis (Feature Tests)

Seluruh skenario pengujian utama pada LKPD 06 telah diuji dan lulus 100%:
```bash
php artisan test --filter=WorkshopTixWorkflowTest
```
- `test_peserta_cannot_access_panitia_dashboard`: **PASSED** (Mencegah peserta mengakses halaman admin)
- `test_panitia_can_access_panitia_dashboard`: **PASSED** (Panitia berhasil membuka dashboard)
- `test_reject_duplicate_registration_for_same_workshop`: **PASSED** (Tolak pendaftaran ganda)
- `test_user_cannot_view_other_user_ticket`: **PASSED** (Mencegah user mengintip tiket akun lain)
