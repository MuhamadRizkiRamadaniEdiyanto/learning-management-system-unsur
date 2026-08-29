<?php

namespace App\Http\Controllers;

use App\Services\ReportService;

class AdminDashboardController extends Controller
{
    public function __construct(private ReportService $reportService) {}

    public function index()
    {
        $this->authorize('manage', \App\Models\User::class);

        return response()->json($this->reportService->dashboardSummary());
    }
}
