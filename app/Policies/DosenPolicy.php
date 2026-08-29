<?php

namespace App\Policies;

use App\Models\User;

class DosenPolicy
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

    public function update(User $user, User $dosen): bool
    {
        return $user->role === 'admin' && $dosen->role === 'dosen';
    }

    public function delete(User $user, User $dosen): bool
    {
        return $user->role === 'admin' && $dosen->role === 'dosen';
    }
}
