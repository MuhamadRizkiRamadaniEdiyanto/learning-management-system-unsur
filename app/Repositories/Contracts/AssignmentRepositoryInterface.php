<?php

namespace App\Repositories\Contracts;

use App\Models\Assignment;
use Illuminate\Support\Collection;

interface AssignmentRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): Assignment;

    public function create(array $data): Assignment;

    public function update(Assignment $assignment, array $data): Assignment;

    public function delete(Assignment $assignment): bool;

    public function getByCourse(int $courseId): Collection;
}
