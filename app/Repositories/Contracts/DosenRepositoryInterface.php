<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DosenRepositoryInterface
{
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator;

    public function all(): Collection;

    public function findById(int $id): User;

    public function create(array $data): User;

    public function update(User $dosen, array $data): User;

    public function delete(User $dosen): bool;
}
