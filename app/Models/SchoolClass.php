<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = ['school_year_id', 'name', 'grade_level', 'homeroom_teacher_id'];

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function homeroomTeacher()
    {
        return $this->belongsTo(Teacher::class, 'homeroom_teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function teachingAssignments()
    {
        return $this->hasMany(ClassSubjectTeacher::class, 'class_id');
    }
}