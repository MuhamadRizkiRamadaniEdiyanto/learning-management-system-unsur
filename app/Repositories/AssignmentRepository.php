<?php

namespace App\Repositories;

use App\Models\Assignment;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use Illuminate\Support\Collection;

class AssignmentRepository implements AssignmentRepositoryInterface
{
    public function all(): Collection
    {
        return Assignment::with('course')->latest()->get();
    }

    public function findById(int $id): Assignment
    {
        return Assignment::with('course')->findOrFail($id);
    }

    public function create(array $data): Assignment
    {
        return Assignment::create($data);
    }

    public function update(Assignment $assignment, array $data): Assignment
    {
        $assignment->update($data);
        return $assignment->refresh();
    }

    public function delete(Assignment $assignment): bool
    {
        return (bool) $assignment->delete();
    }

    public function getByCourse(int $courseId): Collection
    {
        return Assignment::where('course_id', $courseId)->latest('tenggat_waktu')->get();
    }
}
