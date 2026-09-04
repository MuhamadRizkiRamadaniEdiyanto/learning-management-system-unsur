<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct(private ScheduleService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Schedule::class);

        return response()->json([
            'data' => $this->service->paginate($request->query('search')),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Schedule::class);

        if (! request()->wantsJson()) {
            return view('admin.schedules.create', ['courses' => \App\Models\Course::orderBy('nama')->get()]);
        }

        return response()->json(['data' => null]);
    }

    public function store(StoreScheduleRequest $request)
    {
        $this->authorize('create', Schedule::class);

        $schedule = $this->service->create($request->validated());

        return response()->json(['message' => 'Jadwal berhasil dibuat.', 'data' => $schedule], 201);
    }

    public function edit(Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        if (! request()->wantsJson()) {
            return view('admin.schedules.edit', [
                'schedule' => $schedule,
                'courses' => \App\Models\Course::orderBy('nama')->get(),
            ]);
        }

        return response()->json(['data' => $schedule]);
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        $updated = $this->service->update($schedule, $request->validated());

        return response()->json(['message' => 'Jadwal berhasil diperbarui.', 'data' => $updated]);
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);

        $this->service->delete($schedule);

        return response()->json(['message' => 'Jadwal berhasil dihapus.']);
    }
}
