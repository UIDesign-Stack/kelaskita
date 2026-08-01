<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_subject_teacher', function (Blueprint $table) {
            // Mencegah 1 mapel punya 2 guru pengampu berbeda di kelas & tahun ajaran yang sama.
            // Ini pertahanan utama terhadap race condition, karena pengecekan
            // exists() di level aplikasi saja tidak cukup reliable.
            $table->unique(
                ['class_id', 'subject_id', 'school_year_id'],
                'class_subject_teacher_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('class_subject_teacher', function (Blueprint $table) {
            $table->dropUnique('class_subject_teacher_unique');
        });
    }
};
