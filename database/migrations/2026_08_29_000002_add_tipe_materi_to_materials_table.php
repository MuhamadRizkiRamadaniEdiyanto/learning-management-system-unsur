<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->enum('tipe_materi', ['pdf', 'png', 'youtube'])->default('pdf')->after('deskripsi');
            $table->string('file_path')->nullable()->change();
            $table->string('link_youtube')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['tipe_materi', 'link_youtube']);
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->string('file_path')->nullable(false)->change();
        });
    }
};
