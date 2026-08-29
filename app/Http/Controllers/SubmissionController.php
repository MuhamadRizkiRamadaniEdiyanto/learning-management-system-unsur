<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeSubmissionRequest;
use App\Http\Requests\StoreSubmissionRequest;
use App\Models\Assignment;
use App\Models\Submission;
use App\Services\SubmissionService;

class SubmissionController extends Controller
{
    public function __construct(private SubmissionService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Assignment $assignment)
    {
        $this->authorize('viewAny', [Submission::class, $assignment]);
        $submissions = request()->user()->role === 'dosen'
            ? $this->service->getByAssignment($assignment)
            : $this->service->getByMahasiswa((int) request()->user()->id);
        return response()->json(['data' => $submissions]);
    }

    public function mySubmissions()
    {
        abort_unless(request()->user()?->role === 'mahasiswa', 403, 'Akses hanya untuk mahasiswa.');

        return response()->json([
            'data' => $this->service->getByMahasiswa((int) request()->user()->id),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Assignment $assignment)
    {
        $this->authorize('create', [Submission::class, $assignment]);
        return response()->json(['data' => ['assignment' => $assignment]]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubmissionRequest $request, Assignment $assignment)
    {
        $this->authorize('create', [Submission::class, $assignment]);
        $submission = $this->service->submit($assignment, (int) $request->user()->id, $request->file('file_jawaban'));
        return response()->json(['message' => 'Tugas berhasil dikumpulkan.', 'data' => $submission], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment, Submission $submission)
    {
        abort_unless((int) $submission->assignment_id === (int) $assignment->id, 404);
        $this->authorize('view', $submission);
        return response()->json(['data' => $this->service->findById($submission->id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment, Submission $submission)
    {
        abort_unless((int) $submission->assignment_id === (int) $assignment->id, 404);
        $this->authorize('update', $submission);
        return response()->json(['data' => $submission]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSubmissionRequest $request, Assignment $assignment, Submission $submission)
    {
        abort_unless((int) $submission->assignment_id === (int) $assignment->id, 404);
        $this->authorize('update', $submission);
        $updated = $this->service->update($submission, $request->file('file_jawaban'));
        return response()->json(['message' => 'Pengumpulan berhasil diperbarui.', 'data' => $updated]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment, Submission $submission)
    {
        abort_unless((int) $submission->assignment_id === (int) $assignment->id, 404);
        $this->authorize('delete', $submission);
        $this->service->delete($submission);
        return response()->json(['message' => 'Pengumpulan berhasil dihapus.']);
    }

    public function grade(GradeSubmissionRequest $request, Assignment $assignment, Submission $submission)
    {
        abort_unless((int) $submission->assignment_id === (int) $assignment->id, 404);
        $this->authorize('grade', $submission);
        $graded = $this->service->grade($submission, $request->validated('nilai'));
        return response()->json(['message' => 'Submission berhasil dinilai.', 'data' => $graded]);
    }
}
