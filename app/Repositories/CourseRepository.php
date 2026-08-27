<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Support\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    public function all(): Collection
    {
        return Course::with('dosen')->latest()->get();
    }

    public function findById(int $id): Course
    {
        return Course::with('dosen')->findOrFail($id);
    }

    public function create(array $data): Course
    {
        return Course::create($data);
    }

    public function update(Course $course, array $data): Course
    {
        $course->update($data);
        return $course->refresh();
    }

    public function delete(Course $course): bool
    {
        return (bool) $course->delete();
    }

    public function getByDosen(int $dosenId): Collection
    {
        return Course::with('dosen')->where('dosen_id', $dosenId)->latest()->get();
    }

    public function getByMahasiswa(int $mahasiswaId): Collection
    {
        return Course::with('dosen')
            ->whereHas('mahasiswa', fn($query) => $query->whereKey($mahasiswaId))
            ->latest()
            ->get();
    }
}
