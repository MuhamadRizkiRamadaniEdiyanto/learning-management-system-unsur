<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'judul',
        'deskripsi',
        'tipe_materi',
        'file_path',
        'link_youtube',
    ];

    /**
     * Relasi ke tabel Courses
     * Setiap materi (Material) dimiliki oleh satu Mata Kuliah (Course)
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
