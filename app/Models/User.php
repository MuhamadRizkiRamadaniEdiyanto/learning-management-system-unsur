<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nomor_induk',
        'role',
        'status_akun',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==========================================
    // RELASI DATABASE UNTUK LMS
    // ==========================================

    /**
     * Relasi jika User ini adalah Dosen (1 Dosen mengampu banyak Mata Kuliah)
     */
    public function coursesAsDosen()
    {
        return $this->hasMany(Course::class, 'dosen_id');
    }

    /**
     * Relasi jika User ini adalah Mahasiswa (Mengambil banyak Mata Kuliah / KRS)
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user');
    }

    /**
     * Relasi tugas yang sudah dikumpulkan oleh Mahasiswa ini
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
