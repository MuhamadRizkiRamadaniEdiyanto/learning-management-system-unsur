<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['kode_matkul', 'nama', 'deskripsi', 'dosen_id'];

    // Mata kuliah ini milik 1 Dosen pengampu
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    // Diambil oleh banyak Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }

    // Memiliki banyak Materi
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    // Memiliki banyak Tugas
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
