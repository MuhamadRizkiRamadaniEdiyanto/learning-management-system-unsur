<?php

namespace Tests\Feature\Authorization;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class LmsRoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_enrolled_mahasiswa_can_submit(): void
    {
        [$dosen, $mahasiswa, $course, $assignment] = $this->courseFixture();
        $notEnrolled = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($notEnrolled)
            ->postJson("/assignments/{$assignment->id}/submissions", [
                'file_jawaban' => UploadedFile::fake()->create('jawaban.pdf', 100, 'application/pdf'),
            ])
            ->assertForbidden();

        $this->actingAs($mahasiswa)
            ->postJson("/assignments/{$assignment->id}/submissions", [
                'file_jawaban' => UploadedFile::fake()->create('jawaban.pdf', 100, 'application/pdf'),
            ])
            ->assertCreated();
    }

    public function test_only_course_dosen_can_manage_material_and_grade(): void
    {
        [$dosen, $mahasiswa, $course, $assignment] = $this->courseFixture();
        $otherDosen = User::factory()->create(['role' => 'dosen']);
        $material = $course->materials()->create([
            'judul' => 'Materi',
            'tipe_materi' => 'pdf',
            'file_path' => 'assignments/materi.pdf',
        ]);
        $submission = $assignment->submissions()->create([
            'user_id' => $mahasiswa->id,
            'file_jawaban' => 'assignments/jawaban.pdf',
        ]);

        $this->actingAs($otherDosen)
            ->patch("/courses/{$course->id}/materials/{$material->id}", [
                'judul' => 'Materi diubah',
                'tipe_materi' => 'pdf',
                'file' => UploadedFile::fake()->create('materi.pdf', 100, 'application/pdf'),
            ])
            ->assertForbidden();

        $this->actingAs($mahasiswa)
            ->postJson("/assignments/{$assignment->id}/submissions/{$submission->id}/grade", ['nilai' => 90])
            ->assertForbidden();

        $this->actingAs($dosen)
            ->postJson("/assignments/{$assignment->id}/submissions/{$submission->id}/grade", ['nilai' => 90])
            ->assertOk();
    }

    public function test_role_middleware_blocks_cross_role_dashboard_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $dosen = User::factory()->create(['role' => 'dosen']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($mahasiswa)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($mahasiswa)->get('/dosen/dashboard')->assertForbidden();
        $this->actingAs($admin)->get('/mahasiswa/dashboard')->assertForbidden();
        $this->actingAs($dosen)->get('/admin/dashboard')->assertForbidden();
    }

    /** @return array{0: User, 1: User, 2: Course, 3: Assignment} */
    private function courseFixture(): array
    {
        $dosen = User::factory()->create(['role' => 'dosen']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $course = Course::create([
            'kode_matkul' => fake()->unique()->bothify('AUTH###'),
            'nama' => 'Authorization',
            'dosen_id' => $dosen->id,
        ]);
        $course->mahasiswa()->attach($mahasiswa);
        $assignment = $course->assignments()->create([
            'judul' => 'Tugas Authorization',
            'tenggat_waktu' => now()->addDay(),
        ]);

        return [$dosen, $mahasiswa, $course, $assignment];
    }
}
