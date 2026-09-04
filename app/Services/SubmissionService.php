<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Submission;
use App\Repositories\Contracts\SubmissionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class SubmissionService
{
    public function __construct(private SubmissionRepositoryInterface $submissions) {}

    public function getByAssignment(Assignment $assignment): Collection
    {
        return $this->submissions->getByAssignment($assignment->id);
    }

    public function getByMahasiswa(int $userId): Collection
    {
        return $this->submissions->getByMahasiswa($userId);
    }

    public function findById(int $id): Submission
    {
        return $this->submissions->findById($id);
    }

    public function submit(Assignment $assignment, int $userId, UploadedFile $file): Submission
    {
        $this->ensureBeforeDeadline($assignment);
        $existing = $this->submissions->findByAssignmentAndMahasiswa($assignment, $userId);

        abort_if($existing, 422, 'Anda sudah mengumpulkan tugas ini. Gunakan fitur perbarui submission.');

        $path = $file->store('assignments', 'public');

        return $this->submissions->create([
            'assignment_id' => $assignment->id,
            'user_id' => $userId,
            'file_jawaban' => $path,
        ]);
    }

    public function update(Submission $submission, UploadedFile $file): Submission
    {
        $this->ensureBeforeDeadline($submission->assignment);
        $path = $file->store('assignments', 'public');
        Storage::disk('public')->delete($submission->file_jawaban);

        return $this->submissions->update($submission, ['file_jawaban' => $path, 'nilai' => null]);
    }

    public function delete(Submission $submission): bool
    {
        $this->ensureBeforeDeadline($submission->assignment);
        Storage::disk('public')->delete($submission->file_jawaban);
        return $this->submissions->delete($submission);
    }

    public function download(Submission $submission)
    {
        $disk = Storage::disk('public');
        abort_unless($submission->file_jawaban && $disk->exists($submission->file_jawaban), 404, 'File pengumpulan tidak ditemukan.');

        return response()->download($disk->path($submission->file_jawaban), basename($submission->file_jawaban));
    }

    public function grade(Submission $submission, int|float $nilai, ?string $feedback = null): Submission
    {
        return $this->submissions->update($submission, ['nilai' => $nilai, 'feedback' => $feedback]);
    }

    private function ensureBeforeDeadline(Assignment $assignment): void
    {
        // Pengumpulan dan perubahan ditutup tepat setelah tenggat waktu.
        abort_if(Carbon::now()->greaterThan(Carbon::parse($assignment->tenggat_waktu)), 422, 'Tenggat waktu tugas sudah lewat.');
    }
}
