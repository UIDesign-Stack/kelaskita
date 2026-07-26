<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->date('date');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa']);
            $table->string('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'date', 'subject_id'], 'attendance_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
