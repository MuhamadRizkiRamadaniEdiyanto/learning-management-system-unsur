<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;

abstract class Controller
{
    protected function authorize(string $ability, mixed $arguments = []): void
    {
        Gate::authorize($ability, $arguments);
    }
}
