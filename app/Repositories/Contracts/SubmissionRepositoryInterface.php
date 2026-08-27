<?php

namespace App\Repositories\Contracts;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Support\Collection;

interface SubmissionRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): Submission;

    public function create(array $data): Submission;

    public function update(Submission $submission, array $data): Submission;

    public function delete(Submission $submission): bool;

    public function getByAssignment(int $assignmentId): Collection;

    public function getByMahasiswa(int $userId): Collection;

    public function findByAssignmentAndMahasiswa(Assignment $assignment, int $userId): ?Submission;
}
