<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\User;
use App\Repositories\Contracts\MahasiswaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MahasiswaRepository implements MahasiswaRepositoryInterface
{
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = User::query()->where('role', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nomor_induk', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage)->appends(['search' => $search]);
    }

    public function all(): Collection
    {
        return User::where('role', 'mahasiswa')->latest()->get();
    }

    public function findById(int $id): User
    {
        return User::where('role', 'mahasiswa')->findOrFail($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $mahasiswa, array $data): User
    {
        $mahasiswa->update($data);

        return $mahasiswa->refresh();
    }

    public function delete(User $mahasiswa): bool
    {
        return (bool) $mahasiswa->delete();
    }

    public function getCourses(int $userId): Collection
    {
        return Course::with('dosen')
            ->whereHas('mahasiswa', fn($query) => $query->whereKey($userId))
            ->latest()
            ->get();
    }
}
