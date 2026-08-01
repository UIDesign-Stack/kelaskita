<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['user', 'homeroomClasses'])->latest()->get();

        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function store(StoreTeacherRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(),
            ]);

            $user->assignRole('guru');

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('teachers', 'public');
            }

            Teacher::create([
                'user_id' => $user->id,
                'nuptk' => $validated['nuptk'] ?? null,
                'photo' => $photoPath,
                'specialization' => $validated['specialization'] ?? null,
            ]);
        });

        return redirect()
            ->route('data-master.teachers.index')
            ->with('status', 'Data guru berhasil ditambahkan.');
    }

    public function show(Teacher $teacher)
    {
        $teacher->load([
            'user',
            'homeroomClasses.schoolYear',
            'teachingAssignments.subject',
            'teachingAssignments.schoolClass',
            'teachingAssignments.schoolYear',
        ]);

        return view('teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load('user');

        return view('teachers.edit', compact('teacher'));
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $teacher) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user = $teacher->user;
            $user->fill($userData);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            $photoPath = $teacher->photo;
            if ($request->hasFile('photo')) {
                if ($teacher->photo) {
                    Storage::disk('public')->delete($teacher->photo);
                }
                $photoPath = $request->file('photo')->store('teachers', 'public');
            }

            $teacher->update([
                'nuptk' => $validated['nuptk'] ?? null,
                'photo' => $photoPath,
                'specialization' => $validated['specialization'] ?? null,
            ]);
        });

        return redirect()
            ->route('data-master.teachers.index')
            ->with('status', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->homeroomClasses()->exists()) {
            return back()->withErrors([
                'delete' => 'Guru "' . $teacher->user->name . '" masih menjadi wali kelas. Ganti wali kelas terlebih dahulu sebelum menghapus data guru ini.',
            ]);
        }

        if ($teacher->teachingAssignments()->exists()) {
            return back()->withErrors([
                'delete' => 'Guru "' . $teacher->user->name . '" masih memiliki penugasan mengajar aktif. Hapus penugasan tersebut terlebih dahulu sebelum menghapus data guru ini.',
            ]);
        }

        DB::transaction(function () use ($teacher) {
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }

            $teacher->user()->delete();
        });

        return redirect()
            ->route('data-master.teachers.index')
            ->with('status', 'Data guru berhasil dihapus.');
    }
}