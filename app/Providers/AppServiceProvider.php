<?php

namespace App\Providers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Material;
use App\Models\Message;
use App\Models\Submission;
use App\Models\User;
use App\Policies\AssignmentPolicy;
use App\Policies\CoursePolicy;
use App\Policies\MaterialPolicy;
use App\Policies\DosenPolicy;
use App\Policies\MahasiswaPolicy;
use App\Policies\MessagePolicy;
use App\Policies\ReportPolicy;
use App\Policies\SchedulePolicy;
use App\Policies\SubmissionPolicy;
use App\Policies\UserPolicy;
use App\Repositories\AssignmentRepository;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\DosenRepositoryInterface;
use App\Repositories\Contracts\MahasiswaRepositoryInterface;
use App\Repositories\Contracts\MaterialRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Repositories\Contracts\SubmissionRepositoryInterface;
use App\Repositories\CourseRepository;
use App\Repositories\DosenRepository;
use App\Repositories\MahasiswaRepository;
use App\Repositories\MaterialRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\SubmissionRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AssignmentRepositoryInterface::class, AssignmentRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(SubmissionRepositoryInterface::class, SubmissionRepository::class);
        $this->app->bind(MaterialRepositoryInterface::class, MaterialRepository::class);
        $this->app->bind(DosenRepositoryInterface::class, DosenRepository::class);
        $this->app->bind(MahasiswaRepositoryInterface::class, MahasiswaRepository::class);
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(Assignment::class, AssignmentPolicy::class);
        Gate::policy(Material::class, MaterialPolicy::class);
        Gate::policy(Submission::class, SubmissionPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Message::class, MessagePolicy::class);

        Gate::define('manage-dosen', [DosenPolicy::class, 'manage']);
        Gate::define('manage-mahasiswa', [MahasiswaPolicy::class, 'manage']);
        Gate::define('manage-schedule', [SchedulePolicy::class, 'manage']);
        Gate::define('manage-report', [ReportPolicy::class, 'manage']);
    }
}
