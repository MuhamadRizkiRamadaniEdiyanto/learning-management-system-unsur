<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CourseService;
use App\Services\AssignmentService;
use App\Services\ReportService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct(
        private ReportService $reportService,
        private CourseService $courseService,
        private AssignmentService $assignmentService,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('manage', User::class);

        // Get summary data
        $totalCourses = $this->courseService->all()->count();
        $totalDosen = User::where('role', 'dosen')->count();
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $pendingAccounts = User::whereIn('status_akun', ['pending'])->count();
        $activeAssignments = $this->assignmentService->all()
            ->filter(fn($a) => now()->lessThanOrEqualTo($a->tenggat_waktu))
            ->count();

        // Get pending users for approval
        $pendingUsers = User::where('status_akun', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $data = [
            'total_courses' => $totalCourses,
            'total_dosen' => $totalDosen,
            'total_mahasiswa' => $totalMahasiswa,
            'pending_accounts' => $pendingAccounts,
            'active_assignments' => $activeAssignments,
            'pending_users' => $pendingUsers,
        ];

        // If JSON request, return JSON
        if ($request->wantsJson()) {
            return response()->json(['data' => $data]);
        }

        // Otherwise return view
        return view('admin.dashboard', $data);
    }
}
