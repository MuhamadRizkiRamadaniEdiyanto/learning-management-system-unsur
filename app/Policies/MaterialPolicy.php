<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Material;
use App\Models\User;

class MaterialPolicy
{
    public function viewAny(User $user, Course $course): bool
    {
        return $this->ownsCourse($user, $course) || $this->enrolled($user, $course);
    }

    public function view(User $user, Material $material): bool
    {
        return $this->ownsCourse($user, $material->course) || $this->enrolled($user, $material->course);
    }

    public function create(User $user, Course $course): bool
    {
        return $this->ownsCourse($user, $course);
    }

    public function update(User $user, Material $material): bool
    {
        return $this->ownsCourse($user, $material->course);
    }

    public function delete(User $user, Material $material): bool
    {
        return $this->ownsCourse($user, $material->course);
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
