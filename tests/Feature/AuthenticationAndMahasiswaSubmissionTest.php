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

    public function test_submission_create_and_update_are_rejected_after_deadline(): void
    {
        Storage::fake('public');
        $dosen = User::factory()->create(['role' => 'dosen']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $course = Course::create([
            'kode_matkul' => 'FEAT102',
            'nama' => 'Deadline Testing',
            'dosen_id' => $dosen->id,
        ]);
        $course->mahasiswa()->attach($mahasiswa);
        $assignment = $course->assignments()->create([
            'judul' => 'Tugas Ditutup',
            'tenggat_waktu' => now()->subMinute(),
        ]);

        $this->actingAs($mahasiswa)
            ->postJson("/assignments/{$assignment->id}/submissions", [
                'file_jawaban' => UploadedFile::fake()->create('jawaban.pdf', 100, 'application/pdf'),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file_jawaban']);

        $submission = $assignment->submissions()->create([
            'user_id' => $mahasiswa->id,
            'file_jawaban' => 'assignments/lama.pdf',
        ]);

        $this->actingAs($mahasiswa)
            ->putJson("/assignments/{$assignment->id}/submissions/{$submission->id}", [
                'file_jawaban' => UploadedFile::fake()->create('jawaban-baru.pdf', 100, 'application/pdf'),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file_jawaban']);
    }

    public function test_mahasiswa_cannot_submit_the_same_assignment_twice(): void
    {
        Storage::fake('public');
        $dosen = User::factory()->create(['role' => 'dosen']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $course = Course::create([
            'kode_matkul' => 'FEAT103',
            'nama' => 'Duplicate Testing',
            'dosen_id' => $dosen->id,
        ]);
        $course->mahasiswa()->attach($mahasiswa);
        $assignment = $course->assignments()->create([
            'judul' => 'Tugas Tunggal',
            'tenggat_waktu' => now()->addDay(),
        ]);

        $this->actingAs($mahasiswa)
            ->postJson("/assignments/{$assignment->id}/submissions", [
                'file_jawaban' => UploadedFile::fake()->create('pertama.pdf', 100, 'application/pdf'),
            ])
            ->assertCreated();

        $this->actingAs($mahasiswa)
            ->postJson("/assignments/{$assignment->id}/submissions", [
                'file_jawaban' => UploadedFile::fake()->create('kedua.pdf', 100, 'application/pdf'),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file_jawaban']);

        $this->assertDatabaseCount('submissions', 1);
    }

    public function test_student_cannot_access_admin_or_dosen_dashboard(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($mahasiswa)->get('/admin/dashboard')->assertStatus(403);
        $this->actingAs($mahasiswa)->get('/dosen/dashboard')->assertStatus(403);
    }
}
