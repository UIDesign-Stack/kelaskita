<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_subject_teacher', function (Blueprint $table) {
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
