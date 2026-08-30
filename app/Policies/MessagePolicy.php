<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    /**
     * Determine whether the user can view any messages for a course.
     */
    public function viewAny(User $user, Course $course): bool
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

    /**
     * Determine whether the user can view a message.
     */
    public function view(User $user, Course $course): bool
    {
        return $this->viewAny($user, $course);
    }

    /**
     * Determine whether the user can create a message in a course.
     */
    public function create(User $user, Course $course): bool
    {
        if ($user->status_akun !== 'aktif') {
            return false;
        }

        return $this->viewAny($user, $course);
    }
}
