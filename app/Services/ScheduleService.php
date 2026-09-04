<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\Course;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ScheduleService
{
    public function __construct(private ScheduleRepositoryInterface $schedules) {}

    public function all(): Collection
    {
        return $this->schedules->all();
    }

    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return $this->schedules->paginate($search, $perPage);
    }

    public function findById(int $id): Schedule
    {
        return $this->schedules->findById($id);
    }

    public function getTodayByCourses(Collection $courses): Collection
    {
        $today = now()->format('Y-m-d');

        return $courses
            ->flatMap(fn(Course $course) => ($course->relationLoaded('schedules')
                ? $course->schedules->filter(fn(Schedule $schedule) => $schedule->hari === $today)->sortBy('jam_mulai')
                : $course->schedules()->whereDate('hari', $today)->orderBy('jam_mulai')->get())
                ->each(fn(Schedule $schedule) => $schedule->setAttribute('course_name', $course->nama)))
            ->values();
    }

    public function create(array $data): Schedule
    {
        $this->checkConflict($data);

        return $this->schedules->create($data);
    }

    public function update(Schedule $schedule, array $data): Schedule
    {
        $this->checkConflict(array_merge($schedule->toArray(), $data));

        return $this->schedules->update($schedule, $data);
    }

    public function delete(Schedule $schedule): bool
    {
        return $this->schedules->delete($schedule);
    }

    public function checkConflict(array $data): void
    {
        $courseId = $data['course_id'] ?? null;
        $hari = $data['hari'] ?? null;
        $start = $data['jam_mulai'] ?? null;
        $end = $data['jam_selesai'] ?? null;
        $ruangan = $data['ruangan'] ?? null;

        if (! $courseId || ! $hari || ! $start || ! $end || ! $ruangan) {
            return;
        }

        $query = Schedule::query()
            ->where('hari', $hari)
            ->where('ruangan', $ruangan)
            ->where('jam_mulai', '<', $end)
            ->where('jam_selesai', '>', $start);

        if (isset($data['id'])) {
            $query->whereKeyNot($data['id']);
        }

        if ($query->exists()) {
            abort(422, 'Jadwal bentrok dengan jadwal lain di ruangan yang sama pada hari dan jam yang sama.');
        }
    }
}
