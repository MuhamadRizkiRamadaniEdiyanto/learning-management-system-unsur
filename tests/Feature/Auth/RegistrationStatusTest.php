<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_mahasiswa_and_dosen_can_register_via_public_form(): void
    {
        $response = $this->post('/register', [
            'name' => 'Admin Khayalan',
            'email' => 'admin.fake@example.com',
            'nomor_induk' => '1234567890',
            'role' => 'admin',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertSessionHasErrors(['role']);
    }

    public function test_pending_user_cannot_log_in(): void
    {
        $user = User::factory()->create([
            'email' => 'pending@example.com',
            'role' => 'mahasiswa',
            'status_akun' => 'pending',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}
