<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'class_id', 'nis', 'nisn', 'gender',
        'birth_place', 'birth_date', 'address', 'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function guardians()
    {
        return $this->belongsToMany(Guardian::class, 'parent_student', 'student_id', 'parent_id');
    }
}