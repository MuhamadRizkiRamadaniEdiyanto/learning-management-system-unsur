<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\DosenRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DosenRepository implements DosenRepositoryInterface
{
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = User::query()->where('role', 'dosen');

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
        return User::where('role', 'dosen')->latest()->get();
    }

    public function findById(int $id): User
    {
        return User::where('role', 'dosen')->findOrFail($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $dosen, array $data): User
    {
        $dosen->update($data);

        return $dosen->refresh();
    }

    public function delete(User $dosen): bool
    {
        return (bool) $dosen->delete();
    }
}
