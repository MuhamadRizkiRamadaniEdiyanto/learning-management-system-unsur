<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Course;
use App\Models\Material;
use App\Services\MaterialService;

class MaterialController extends Controller
{
    public function __construct(private MaterialService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        $this->authorize('viewAny', [Material::class, $course]);
        return response()->json(['data' => $this->service->getByCourse($course)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course)
    {
        $this->authorize('create', [Material::class, $course]);

        if (! request()->wantsJson()) {
            return view('dosen.materials.create', compact('course'));
        }

        return response()->json(['data' => ['course' => $course]]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaterialRequest $request, Course $course)
    {
        $this->authorize('create', [Material::class, $course]);
        $material = $this->service->create(
            $course,
            $request->safe()->except('file'),
            $request->file('file'),
            (int) $request->user()->id
        );

        return response()->json(['message' => 'Materi berhasil dibuat.', 'data' => $material], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course, Material $material)
    {
        abort_unless((int) $material->course_id === (int) $course->id, 404);
        $this->authorize('view', $material);
        return response()->json(['data' => $this->service->findById($material->id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course, Material $material)
    {
        abort_unless((int) $material->course_id === (int) $course->id, 404);
        $this->authorize('update', $material);
        return response()->json(['data' => $material]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaterialRequest $request, Course $course, Material $material)
    {
        abort_unless((int) $material->course_id === (int) $course->id, 404);
        $this->authorize('update', $material);
        $updated = $this->service->update(
            $material,
            $request->safe()->except('file'),
            $request->file('file'),
            (int) $request->user()->id
        );

        return response()->json(['message' => 'Materi berhasil diperbarui.', 'data' => $updated]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Material $material)
    {
        abort_unless((int) $material->course_id === (int) $course->id, 404);
        $this->authorize('delete', $material);
        $this->service->delete($material, (int) request()->user()->id);
        return response()->json(['message' => 'Materi berhasil dihapus.']);
    }

    public function download(Course $course, Material $material)
    {
        abort_unless((int) $material->course_id === (int) $course->id, 404);
        $this->authorize('view', $material);
        return $this->service->download($material);
    }
}
