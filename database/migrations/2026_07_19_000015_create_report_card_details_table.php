<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_card_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_card_id')->constrained('report_cards')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->decimal('final_score', 5, 2);
            $table->string('predicate')->nullable();
            $table->text('teacher_notes')->nullable();
            $table->timestamps();

            $table->unique(['report_card_id', 'subject_id'], 'report_card_subject_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_card_details');
    }
};
