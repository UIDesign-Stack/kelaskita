<?php

namespace App\Policies;

use App\Models\ClassSubjectTeacher;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClassSubjectTeacherPolicy
{
    
    public function manage(User $user, ClassSubjectTeacher $assignment): Response
    {
        if ($user->teacher && $assignment->teacher_id === $user->teacher->id) {
            return Response::allow();
        }

        return Response::deny('Anda tidak memiliki akses ke kelas/mapel ini.');
    }
}