<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    // Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'assignment_id',
        'user_id',
        'file_jawaban', // Path lokasi file tugas yang diupload mahasiswa
        'nilai'         // Nilai yang akan diisi oleh Dosen
    ];

    /**
     * Relasi ke tabel Assignments
     * Pengumpulan (Submission) ini adalah jawaban untuk satu Tugas (Assignment) tertentu
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Relasi ke user yang mengumpulkan tugas.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias relasi untuk konteks mahasiswa.
     */
    public function mahasiswa()
    {
        return $this->user();
    }
}
