<?php

namespace App\Http\Controllers;

use App\Services\AssignmentService;
use App\Services\CourseService;
use App\Services\ScheduleService;
use App\Services\SubmissionService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DosenDashboardController extends Controller
{
    public function __construct(
        private CourseService $courseService,
        private AssignmentService $assignmentService,
        private SubmissionService $submissionService,
        private ScheduleService $scheduleService,
    ) {}

    public function index(Request $request)
    {
        $dosenId = (int) $request->user()->id;

        // Mata kuliah yang diampu
        $courses = $this->courseService->getByDosen($dosenId);
        $totalCourses = $courses->count();

        // Tugas aktif dan submission belum diperiksa
        $totalAssignments = 0;
        $unGradedSubmissions = collect();

        foreach ($courses as $course) {
            $assignments = $this->assignmentService->getByCourse($course);
            $totalAssignments += $assignments->count();

            foreach ($assignments as $assignment) {
                $submissions = $this->submissionService->getByAssignment($assignment);
                foreach ($submissions as $submission) {
                    if ($submission->nilai === null) {
                        $unGradedSubmissions->push([
                            'id' => $submission->id,
                            'assignment_id' => $assignment->id,
                            'assignment_title' => $assignment->judul,
                            'course_name' => $course->nama,
                            'mahasiswa_name' => $submission->user->name,
                            'submitted_at' => $submission->created_at,
                        ]);
                    }
                }
            }
        }

        // Jadwal mengajar terdekat (7 hari ke depan)
        $upcomingSchedules = collect();
        foreach ($courses as $course) {
            $schedules = $course->schedules()
                ->where('hari', '>=', now()->format('Y-m-d'))
                ->where('hari', '<=', now()->addDays(7)->format('Y-m-d'))
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();

            foreach ($schedules as $schedule) {
                $upcomingSchedules->push([
                    'id' => $schedule->id,
                    'course_name' => $course->nama,
                    'hari' => $schedule->hari,
                    'jam_mulai' => $schedule->jam_mulai,
                    'jam_selesai' => $schedule->jam_selesai,
                    'ruangan' => $schedule->ruangan,
                ]);
            }
        }

        $upcomingSchedules = $upcomingSchedules
            ->sortBy('hari')
            ->take(5)
            ->values();

        $data = [
            'total_courses' => $totalCourses,
            'total_assignments' => $totalAssignments,
            'ungraded_submissions_count' => $unGradedSubmissions->count(),
            'ungraded_submissions' => $unGradedSubmissions->take(10)->values(),
            'courses' => $courses,
            'upcoming_schedules' => $upcomingSchedules,
        ];

        // If JSON request, return JSON
        if ($request->wantsJson()) {
            return response()->json(['data' => $data]);
        }

        // Otherwise return view
        return view('dosen.dashboard', $data);
    }
}
