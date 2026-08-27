<?php

namespace App\Repositories;

use App\Models\Material;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use Illuminate\Support\Collection;

class MaterialRepository implements MaterialRepositoryInterface
{
    public function all(): Collection
    {
        return Material::with('course')->latest()->get();
    }

    public function findById(int $id): Material
    {
        return Material::with('course')->findOrFail($id);
    }

    public function create(array $data): Material
    {
        return Material::create($data);
    }

    public function update(Material $material, array $data): Material
    {
        $material->update($data);
        return $material->refresh();
    }

    public function delete(Material $material): bool
    {
        return (bool) $material->delete();
    }

    public function getByCourse(int $courseId): Collection
    {
        return Material::with('course')->where('course_id', $courseId)->latest()->get();
    }
}
