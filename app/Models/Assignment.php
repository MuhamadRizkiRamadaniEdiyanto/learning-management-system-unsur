<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    // Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'course_id',
        'judul',
        'deskripsi',
        'tenggat_waktu' // Waktu deadline tugas
    ];

    /**
     * Relasi ke tabel Courses
     * Setiap tugas (Assignment) berada di dalam satu Mata Kuliah (Course)
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relasi ke tabel Submissions
     * Satu tugas (Assignment) bisa memiliki banyak pengumpulan jawaban (Submission) dari mahasiswa
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
