<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubstituteTeacher extends Model
{
    protected $fillable = [
        'original_teacher_id', 'substitute_teacher_id', 'class_id',
        'subject_id', 'date', 'notes', 'recorded_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function originalTeacher()
    {
        return $this->belongsTo(Teacher::class, 'original_teacher_id');
    }

    public function substituteTeacher()
    {
        return $this->belongsTo(Teacher::class, 'substitute_teacher_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}