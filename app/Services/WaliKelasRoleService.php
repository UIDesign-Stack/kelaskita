<?php

namespace App\Services;

use App\Models\SchoolClass;
use App\Models\Teacher;

class WaliKelasRoleService
{
    /**
     * Sinkronkan role 'wali_kelas' ketika homeroom teacher sebuah kelas berubah.
     *
     * @param  int|null  $oldTeacherId  ID guru yang lama (sebelum perubahan)
     * @param  int|null  $newTeacherId  ID guru yang baru (setelah perubahan)
     */
    public function sync(?int $oldTeacherId, ?int $newTeacherId): void
    {
        if ($oldTeacherId && $oldTeacherId !== $newTeacherId) {
            $this->revokeIfNoLongerHomeroom($oldTeacherId);
        }

        if ($newTeacherId) {
            $this->grant($newTeacherId);
        }
    }

    private function revokeIfNoLongerHomeroom(int $teacherId): void
    {
        $stillHomeroom = SchoolClass::where('homeroom_teacher_id', $teacherId)->exists();

        if (! $stillHomeroom) {
            Teacher::with('user')->find($teacherId)?->user?->removeRole('wali_kelas');
        }
    }

    private function grant(int $teacherId): void
    {
        $teacher = Teacher::with('user')->find($teacherId);

        if ($teacher?->user && ! $teacher->user->hasRole('wali_kelas')) {
            $teacher->user->assignRole('wali_kelas');
        }
    }
}