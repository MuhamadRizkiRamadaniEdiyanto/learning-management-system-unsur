<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Models\Assignment;
use App\Models\Course;
use App\Services\AssignmentService;

class AssignmentController extends Controller
{
    public function __construct(private AssignmentService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        $this->authorize('viewAny', [Assignment::class, $course]);
        return response()->json(['data' => $this->service->getByCourse($course)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course)
    {
        $this->authorize('create', [Assignment::class, $course]);
        return response()->json(['data' => ['course' => $course]]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssignmentRequest $request, Course $course)
    {
        $this->authorize('create', [Assignment::class, $course]);
        $assignment = $this->service->create($course, $request->validated(), (int) $request->user()->id);
        return response()->json(['message' => 'Tugas berhasil dibuat.', 'data' => $assignment], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course, Assignment $assignment)
    {
        abort_unless((int) $assignment->course_id === (int) $course->id, 404);
        $this->authorize('view', $assignment);
        return response()->json(['data' => $this->service->findById($assignment->id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course, Assignment $assignment)
    {
        abort_unless((int) $assignment->course_id === (int) $course->id, 404);
        $this->authorize('update', $assignment);
        return response()->json(['data' => $assignment]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssignmentRequest $request, Course $course, Assignment $assignment)
    {
        abort_unless((int) $assignment->course_id === (int) $course->id, 404);
        $this->authorize('update', $assignment);
        $updated = $this->service->update($assignment, $request->validated(), (int) $request->user()->id);
        return response()->json(['message' => 'Tugas berhasil diperbarui.', 'data' => $updated]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Assignment $assignment)
    {
        abort_unless((int) $assignment->course_id === (int) $course->id, 404);
        $this->authorize('delete', $assignment);
        $this->service->delete($assignment, (int) request()->user()->id);
        return response()->json(['message' => 'Tugas berhasil dihapus.']);
    }
}
