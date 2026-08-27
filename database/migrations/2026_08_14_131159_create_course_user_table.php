<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_user', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel courses
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();

            // Menghubungkan ke tabel users (dalam hal ini, Mahasiswa)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->timestamps(); // Untuk mencatat kapan mahasiswa mengambil kelas ini
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_user');
    }
};
