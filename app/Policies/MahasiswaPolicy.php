<?php

namespace App\Policies;

use App\Models\User;

class MahasiswaPolicy
{
    public function manage(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, User $mahasiswa): bool
    {
        return $user->role === 'admin' && $mahasiswa->role === 'mahasiswa';
    }

    public function delete(User $user, User $mahasiswa): bool
    {
        return $user->role === 'admin' && $mahasiswa->role === 'mahasiswa';
    }

    public function viewCourses(User $user, User $mahasiswa): bool
    {
        return $user->role === 'admin' && $mahasiswa->role === 'mahasiswa';
    }
}
