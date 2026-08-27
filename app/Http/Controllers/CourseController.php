<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollMahasiswaRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\User;
use App\Services\CourseService;

class CourseController extends Controller
{
    public function __construct(private CourseService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Course::class);
        $user = request()->user();
        $courses = $user->role === 'dosen'
            ? $this->service->getByDosen((int) $user->id)
            : $this->service->all();
        return response()->json(['data' => $courses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Course::class);
        return response()->json(['data' => null]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $this->authorize('create', Course::class);
        $course = $this->service->create($request->validated(), (int) $request->user()->id, $request->user()->role);
        return response()->json(['message' => 'Course berhasil dibuat.', 'data' => $course], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $this->authorize('view', $course);
        return response()->json(['data' => $this->service->findById($course->id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        return response()->json(['data' => $course]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $this->authorize('update', $course);
        $updated = $this->service->update($course, $request->validated(), (int) $request->user()->id, $request->user()->role);
        return response()->json(['message' => 'Course berhasil diperbarui.', 'data' => $updated]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $this->service->delete($course, (int) request()->user()->id, request()->user()->role);
        return response()->json(['message' => 'Course berhasil dihapus.']);
    }

    public function enroll(EnrollMahasiswaRequest $request, Course $course)
    {
        $this->authorize('enroll', $course);
        $this->service->enroll($course, (int) $request->validated('user_id'), (int) $request->user()->id, $request->user()->role);
        return response()->json(['message' => 'Mahasiswa berhasil didaftarkan.']);
    }

    public function unenroll(Course $course, User $user)
    {
        $this->authorize('enroll', $course);
        $this->service->unenroll($course, (int) $user->id, (int) request()->user()->id, request()->user()->role);
        return response()->json(['message' => 'Mahasiswa berhasil dikeluarkan.']);
    }
}
