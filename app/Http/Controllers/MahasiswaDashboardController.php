<?php

namespace App\Http\Controllers;

use App\Services\AssignmentService;
use App\Services\CourseService;
use App\Services\MaterialService;
use App\Services\ScheduleService;
use App\Services\SubmissionService;
use Illuminate\Http\Request;

class MahasiswaDashboardController extends Controller
{
    public function __construct(
        private CourseService $courseService,
        private AssignmentService $assignmentService,
        private SubmissionService $submissionService,
        private MaterialService $materialService,
        private ScheduleService $scheduleService,
    ) {}

    public function index(Request $request)
    {
        $mahasiswaId = (int) $request->user()->id;

        $courses = $this->courseService->getByMahasiswa($mahasiswaId);
        $assignments = $courses->flatMap(function ($course) use ($mahasiswaId) {
            return $this->assignmentService->getByCourseForMahasiswa($course, $mahasiswaId)
                ->map(function ($assignment) use ($course) {
                    return [
                        'id' => $assignment->id,
                        'course_id' => $course->id,
                        'course_name' => $course->nama,
                        'judul' => $assignment->judul,
                        'tenggat_waktu' => $assignment->tenggat_waktu,
                        'status' => $assignment->submission_status,
                        'nilai' => $assignment->submission_nilai,
                    ];
                });
        })
            ->sortBy('tenggat_waktu')
            ->take(7)
            ->values();

        $nilaiTerakhir = $this->submissionService->getByMahasiswa($mahasiswaId)
            ->filter(fn($submission) => $submission->nilai !== null)
            ->take(5)
            ->values();

        $todaySchedules = $this->scheduleService->getTodayByCourses($courses);
        $latestMaterials = $this->materialService->getLatestByCourses($courses);
        $latestMessages = $courses
            ->flatMap(fn($course) => $course->messages->map(function ($message) use ($course) {
                $message->setAttribute('course_name', $course->nama);

                return $message;
            }))
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        $data = [
            'courses' => $courses,
            'assignments' => $assignments,
            'nilai_terakhir' => $nilaiTerakhir,
            'today_schedules' => $todaySchedules,
            'latest_materials' => $latestMaterials,
            'latest_messages' => $latestMessages,
        ];

        if ($request->wantsJson()) {
            return response()->json(['data' => $data]);
        }

        return view('mahasiswa.dashboard', $data);
    }
}
