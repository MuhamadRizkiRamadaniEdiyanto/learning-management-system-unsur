<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Collection;

class ReportService
{
    public function jumlahMahasiswaPerMatkul(): Collection
    {
        return Course::withCount('mahasiswa')->get()->map(function ($course) {
            return [
                'course_id' => $course->id,
                'kode_matkul' => $course->kode_matkul,
                'nama' => $course->nama,
                'jumlah_mahasiswa' => $course->mahasiswa_count,
            ];
        });
    }

    public function rekapNilaiPerMatkul($courseId): array
    {
        $course = Course::with(['assignments.submissions.mahasiswa'])->findOrFail($courseId);

        $nilaiList = [];
        foreach ($course->assignments as $assignment) {
            foreach ($assignment->submissions as $submission) {
                $nilaiList[] = [
                    'assignment_id' => $assignment->id,
                    'judul' => $assignment->judul,
                    'mahasiswa' => $submission->mahasiswa?->name,
                    'nilai' => $submission->nilai,
                ];
            }
        }

        return [
            'course_id' => $course->id,
            'course_name' => $course->nama,
            'nilai' => $nilaiList,
            'rata_rata' => collect($nilaiList)->whereNotNull('nilai')->avg('nilai') ?: 0,
        ];
    }

    public function rekapPengumpulanTugas($assignmentId): array
    {
        $assignment = Assignment::with('course')->findOrFail($assignmentId);
        $mahasiswaTerdaftar = $assignment->course->mahasiswa()->count();
        $sudahSubmit = Submission::where('assignment_id', $assignmentId)->count();

        return [
            'assignment_id' => $assignment->id,
            'judul' => $assignment->judul,
            'total_mahasiswa' => $mahasiswaTerdaftar,
            'sudah_submit' => $sudahSubmit,
            'belum_submit' => max($mahasiswaTerdaftar - $sudahSubmit, 0),
        ];
    }

    public function bebanMengajarDosen(): Collection
    {
        return User::where('role', 'dosen')->withCount('coursesAsDosen')->with(['coursesAsDosen'])->get()->map(function ($dosen) {
            return [
                'dosen_id' => $dosen->id,
                'name' => $dosen->name,
                'jumlah_matkul' => $dosen->courses_as_dosen_count,
                'jumlah_mahasiswa' => $dosen->coursesAsDosen->sum(fn($course) => $course->mahasiswa()->count()),
            ];
        });
    }

    public function dashboardSummary(): array
    {
        $mahasiswa = User::where('role', 'mahasiswa')->count();
        $dosen = User::where('role', 'dosen')->count();
        $matkul = Course::count();
        $jadwal = \App\Models\Schedule::count();
        $latestSubmissions = Submission::with(['assignment.course', 'mahasiswa'])->latest()->take(5)->get();

        return [
            'total_mahasiswa' => $mahasiswa,
            'total_dosen' => $dosen,
            'total_matkul' => $matkul,
            'total_jadwal_aktif' => $jadwal,
            'aktivitas_submission_terbaru' => $latestSubmissions->map(fn($submission) => [
                'submission_id' => $submission->id,
                'mahasiswa' => $submission->mahasiswa?->name,
                'matkul' => $submission->assignment?->course?->nama,
                'assignment' => $submission->assignment?->judul,
                'nilai' => $submission->nilai,
                'created_at' => $submission->created_at,
            ])->values(),
        ];
    }

    public function activitySummary(): array
    {
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalAssignments = Assignment::count();
        $totalTerdaftar = Course::withCount('mahasiswa')->get()->sum('mahasiswa_count');
        $totalSubmission = Submission::count();

        return [
            'total_materi' => \App\Models\Material::count(),
            'total_tugas' => $totalAssignments,
            'total_submission' => $totalSubmission,
            'total_peluang_submission' => $totalTerdaftar,
            'rasio_pengumpulan' => $totalTerdaftar > 0
                ? round(($totalSubmission / $totalTerdaftar) * 100, 1)
                : 0,
            'total_mahasiswa' => $totalMahasiswa,
        ];
    }
}
