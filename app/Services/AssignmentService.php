<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Course;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use Illuminate\Support\Collection;

class AssignmentService
{
    public function __construct(private AssignmentRepositoryInterface $assignments) {}

    public function all(): Collection
    {
        return $this->assignments->all();
    }

    public function getByCourse(Course $course): Collection
    {
        return $this->assignments->getByCourse($course->id);
    }

    public function findById(int $id): Assignment
    {
        return $this->assignments->findById($id);
    }

    public function create(Course $course, array $data, int $userId): Assignment
    {
        $this->ensureCourseOwner($course, $userId);

        return $this->assignments->create([...$data, 'course_id' => $course->id]);
    }

    public function update(Assignment $assignment, array $data, ?int $userId = null): Assignment
    {
        $this->ensureCourseOwner($assignment->course, $userId);
        return $this->assignments->update($assignment, $data);
    }

    public function delete(Assignment $assignment, ?int $userId = null): bool
    {
        $this->ensureCourseOwner($assignment->course, $userId);
        return $this->assignments->delete($assignment);
    }

    private function ensureCourseOwner(Course $course, ?int $userId): void
    {
        abort_unless($userId !== null && (int) $course->dosen_id === $userId, 403, 'Anda bukan dosen pengampu course ini.');
    }
}
