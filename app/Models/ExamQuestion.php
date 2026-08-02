<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $fillable = [
        'exam_id', 'question', 'type', 'option_a', 'option_b',
        'option_c', 'option_d', 'correct_answer', 'weight',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
     public function examAnswers()
    {
        return $this->hasMany(ExamAnswer::class, 'question_id');
    }
}