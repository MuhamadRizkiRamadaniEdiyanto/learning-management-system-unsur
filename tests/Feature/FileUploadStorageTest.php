<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadStorageTest extends TestCase
{
    use RefreshDatabase;

    public function test_dosen_material_is_stored_in_public_assignments_directory(): void
    {
        Storage::fake('public');
        $dosen = User::factory()->create(['role' => 'dosen']);
        $course = Course::create([
            'kode_matkul' => 'UPL101',
            'nama' => 'Upload Materi',
            'dosen_id' => $dosen->id,
        ]);

        $response = $this->actingAs($dosen)->postJson("/courses/{$course->id}/materials", [
            'judul' => 'Materi PDF',
            'tipe_materi' => 'pdf',
            'file' => UploadedFile::fake()->create('materi.pdf', 100, 'application/pdf'),
        ]);

        $response->assertCreated();
        $path = $response->json('data.file_path');
        $this->assertStringStartsWith('assignments/', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_mahasiswa_submission_is_stored_and_can_be_downloaded_by_authorized_user(): void
    {
        Storage::fake('public');
        $dosen = User::factory()->create(['role' => 'dosen']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $otherMahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $course = Course::create([
            'kode_matkul' => 'UPL102',
            'nama' => 'Upload Tugas',
            'dosen_id' => $dosen->id,
        ]);
        $course->mahasiswa()->attach([$mahasiswa->id, $otherMahasiswa->id]);
        $assignment = Assignment::create([
            'course_id' => $course->id,
            'judul' => 'Tugas PDF',
            'tenggat_waktu' => now()->addDay(),
        ]);

        $response = $this->actingAs($mahasiswa)->postJson("/assignments/{$assignment->id}/submissions", [
            'file_jawaban' => UploadedFile::fake()->create('jawaban.pdf', 100, 'application/pdf'),
        ]);

        $response->assertCreated();
        $path = $response->json('data.file_jawaban');
        $this->assertStringStartsWith('assignments/', $path);
        Storage::disk('public')->assertExists($path);

        $submissionId = $response->json('data.id');
        $this->actingAs($mahasiswa)
            ->get("/assignments/{$assignment->id}/submissions/{$submissionId}/download")
            ->assertOk();

        $this->actingAs($otherMahasiswa)
            ->get("/assignments/{$assignment->id}/submissions/{$submissionId}/download")
            ->assertForbidden();
    }
}
