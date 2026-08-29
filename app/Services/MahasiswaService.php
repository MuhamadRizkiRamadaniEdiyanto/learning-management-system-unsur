<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\MahasiswaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class MahasiswaService
{
    public function __construct(private MahasiswaRepositoryInterface $mahasiswas) {}

    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return $this->mahasiswas->paginate($search, $perPage);
    }

    public function all(): Collection
    {
        return $this->mahasiswas->all();
    }

    public function findById(int $id): User
    {
        return $this->mahasiswas->findById($id);
    }

    public function create(array $data): User
    {
        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'nomor_induk' => $data['nomor_induk'],
            'password' => Hash::make($data['password']),
            'role' => 'mahasiswa',
        ];

        return $this->mahasiswas->create($payload);
    }

    public function update(User $mahasiswa, array $data): User
    {
        $payload = $data;

        if (isset($payload['password']) && $payload['password'] !== null && $payload['password'] !== '') {
            $payload['password'] = Hash::make($payload['password']);
        }

        unset($payload['role']);

        return $this->mahasiswas->update($mahasiswa, $payload);
    }

    public function delete(User $mahasiswa): bool
    {
        return $this->mahasiswas->delete($mahasiswa);
    }

    public function courses(User $mahasiswa): Collection
    {
        return $this->mahasiswas->getCourses($mahasiswa->id);
    }
}
