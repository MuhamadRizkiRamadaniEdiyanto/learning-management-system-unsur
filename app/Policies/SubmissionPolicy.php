<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;

class SubmissionPolicy
{
    public function viewAny(User $user, Assignment $assignment): bool
    {
        return $this->ownsAssignment($user, $assignment) || $this->enrolled($user, $assignment);
    }

    public function view(User $user, Submission $submission): bool
    {
        return $this->ownsAssignment($user, $submission->assignment)
            || ($user->role === 'mahasiswa'
                && (int) $submission->user_id === (int) $user->id
                && $this->enrolled($user, $submission->assignment));
    }

    public function create(User $user, Assignment $assignment): bool
    {
        return $user->role === 'mahasiswa'
            && $this->enrolled($user, $assignment)
            && $this->beforeDeadline($assignment);
    }

    public function update(User $user, Submission $submission): bool
    {
        return $user->role === 'mahasiswa'
            && (int) $submission->user_id === (int) $user->id
            && $this->beforeDeadline($submission->assignment);
    }

    public function delete(User $user, Submission $submission): bool
    {
        return $this->update($user, $submission);
    }

    public function grade(User $user, Submission $submission): bool
    {
        return $this->ownsAssignment($user, $submission->assignment);
    }

    private function ownsAssignment(User $user, Assignment $assignment): bool
    {
        return $user->role === 'dosen' && (int) $assignment->course->dosen_id === (int) $user->id;
    }

    private function enrolled(User $user, Assignment $assignment): bool
    {
        return $user->role === 'mahasiswa'
            && $assignment->course->mahasiswa()->whereKey($user->id)->exists();
    }

    private function beforeDeadline(Assignment $assignment): bool
    {
        // Policy menolak perubahan setelah batas waktu, sebelum service berjalan.
        return Carbon::now()->lessThanOrEqualTo(Carbon::parse($assignment->tenggat_waktu));
    }
}
