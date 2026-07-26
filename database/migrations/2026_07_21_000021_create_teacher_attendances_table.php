<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa']);
            $table->string('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['teacher_id', 'date'], 'teacher_attendance_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_attendances');
    }
};
