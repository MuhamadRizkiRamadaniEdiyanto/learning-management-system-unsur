<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use App\Repositories\Contracts\DosenRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class DosenService
{
    public function __construct(private DosenRepositoryInterface $dosens) {}

    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return $this->dosens->paginate($search, $perPage);
    }

    public function all(): Collection
    {
        return $this->dosens->all();
    }

    public function findById(int $id): User
    {
        return $this->dosens->findById($id);
    }

    public function create(array $data): User
    {
        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'nomor_induk' => $data['nomor_induk'],
            'password' => Hash::make($data['password']),
            'role' => 'dosen',
        ];

        return $this->dosens->create($payload);
    }

    public function update(User $dosen, array $data): User
    {
        $payload = $data;

        if (isset($payload['password']) && $payload['password'] !== null && $payload['password'] !== '') {
            $payload['password'] = Hash::make($payload['password']);
        }

        unset($payload['role']);

        return $this->dosens->update($dosen, $payload);
    }

    public function delete(User $dosen): bool
    {
        if ($dosen->coursesAsDosen()->exists()) {
            abort(422, 'Dosen masih memiliki matakuliah yang aktif dan tidak dapat dihapus.');
        }

        return $this->dosens->delete($dosen);
    }
}
