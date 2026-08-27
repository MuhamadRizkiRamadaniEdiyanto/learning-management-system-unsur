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

    public function findById(int $id): Material
    {
        return $this->materials->findById($id);
    }

    public function create(Course $course, array $data, UploadedFile $file, int $userId): Material
    {
        $this->ensureCourseOwner($course, $userId);
        $data['course_id'] = $course->id;
        $data['file_path'] = $file->store('materials', 'public');

        return $this->materials->create($data);
    }

    public function update(Material $material, array $data, ?UploadedFile $file, int $userId): Material
    {
        $this->ensureCourseOwner($material->course, $userId);

        if ($file) {
            $newPath = $file->store('materials', 'public');
            // Hapus file lama hanya setelah file baru berhasil disimpan.
            Storage::disk('public')->delete($material->file_path);
            $data['file_path'] = $newPath;
        }

        return $this->materials->update($material, $data);
    }

    public function delete(Material $material, int $userId): bool
    {
        $this->ensureCourseOwner($material->course, $userId);
        Storage::disk('public')->delete($material->file_path);
        return $this->materials->delete($material);
    }

    public function download(Material $material)
    {
        $disk = Storage::disk('public');
        abort_unless($disk->exists($material->file_path), 404, 'File materi tidak ditemukan.');

        return response()->download($disk->path($material->file_path), basename($material->file_path));
    }

    private function ensureCourseOwner(Course $course, ?int $userId): void
    {
        // Hanya dosen pengampu course yang boleh mengelola materi.
        abort_unless($userId !== null && (int) $course->dosen_id === $userId, 403, 'Anda bukan dosen pengampu course ini.');
    }
}
