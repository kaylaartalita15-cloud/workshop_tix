<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\WorkshopController;
use Illuminate\Support\Facades\Route;

// Halaman Beranda / Katalog Workshop
Route::get('/', [WorkshopController::class, 'index'])->name('workshops.index');
Route::get('/workshops/{workshop}', [WorkshopController::class, 'show'])->name('workshops.show');

// Auth Routes (Tamu / Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Midtrans Notification Callback Webhook
Route::post('/midtrans/callback', [PaymentController::class, 'handleCallback'])->name('midtrans.callback');

// Area User Terautentikasi (Peserta & Panitia)
Route::middleware('auth')->group(function () {
    // Alur Pendaftaran & Tiket Peserta
    Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::post('/workshops/{workshop}/register', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('/registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');

    // Pembayaran & Simulasi
    Route::get('/payments/{registration}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/simulate-success', [PaymentController::class, 'simulateSuccess'])->name('payments.simulate-success');

    // Tiket Digital
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
});

// Area Khusus Panitia (Middleware: panitia)
Route::middleware(['auth', 'panitia'])->prefix('panitia')->name('panitia.')->group(function () {
    Route::get('/dashboard', [PanitiaController::class, 'dashboard'])->name('dashboard');
    Route::get('/pendaftar', [PanitiaController::class, 'pendaftar'])->name('pendaftar');

    // CRUD Workshop Panitia
    Route::get('/workshops', [WorkshopController::class, 'adminIndex'])->name('workshops.index');
    Route::get('/workshops/create', [WorkshopController::class, 'create'])->name('workshops.create');
    Route::post('/workshops', [WorkshopController::class, 'store'])->name('workshops.store');
    Route::get('/workshops/{workshop}/edit', [WorkshopController::class, 'edit'])->name('workshops.edit');
    Route::put('/workshops/{workshop}', [WorkshopController::class, 'update'])->name('workshops.update');
    Route::delete('/workshops/{workshop}', [WorkshopController::class, 'destroy'])->name('workshops.destroy');
});
