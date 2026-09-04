<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Material;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class MaterialService
{
    public function __construct(private MaterialRepositoryInterface $materials) {}

    public function all(): Collection
    {
        return $this->materials->all();
    }

    public function getByCourse(Course $course): Collection
    {
        return $this->materials->getByCourse($course->id);
    }

    public function getLatestByCourses(Collection $courses, int $limit = 5): Collection
    {
        return $courses
            ->flatMap(fn(Course $course) => ($course->relationLoaded('materials')
                ? $course->materials
                : $this->getByCourse($course))->map(function (Material $material) use ($course) {
                $material->setAttribute('course_name', $course->nama);

                return $material;
            }))
            ->sortByDesc('created_at')
            ->take($limit)
            ->values();
    }

    public function findById(int $id): Material
    {
        return $this->materials->findById($id);
    }

    public function create(Course $course, array $data, ?UploadedFile $file, int $userId): Material
    {
        $this->ensureCourseOwner($course, $userId);
        $data['course_id'] = $course->id;

        if ($data['tipe_materi'] === 'youtube') {
            $data['file_path'] = null;
            $data['link_youtube'] = $data['link_youtube'] ?? null;
        } elseif ($file) {
            $data['file_path'] = $file->store('assignments', 'public');
            $data['link_youtube'] = null;
        }

        return $this->materials->create($data);
    }

    public function update(Material $material, array $data, ?UploadedFile $file, int $userId): Material
    {
        $this->ensureCourseOwner($material->course, $userId);

        if (isset($data['tipe_materi']) && $data['tipe_materi'] === 'youtube') {
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $data['file_path'] = null;
            $data['link_youtube'] = $data['link_youtube'] ?? $material->link_youtube;
        } elseif ($file) {
            $newPath = $file->store('assignments', 'public');

            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $data['file_path'] = $newPath;
            $data['link_youtube'] = null;
        }

        return $this->materials->update($material, $data);
    }

    public function delete(Material $material, int $userId): bool
    {
        $this->ensureCourseOwner($material->course, $userId);

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        return $this->materials->delete($material);
    }

    public function download(Material $material)
    {
        if ($material->tipe_materi === 'youtube') {
            abort_unless(! empty($material->link_youtube), 404, 'Link materi YouTube tidak tersedia.');
            return redirect()->away($material->link_youtube);
        }

        $disk = Storage::disk('public');
        abort_unless($material->file_path && $disk->exists($material->file_path), 404, 'File materi tidak ditemukan.');

        return response()->download($disk->path($material->file_path), basename($material->file_path));
    }

    private function ensureCourseOwner(Course $course, ?int $userId): void
    {
        // Hanya dosen pengampu course yang boleh mengelola materi.
        abort_unless($userId !== null && (int) $course->dosen_id === $userId, 403, 'Anda bukan dosen pengampu course ini.');
    }
}
