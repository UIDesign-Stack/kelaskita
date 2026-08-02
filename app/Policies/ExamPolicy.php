<?php

namespace App\Policies;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExamPolicy
{

    public function manage(User $user, Exam $exam): Response
    {
        if ($user->teacher && $exam->teacher_id === $user->teacher->id) {
            return Response::allow();
        }

        return Response::deny('Anda tidak memiliki akses ke ujian ini.');
    }

    public function manageQuestions(User $user, Exam $exam): Response
    {
        if (!$user->teacher || $exam->teacher_id !== $user->teacher->id) {
            return Response::deny('Anda tidak memiliki akses ke ujian ini.');
        }

        if ($exam->status === 'approved') {
            return Response::deny('Soal tidak bisa diubah karena ujian ini sudah disetujui admin.');
        }

        return Response::allow();
    }
}