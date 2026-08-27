<?php

namespace App\Repositories\Contracts;

use App\Models\Material;
use Illuminate\Support\Collection;

interface MaterialRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): Material;

    public function create(array $data): Material;

    public function update(Material $material, array $data): Material;

    public function delete(Material $material): bool;

    public function getByCourse(int $courseId): Collection;
}
