<?php

namespace App\Repositories\Contracts;

use App\Models\Schedule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ScheduleRepositoryInterface
{
    public function all(): Collection;

    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): Schedule;

    public function create(array $data): Schedule;

    public function update(Schedule $schedule, array $data): Schedule;

    public function delete(Schedule $schedule): bool;
}
