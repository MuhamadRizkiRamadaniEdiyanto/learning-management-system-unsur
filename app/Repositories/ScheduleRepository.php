<?php

namespace App\Repositories;

use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function all(): Collection
    {
        return Schedule::with('course')->latest()->get();
    }

    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Schedule::query()->with('course');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ruangan', 'like', "%{$search}%")
                    ->orWhere('hari', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage)->appends(['search' => $search]);
    }

    public function findById(int $id): Schedule
    {
        return Schedule::with('course')->findOrFail($id);
    }

    public function create(array $data): Schedule
    {
        return Schedule::create($data);
    }

    public function update(Schedule $schedule, array $data): Schedule
    {
        $schedule->update($data);

        return $schedule->refresh();
    }

    public function delete(Schedule $schedule): bool
    {
        return (bool) $schedule->delete();
    }
}
