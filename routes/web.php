<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DosenDashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MessageController;
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
    Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
});

require __DIR__ . '/auth.php';

// ==========================================
// RUTE KHUSUS ADMIN LMS
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('courses', CourseController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::patch('courses/{course}/assign-dosen', [CourseController::class, 'assignDosen'])->name('courses.assign-dosen');
    Route::resource('dosen', \App\Http\Controllers\DosenController::class);
    Route::patch('dosen/{dosen}/approve', [\App\Http\Controllers\DosenController::class, 'approve'])->name('dosen.approve');
    Route::patch('dosen/{dosen}/reject', [\App\Http\Controllers\DosenController::class, 'reject'])->name('dosen.reject');
    Route::resource('mahasiswa', \App\Http\Controllers\MahasiswaController::class);
    Route::patch('mahasiswa/{mahasiswa}/approve', [\App\Http\Controllers\MahasiswaController::class, 'approve'])->name('mahasiswa.approve');
    Route::patch('mahasiswa/{mahasiswa}/reject', [\App\Http\Controllers\MahasiswaController::class, 'reject'])->name('mahasiswa.reject');
    Route::get('mahasiswa/{mahasiswa}/courses', [\App\Http\Controllers\MahasiswaController::class, 'courses'])->name('mahasiswa.courses');
    Route::resource('schedules', \App\Http\Controllers\ScheduleController::class);
    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/{jenis}/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
    Route::get('reports/mahasiswa-per-matkul', [\App\Http\Controllers\ReportController::class, 'jumlahMahasiswaPerMatkul'])->name('reports.mahasiswa-per-matkul');
    Route::get('reports/nilai-per-matkul/{course}', [\App\Http\Controllers\ReportController::class, 'rekapNilaiPerMatkul'])->name('reports.nilai-per-matkul');
    Route::get('reports/pengumpulan-tugas/{assignment}', [\App\Http\Controllers\ReportController::class, 'rekapPengumpulanTugas'])->name('reports.pengumpulan-tugas');
    Route::get('reports/beban-mengajar', [\App\Http\Controllers\ReportController::class, 'bebanMengajarDosen'])->name('reports.beban-mengajar');
});

// ==========================================
// RUTE KHUSUS DOSEN
// ==========================================
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/dashboard', [DosenDashboardController::class, 'index'])->name('dosen.dashboard');

    // Dosen materials, assignments, submissions routes
    Route::get('/dosen/materials', [\App\Http\Controllers\MaterialController::class, 'dosenIndex'])->name('dosen.materials.index');
    Route::get('/dosen/assignments', [\App\Http\Controllers\AssignmentController::class, 'dosenIndex'])->name('dosen.assignments.index');
    Route::get('/dosen/submissions', [\App\Http\Controllers\SubmissionController::class, 'dosenIndex'])->name('dosen.submissions.index');
    Route::get('/dosen/messages', [\App\Http\Controllers\MessageController::class, 'dosenIndex'])->name('dosen.messages.index');
});

// ==========================================
// RUTE KHUSUS MAHASISWA 
// ==========================================
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', [\App\Http\Controllers\MahasiswaDashboardController::class, 'index'])->name('mahasiswa.dashboard');
    Route::get('/mahasiswa/submissions', [\App\Http\Controllers\SubmissionController::class, 'mySubmissions'])->name('mahasiswa.submissions');
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

    Route::get('courses/{course}/messages', [MessageController::class, 'index'])
        ->name('courses.messages.index');

    Route::post('courses/{course}/messages', [MessageController::class, 'store'])
        ->name('courses.messages.store');

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
