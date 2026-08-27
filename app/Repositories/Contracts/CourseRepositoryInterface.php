<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use Illuminate\Support\Collection;

interface CourseRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): Course;

    public function create(array $data): Course;

    public function update(Course $course, array $data): Course;

    public function delete(Course $course): bool;

    public function getByDosen(int $dosenId): Collection;

    public function getByMahasiswa(int $mahasiswaId): Collection;
}
