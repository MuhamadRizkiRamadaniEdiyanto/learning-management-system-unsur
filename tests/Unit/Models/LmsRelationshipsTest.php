<?php

namespace Tests\Unit\Models;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LmsRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_lms_relationships_are_defined(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $course = Course::create([
            'kode_matkul' => 'REL101',
            'nama' => 'Relasi Laravel',
            'deskripsi' => 'Pengujian relasi',
            'dosen_id' => $dosen->id,
        ]);

        $course->mahasiswa()->attach($mahasiswa);
        $material = $course->materials()->create([
            'judul' => 'Materi Relasi',
            'deskripsi' => 'Materi test',
            'file_path' => 'materials/relasi.pdf',
        ]);
        $assignment = $course->assignments()->create([
            'judul' => 'Tugas Relasi',
            'deskripsi' => 'Tugas test',
            'tenggat_waktu' => now()->addDay(),
        ]);
        $submission = $assignment->submissions()->create([
            'user_id' => $mahasiswa->id,
            'file_jawaban' => 'submissions/relasi.pdf',
        ]);

        $this->assertTrue($dosen->coursesAsDosen->contains($course));
        $this->assertTrue($mahasiswa->enrolledCourses->contains($course));
        $this->assertTrue($course->materials->contains($material));
        $this->assertTrue($course->assignments->contains($assignment));
        $this->assertTrue($assignment->submissions->contains($submission));
        $this->assertTrue($submission->user->is($mahasiswa));
        $this->assertTrue($submission->mahasiswa->is($mahasiswa));
    }

    public function test_deleting_course_cascades_to_pivot_materials_assignments_and_submissions(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $course = Course::create([
            'kode_matkul' => 'REL202',
            'nama' => 'Cascade Laravel',
            'dosen_id' => $dosen->id,
        ]);
        $course->mahasiswa()->attach($mahasiswa);
        $course->materials()->create([
            'judul' => 'Materi Cascade',
            'file_path' => 'materials/cascade.pdf',
        ]);
        $assignment = $course->assignments()->create([
            'judul' => 'Tugas Cascade',
            'tenggat_waktu' => now()->addDay(),
        ]);
        $assignment->submissions()->create([
            'user_id' => $mahasiswa->id,
            'file_jawaban' => 'submissions/cascade.pdf',
        ]);

        $course->delete();

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
        $this->assertDatabaseMissing('course_user', ['course_id' => $course->id]);
        $this->assertDatabaseMissing('materials', ['course_id' => $course->id]);
        $this->assertDatabaseMissing('assignments', ['course_id' => $course->id]);
        $this->assertDatabaseMissing('submissions', ['assignment_id' => $assignment->id]);
    }
}
