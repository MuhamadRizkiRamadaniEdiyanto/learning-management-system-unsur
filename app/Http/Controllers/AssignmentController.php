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

    public function dosenIndex()
    {
        if (request()->wantsJson()) {
            $assignments = Course::where('dosen_id', request()->user()->id)
                ->with('assignments')
                ->get()
                ->flatMap(fn(Course $course) => $course->assignments)
                ->values();

            return response()->json(['data' => $assignments]);
        }

        return view('dosen.assignments.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        $this->authorize('viewAny', [Assignment::class, $course]);

        $data = request()->user()->role === 'mahasiswa'
            ? $this->service->getByCourseForMahasiswa($course, (int) request()->user()->id)
            : $this->service->getByCourse($course);

        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course)
    {
        $this->authorize('create', [Assignment::class, $course]);

        if (! request()->wantsJson()) {
            return view('dosen.assignments.create', compact('course'));
        }

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

        if (! request()->wantsJson() && request()->user()->role === 'mahasiswa') {
            $assignment->load(['course', 'submissions' => fn($query) => $query->where('user_id', request()->user()->id)]);

            return view('mahasiswa.assignments.show', compact('course', 'assignment'));
        }

        return response()->json(['data' => $this->service->findById($assignment->id)]);
    }

    public function mahasiswaIndex()
    {
        $user = request()->user();
        $assignments = $user->enrolledCourses()
            ->with(['assignments.submissions' => fn($query) => $query->where('user_id', $user->id)])
            ->get()
            ->flatMap(fn($course) => $course->assignments->each(fn($assignment) => $assignment->setRelation('course', $course)))
            ->sortBy('tenggat_waktu')
            ->values();

        return view('mahasiswa.assignments.index', compact('assignments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course, Assignment $assignment)
    {
        abort_unless((int) $assignment->course_id === (int) $course->id, 404);
        $this->authorize('update', $assignment);

        if (! request()->wantsJson()) {
            return view('dosen.assignments.edit', compact('course', 'assignment'));
        }

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
