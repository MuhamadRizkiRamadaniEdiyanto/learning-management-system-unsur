<?php

namespace App\Repositories;

use App\Models\Assignment;
use App\Models\Submission;
use App\Repositories\Contracts\SubmissionRepositoryInterface;
use Illuminate\Support\Collection;

class SubmissionRepository implements SubmissionRepositoryInterface
{
    public function all(): Collection
    {
        return Submission::with(['assignment', 'mahasiswa'])->latest()->get();
    }

    public function findById(int $id): Submission
    {
        return Submission::with(['assignment.course', 'mahasiswa'])->findOrFail($id);
    }

    public function create(array $data): Submission
    {
        return Submission::create($data);
    }

    public function update(Submission $submission, array $data): Submission
    {
        $submission->update($data);
        return $submission->refresh();
    }

    public function delete(Submission $submission): bool
    {
        return (bool) $submission->delete();
    }

    public function getByAssignment(int $assignmentId): Collection
    {
        return Submission::with('mahasiswa')->where('assignment_id', $assignmentId)->latest()->get();
    }

    public function getByMahasiswa(int $userId): Collection
    {
        return Submission::with('assignment')->where('user_id', $userId)->latest()->get();
    }

    public function findByAssignmentAndMahasiswa(Assignment $assignment, int $userId): ?Submission
    {
        return Submission::where('assignment_id', $assignment->id)
            ->where('user_id', $userId)
            ->first();
    }
}
