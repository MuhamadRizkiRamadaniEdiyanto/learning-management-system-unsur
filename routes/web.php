<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// ==========================================
// RUTE KHUSUS ADMIN LMS
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return 'Selamat datang di Dashboard Admin LMS. Ini adalah halaman Admin.';
    })->name('admin.dashboard');

    Route::resource('courses', CourseController::class)
        ->only(['create', 'store', 'destroy']);
});

// ==========================================
// RUTE KHUSUS DOSEN
// ==========================================
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/dashboard', function () {
        return 'Selamat datang di Dashboard Dosen. Anda bisa mengelola kelas di sini.';
    })->name('dosen.dashboard');
});

// ==========================================
// RUTE KHUSUS MAHASISWA 
// ==========================================
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', function () {
        return 'Selamat datang di Ruang Kelas Mahasiswa. Ini adalah area belajar Anda.';
    })->name('mahasiswa.dashboard');
});

// Modul tugas dan pengumpulan menggunakan response JSON untuk client web/API.
Route::middleware('auth')->group(function () {
    Route::resource('courses', CourseController::class)
        ->only(['index', 'show', 'edit', 'update']);

    Route::post('courses/{course}/enroll', [CourseController::class, 'enroll'])
        ->name('courses.enroll');

    Route::delete('courses/{course}/enroll/{user}', [CourseController::class, 'unenroll'])
        ->name('courses.unenroll');

    Route::resource('courses.assignments', AssignmentController::class)
        ->only(['index', 'show'])
        ->scoped();

    Route::resource('courses.materials', MaterialController::class)
        ->only(['index', 'show'])
        ->scoped();

    Route::get('courses/{course}/materials/{material}/download', [MaterialController::class, 'download'])
        ->scopeBindings()
        ->name('courses.materials.download');

    Route::resource('assignments.submissions', SubmissionController::class)
        ->only(['index', 'show'])
        ->scoped();
});

Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::resource('courses.assignments', AssignmentController::class)
        ->except(['index', 'show'])
        ->scoped();

    Route::resource('courses.materials', MaterialController::class)
        ->except(['index', 'show'])
        ->scoped();

    Route::post('assignments/{assignment}/submissions/{submission}/grade', [SubmissionController::class, 'grade'])
        ->scopeBindings()
        ->name('assignments.submissions.grade');
});

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::resource('assignments.submissions', SubmissionController::class)
        ->except(['index', 'show'])
        ->scoped();
});
