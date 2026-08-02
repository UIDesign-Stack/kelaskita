<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_result_id')->constrained('exam_results')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('exam_questions')->cascadeOnDelete();
            $table->text('answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->integer('score')->nullable(); // buat esai yang dinilai manual guru
            $table->timestamps();

            $table->unique(['exam_result_id', 'question_id'], 'exam_answer_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_answers');
    }
};
