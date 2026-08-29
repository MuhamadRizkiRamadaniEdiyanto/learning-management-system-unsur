<?php

namespace App\Http\Controllers;

use App\Services\AssignmentService;
use App\Services\CourseService;
use App\Services\SubmissionService;
use Illuminate\Http\Request;

class MahasiswaDashboardController extends Controller
{
    public function __construct(
        private CourseService $courseService,
        private AssignmentService $assignmentService,
        private SubmissionService $submissionService,
    ) {}

    public function index(Request $request)
    {
        $mahasiswaId = (int) $request->user()->id;

        $courses = $this->courseService->getByMahasiswa($mahasiswaId);
        $assignments = [];

        foreach ($courses as $course) {
            $courseAssignments = $this->assignmentService->getByCourseForMahasiswa($course, $mahasiswaId);

            foreach ($courseAssignments as $assignment) {
                $assignments[] = [
                    'id' => $assignment->id,
                    'course_id' => $course->id,
                    'course_name' => $course->nama,
                    'judul' => $assignment->judul,
                    'tenggat_waktu' => $assignment->tenggat_waktu,
                    'status' => $assignment->submission_status,
                    'nilai' => $assignment->submission_nilai,
                ];
            }
        }

        $assignments = collect($assignments)
            ->sortBy('tenggat_waktu')
            ->take(7)
            ->values();

        $nilaiTerakhir = $this->submissionService->getByMahasiswa($mahasiswaId)
            ->filter(fn($submission) => $submission->nilai !== null)
            ->take(5)
            ->values();

        return response()->json([
            'data' => [
                'courses' => $courses,
                'assignments' => $assignments,
                'nilai_terakhir' => $nilaiTerakhir,
            ],
        ]);
    }
}
