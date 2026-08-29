<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'dosen', 'mahasiswa'], true);
    }

    public function view(User $user, Course $course): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'dosen') {
            return (int) $course->dosen_id === (int) $user->id;
        }

        if ($user->role === 'mahasiswa') {
            return $course->mahasiswa()->whereKey($user->id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Course $course): bool
    {
        return $user->role === 'admin'
            || ($user->role === 'dosen' && (int) $course->dosen_id === (int) $user->id);
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->role === 'admin';
    }

    public function enroll(User $user, Course $course): bool
    {
        return $user->role === 'admin'
            || ($user->role === 'dosen' && (int) $course->dosen_id === (int) $user->id);
    }
}
