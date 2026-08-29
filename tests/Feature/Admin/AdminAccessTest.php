<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_dashboard_and_resources(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/courses')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/dosen')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/mahasiswa')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/schedules')
            ->assertOk();
    }

    public function test_non_admin_gets_forbidden_for_admin_routes(): void
    {
        $dosen = User::factory()->create([
            'role' => 'dosen',
            'email' => 'dosen@example.com',
        ]);

        $this->actingAs($dosen)
            ->get('/admin/dashboard')
            ->assertForbidden();

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'mahasiswa@example.com',
        ]);

        $this->actingAs($mahasiswa)
            ->get('/admin/courses')
            ->assertForbidden();
    }
}
