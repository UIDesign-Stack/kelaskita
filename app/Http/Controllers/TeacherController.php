<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8'],
            'nuptk' => ['nullable', 'string', 'max:50', Rule::unique('teachers', 'nuptk')],
            'specialization' => ['nullable', 'string', 'max:255'],
        ]);

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
 
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($teacher->user_id)],
            'password' => ['nullable', 'min:8'],
            'nuptk' => ['nullable', 'string', 'max:50', Rule::unique('teachers', 'nuptk')->ignore($teacher->id)],
            'specialization' => ['nullable', 'string', 'max:255'],
        ]);
 
        DB::transaction(function () use ($validated, $request, $teacher) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];
 
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
 
            $teacher->user()->update($userData);
 
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