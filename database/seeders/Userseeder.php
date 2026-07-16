<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Sekolah',
                'email' => 'admin@kelaskita.test',
                'role' => 'admin',
            ],
            [
                'name' => 'Wali Kelas 7A',
                'email' => 'walikelas@kelaskita.test',
                'role' => 'wali_kelas',
            ],
            [
                'name' => 'Guru Matematika',
                'email' => 'guru@kelaskita.test',
                'role' => 'guru',
            ],
            [
                'name' => 'Siswa Contoh',
                'email' => 'siswa@kelaskita.test',
                'role' => 'siswa',
            ],
            [
                'name' => 'Orang Tua Contoh',
                'email' => 'orangtua@kelaskita.test',
                'role' => 'orang_tua',
            ],
        ];

        foreach ($users as $item) {
            $user = User::firstOrCreate(
                ['email' => $item['email']],
                [
                    'name' => $item['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles([$item['role']]);
        }
    }
}