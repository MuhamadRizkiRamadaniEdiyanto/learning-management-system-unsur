<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('kode_matkul')->unique(); // Contoh: TIF101
            $table->string('nama'); // Contoh: Algoritma dan Pemrograman
            $table->text('deskripsi')->nullable();

            // Relasi ke tabel users (siapa dosen yang mengajar matkul ini)
            // cascadeOnDelete() berarti jika dosen dihapus, matkulnya juga terhapus.
            $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
