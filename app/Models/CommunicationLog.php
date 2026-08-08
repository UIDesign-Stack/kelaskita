<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationLog extends Model
{
    protected $fillable = ['student_id', 'date', 'teacher_note', 'parent_note', 'parent_replied_at', 'recorded_by'];

    protected $casts = [
        'date' => 'date',
        'parent_replied_at' => 'datetime',
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