<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workshop;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkshopTixWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Uji 1: Peserta biasa tidak boleh mengakses dashboard panitia (403)
     */
    public function test_peserta_cannot_access_panitia_dashboard(): void
    {
        $peserta = User::where('role', 'peserta')->first();

        $response = $this->actingAs($peserta)->get('/panitia/dashboard');
        $response->assertStatus(403);
    }

    /**
     * Uji 2: Panitia bisa membuka dashboard panitia (200)
     */
    public function test_panitia_can_access_panitia_dashboard(): void
    {
        $panitia = User::where('role', 'panitia')->first();

        $response = $this->actingAs($panitia)->get('/panitia/dashboard');
        $response->assertStatus(200);
    }

    /**
     * Uji 3: Tolak pendaftaran ganda pada workshop yang sama (LKPD 03 & 06)
     */
    public function test_reject_duplicate_registration_for_same_workshop(): void
    {
        $peserta = User::where('email', 'peserta@workshoptix.test')->first();
        $workshop = Workshop::first();

        // Peserta sudah terdaftar dari database seeder
        $response = $this->actingAs($peserta)->post("/workshops/{$workshop->id}/register");
        
        $response->assertSessionHas('warning');
    }

    /**
     * Uji 4: Peserta tidak boleh membuka tiket milik user lain (LKPD 06)
     */
    public function test_user_cannot_view_other_user_ticket(): void
    {
        $userBudi = User::where('email', 'budi@workshoptix.test')->first();
        $ticketKayla = Ticket::first();

        $response = $this->actingAs($userBudi)->get("/tickets/{$ticketKayla->id}");
        $response->assertStatus(403);
    }
}
