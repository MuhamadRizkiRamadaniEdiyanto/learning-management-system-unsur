<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthenticationAndMahasiswaSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_register_pages_are_accessible(): void
    {
        $this->get('/login')->assertStatus(200);
        $this->get('/register')->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'login.feature@example.com',
            'password' => 'password123',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])
            ->assertRedirect(route('mahasiswa.dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
    }

    public function test_enrolled_mahasiswa_can_submit_and_non_enrolled_mahasiswa_is_forbidden(): void
    {
        Storage::fake('public');
        $dosen = User::factory()->create(['role' => 'dosen']);
        $enrolled = User::factory()->create(['role' => 'mahasiswa']);
        $notEnrolled = User::factory()->create(['role' => 'mahasiswa']);
        $course = Course::create([
            'kode_matkul' => 'FEAT101',
            'nama' => 'Feature Testing',
            'dosen_id' => $dosen->id,
        ]);
        $course->mahasiswa()->attach($enrolled);
        $assignment = $course->assignments()->create([
            'judul' => 'Tugas Feature Test',
            'tenggat_waktu' => now()->addDay(),
        ]);

        $this->actingAs($enrolled)
            ->postJson("/assignments/{$assignment->id}/submissions", [
                'file_jawaban' => UploadedFile::fake()->create('jawaban.pdf', 100, 'application/pdf'),
            ])
            ->assertCreated();

        $this->actingAs($notEnrolled)
            ->postJson("/assignments/{$assignment->id}/submissions", [
                'file_jawaban' => UploadedFile::fake()->create('jawaban.pdf', 100, 'application/pdf'),
            ])
            ->assertForbidden();
    }

    public function test_student_cannot_access_admin_or_dosen_dashboard(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($mahasiswa)->get('/admin/dashboard')->assertStatus(403);
        $this->actingAs($mahasiswa)->get('/dosen/dashboard')->assertStatus(403);
    }
}
