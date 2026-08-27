<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    // Menentukan kolom mana saja yang boleh diisi datanya (Mass Assignment)
    protected $fillable = [
        'course_id',
        'judul',
        'deskripsi',
        'file_path'
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
