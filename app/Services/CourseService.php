<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Support\Collection;

class CourseService
{
    public function __construct(private CourseRepositoryInterface $courses) {}

    public function all(): Collection
    {
        return $this->courses->all();
    }

    public function findById(int $id): Course
    {
        return $this->courses->findById($id);
    }

    public function getByDosen(int $dosenId): Collection
    {
        return $this->courses->getByDosen($dosenId);
    }

    public function getByMahasiswa(int $mahasiswaId): Collection
    {
        return $this->courses->getByMahasiswa($mahasiswaId);
    }

    public function create(array $data, int $actorId, string $actorRole): Course
    {
        $this->ensureAdmin($actorId, $actorRole);
        return $this->courses->create($data);
    }

    public function update(Course $course, array $data, int $actorId, string $actorRole): Course
    {
        abort_unless($actorRole === 'admin' || ($actorRole === 'dosen' && (int) $course->dosen_id === $actorId), 403);
        return $this->courses->update($course, $data);
    }

    public function delete(Course $course, int $actorId, string $actorRole): bool
    {
        $this->ensureAdmin($actorId, $actorRole);
        return $this->courses->delete($course);
    }

    public function enroll(Course $course, int $mahasiswaId, int $actorId, string $actorRole): void
    {
        $this->ensureEnrollmentActor($course, $actorId, $actorRole);
        abort_unless(User::whereKey($mahasiswaId)->where('role', 'mahasiswa')->exists(), 422, 'User harus ber-role mahasiswa.');

        // Cegah mahasiswa yang sama terdaftar dua kali pada course yang sama.
        if (! $course->mahasiswa()->whereKey($mahasiswaId)->exists()) {
            $course->mahasiswa()->attach($mahasiswaId);
        }
    }

    public function unenroll(Course $course, int $mahasiswaId, int $actorId, string $actorRole): void
    {
        $this->ensureEnrollmentActor($course, $actorId, $actorRole);
        abort_unless(User::whereKey($mahasiswaId)->where('role', 'mahasiswa')->exists(), 422, 'User harus ber-role mahasiswa.');
        $course->mahasiswa()->detach($mahasiswaId);
    }

    private function ensureAdmin(int $actorId, string $actorRole): void
    {
        abort_unless($actorId > 0 && $actorRole === 'admin', 403);
    }

    private function ensureEnrollmentActor(Course $course, int $actorId, string $actorRole): void
    {
        abort_unless($actorRole === 'admin' || ($actorRole === 'dosen' && (int) $course->dosen_id === $actorId), 403);
    }
}
