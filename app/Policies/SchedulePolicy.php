<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
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

    public function update(User $user, Schedule $schedule): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Schedule $schedule): bool
    {
        return $user->role === 'admin';
    }
}
