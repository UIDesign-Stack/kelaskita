<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounselingNote extends Model
{
    protected $fillable = ['student_id', 'date', 'note', 'is_confidential', 'recorded_by'];

    protected $casts = [
        'date' => 'date',
        'is_confidential' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}