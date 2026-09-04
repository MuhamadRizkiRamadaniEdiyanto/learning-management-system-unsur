<?php

namespace Tests\Feature\Mahasiswa;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MahasiswaSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_mahasiswa_accessing_admin_dashboard_receives_403(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($mahasiswa)
            ->get('/admin/dashboard')
            ->assertStatus(403);
    }

    public function test_mahasiswa_sees_only_enrolled_courses(): void
    {
        $dosen = User::factory()->create([
            'role' => 'dosen',
            'email' => 'dosen.security@example.com',
        ]);

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'mahasiswa.security@example.com',
            'nomor_induk' => '5520119001',
        ]);

        $courseEnrolled = Course::create([
            'kode_matkul' => 'TIF101',
            'nama' => 'Algoritma',
            'deskripsi' => 'Dibuat untuk mahasiswa',
            'dosen_id' => $dosen->id,
        ]);

        $courseOther = Course::create([
            'kode_matkul' => 'TIF202',
            'nama' => 'Database',
            'deskripsi' => 'Course lain',
            'dosen_id' => $dosen->id,
        ]);

        $mahasiswa->enrolledCourses()->attach($courseEnrolled->id);

        $response = $this->actingAs($mahasiswa)->get('/courses');

        $response->assertOk()
            ->assertJsonPath('data.0.id', $courseEnrolled->id)
            ->assertJsonMissing(['id' => $courseOther->id]);
    }

    public function test_mahasiswa_cannot_view_course_they_do_not_follow(): void
    {
        $dosen = User::factory()->create([
            'role' => 'dosen',
            'email' => 'dosen2.security@example.com',
        ]);

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'mahasiswa2.security@example.com',
            'nomor_induk' => '5520119002',
        ]);

        $course = Course::create([
            'kode_matkul' => 'TIF303',
            'nama' => 'Jaringan',
            'deskripsi' => 'Course lain',
            'dosen_id' => $dosen->id,
        ]);

        $this->actingAs($mahasiswa)
            ->get('/courses/' . $course->id)
            ->assertForbidden();
    }

    public function test_mahasiswa_cannot_change_nomor_induk_via_profile_update(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'student.profile@example.com',
            'nomor_induk' => '5520119003',
        ]);

        $response = $this->actingAs($mahasiswa)->patch('/profile', [
            'name' => 'Mahasiswa Baru',
            'email' => 'student.profile@example.com',
            'nomor_induk' => '9999999999',
        ]);

        $response->assertRedirect('/profile');
        $this->assertSame('5520119003', $mahasiswa->fresh()->nomor_induk);
    }

    public function test_mahasiswa_dashboard_returns_enrolled_courses_and_assignment_status(): void
    {
        $dosen = User::factory()->create([
            'role' => 'dosen',
            'email' => 'dosen.dashboard@example.com',
        ]);

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'mahasiswa.dashboard@example.com',
            'nomor_induk' => '5520119004',
        ]);

        $course = Course::create([
            'kode_matkul' => 'TIF404',
            'nama' => 'Pemrograman Web',
            'deskripsi' => 'Dashboard task',
            'dosen_id' => $dosen->id,
        ]);

        $mahasiswa->enrolledCourses()->attach($course->id);

        $this->actingAs($mahasiswa)
            ->getJson('/mahasiswa/dashboard')
            ->assertOk()
            ->assertJsonPath('data.courses.0.id', $course->id)
            ->assertJsonStructure([
                'data' => [
                    'courses',
                    'assignments',
                    'nilai_terakhir',
                ],
            ]);
    }

    public function test_mahasiswa_can_view_own_submission_history(): void
    {
        $dosen = User::factory()->create([
            'role' => 'dosen',
            'email' => 'dosen.submissions@example.com',
        ]);

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'mahasiswa.submissions@example.com',
            'nomor_induk' => '5520119005',
        ]);

        $course = Course::create([
            'kode_matkul' => 'TIF505',
            'nama' => 'Basis Data',
            'deskripsi' => 'Submission history',
            'dosen_id' => $dosen->id,
        ]);

        $mahasiswa->enrolledCourses()->attach($course->id);

        $assignment = $course->assignments()->create([
            'judul' => 'UTS',
            'deskripsi' => 'Tugas UTS',
            'tenggat_waktu' => now()->addDays(5),
        ]);

        $assignment->submissions()->create([
            'user_id' => $mahasiswa->id,
            'file_jawaban' => 'submissions/sample.pdf',
            'nilai' => 85,
        ]);

        $this->actingAs($mahasiswa)
            ->getJson('/mahasiswa/submissions')
            ->assertOk()
            ->assertJsonPath('data.0.user_id', $mahasiswa->id)
            ->assertJsonPath('data.0.assignment_id', $assignment->id);
    }
}
