<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentAccountService
{
    
    public function createForNewStudent(array $data, bool $shouldCreateAccount): ?int
    {
        if (! $shouldCreateAccount) {
            return null;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('siswa');

        return $user->id;
    }
    public function syncForExistingStudent(Student $student, array $data, bool $shouldCreateAccount): ?int
    {
        if ($student->user_id) {
            $userData = ['name' => $data['name']];

            if (! empty($data['email'])) {
                $userData['email'] = $data['email'];
            }

            if (! empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            $student->user()->update($userData);

            return $student->user_id;
        }

        if ($shouldCreateAccount) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?: str()->random(12)),
                'email_verified_at' => now(),
            ]);

            $user->assignRole('siswa');

            return $user->id;
        }

        return $student->user_id;
    }
}