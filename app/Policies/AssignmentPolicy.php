<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;

class AssignmentPolicy
{
    public function viewAny(User $user, Course $course): bool
    {
        return $this->ownsCourse($user, $course) || $this->enrolled($user, $course);
    }

    public function view(User $user, Assignment $assignment): bool
    {
        return $this->ownsCourse($user, $assignment->course) || $this->enrolled($user, $assignment->course);
    }

    public function create(User $user, Course $course): bool
    {
        return $this->ownsCourse($user, $course);
    }

    public function update(User $user, Assignment $assignment): bool
    {
        return $this->ownsCourse($user, $assignment->course);
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        return $this->ownsCourse($user, $assignment->course);
    }

    private function ownsCourse(User $user, Course $course): bool
    {
        return $user->role === 'dosen' && (int) $course->dosen_id === (int) $user->id;
    }

    private function enrolled(User $user, Course $course): bool
    {
        return $user->role === 'mahasiswa' && $course->mahasiswa()->whereKey($user->id)->exists();
    }
}
