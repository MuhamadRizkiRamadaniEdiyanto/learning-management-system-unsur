<?php

namespace Tests\Feature\Dosen;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MaterialValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dosen_pdf_upload_over_10mb_is_rejected(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);
        $course = Course::create([
            'kode_matkul' => 'CS101',
            'nama' => 'Pemrograman Web',
            'deskripsi' => 'Deskripsi',
            'dosen_id' => $dosen->id,
        ]);

        $response = $this->actingAs($dosen)
            ->postJson("/courses/{$course->id}/materials", [
                'judul' => 'Materi besar',
                'deskripsi' => 'Ukuran melebihi batas',
                'tipe_materi' => 'pdf',
                'file' => UploadedFile::fake()->create('besar.pdf', 11000, 'application/pdf'),
            ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['file']);
    }

    public function test_dosen_png_upload_under_10mb_is_accepted(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);
        $course = Course::create([
            'kode_matkul' => 'CS102',
            'nama' => 'Desain Grafis',
            'deskripsi' => 'Deskripsi',
            'dosen_id' => $dosen->id,
        ]);

        $response = $this->actingAs($dosen)
            ->postJson("/courses/{$course->id}/materials", [
                'judul' => 'Materi png',
                'deskripsi' => 'Ukuran valid',
                'tipe_materi' => 'png',
                'file' => UploadedFile::fake()->create('gambar.png', 2048, 'image/png'),
            ]);

        $response->assertCreated()
            ->assertJsonPath('data.tipe_materi', 'png');

        $this->assertDatabaseHas('materials', ['tipe_materi' => 'png', 'course_id' => $course->id]);
    }

    public function test_dosen_youtube_link_is_validated(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);
        $course = Course::create([
            'kode_matkul' => 'CS103',
            'nama' => 'Analisis Data',
            'deskripsi' => 'Deskripsi',
            'dosen_id' => $dosen->id,
        ]);

        $valid = $this->actingAs($dosen)
            ->postJson("/courses/{$course->id}/materials", [
                'judul' => 'Video pembelajaran',
                'deskripsi' => 'Video YouTube',
                'tipe_materi' => 'youtube',
                'link_youtube' => 'https://www.youtube.com/watch?v=abc123xyz',
            ]);

        $valid->assertCreated()
            ->assertJsonPath('data.link_youtube', 'https://www.youtube.com/watch?v=abc123xyz');

        $invalid = $this->actingAs($dosen)
            ->postJson("/courses/{$course->id}/materials", [
                'judul' => 'Video invalid',
                'deskripsi' => 'Bukan YouTube',
                'tipe_materi' => 'youtube',
                'link_youtube' => 'https://example.com/video',
            ]);

        $invalid->assertUnprocessable()->assertJsonValidationErrors(['link_youtube']);

        $ambiguous = $this->actingAs($dosen)
            ->postJson("/courses/{$course->id}/materials", [
                'judul' => 'Video campuran',
                'deskripsi' => 'Harus menolak kombinasi',
                'tipe_materi' => 'youtube',
                'file' => UploadedFile::fake()->create('campuran.pdf', 1024, 'application/pdf'),
                'link_youtube' => 'https://www.youtube.com/watch?v=abc123xyz',
            ]);

        $ambiguous->assertUnprocessable()->assertJsonValidationErrors(['file']);
    }

    public function test_mahasiswa_submission_over_10mb_is_rejected(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $course = Course::create([
            'kode_matkul' => 'CS104',
            'nama' => 'Basis Data',
            'deskripsi' => 'Deskripsi',
            'dosen_id' => $dosen->id,
        ]);
        $course->mahasiswa()->attach($mahasiswa->id);

        $assignment = Assignment::create([
            'course_id' => $course->id,
            'judul' => 'Tugas analisis',
            'deskripsi' => 'Deskripsi tugas',
            'tenggat_waktu' => now()->addDay(),
        ]);

        $response = $this->actingAs($mahasiswa)
            ->postJson("/assignments/{$assignment->id}/submissions", [
                'file_jawaban' => UploadedFile::fake()->create('jawaban.pdf', 11000, 'application/pdf'),
            ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['file_jawaban']);
    }
}
