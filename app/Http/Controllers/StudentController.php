<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('schoolClass')->latest()->get();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'nis' => ['required', 'string', 'max:50', Rule::unique('students', 'nis')],
            'nisn' => ['nullable', 'string', 'max:50', Rule::unique('students', 'nisn')],
            'class_id' => ['required', 'exists:classes,id'],
            'gender' => ['required', 'in:L,P'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,pindah,lulus,keluar'],
            'create_account' => ['nullable', 'boolean'],
            'email' => ['nullable', 'required_if:create_account,1', 'email', Rule::unique('users', 'email')],
            'password' => ['nullable', 'required_if:create_account,1', 'min:8'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $userId = null;

            if ($request->boolean('create_account')) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'email_verified_at' => now(),
                ]);

                $user->assignRole('siswa');

                $userId = $user->id;
            }

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('students', 'public');
            }

            Student::create([
                'user_id' => $userId,
                'name' => $validated['name'],
                'photo' => $photoPath,
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'] ?? null,
                'class_id' => $validated['class_id'],
                'gender' => $validated['gender'],
                'birth_place' => $validated['birth_place'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => $validated['status'],
            ]);
        });

        return redirect()
            ->route('data-master.students.index')
            ->with('status', 'Data siswa berhasil ditambahkan.');
    }
    public function show(Student $student)
    {
        $student->load(['user', 'schoolClass.schoolYear', 'guardians.user']);
 
        return view('students.show', compact('student'));
    }
    public function edit(Student $student)
    {
        $student->load('user');
        $classes = SchoolClass::orderBy('name')->get();
 
        return view('students.edit', compact('student', 'classes'));
    }
 
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'nis' => ['required', 'string', 'max:50', Rule::unique('students', 'nis')->ignore($student->id)],
            'nisn' => ['nullable', 'string', 'max:50', Rule::unique('students', 'nisn')->ignore($student->id)],
            'class_id' => ['required', 'exists:classes,id'],
            'gender' => ['required', 'in:L,P'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,pindah,lulus,keluar'],
            'create_account' => ['nullable', 'boolean'],
            'email' => [
                'nullable',
                'required_if:create_account,1',
                'email',
                Rule::unique('users', 'email')->ignore($student->user_id),
            ],
            'password' => ['nullable', 'min:8'],
        ]);
 
        DB::transaction(function () use ($validated, $request, $student) {
            // ===== Handle akun login =====
            if ($student->user_id) {
                // Sudah punya akun -> update data akunnya
                $userData = ['name' => $validated['name']];
 
                if (!empty($validated['email'])) {
                    $userData['email'] = $validated['email'];
                }
 
                if (!empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }
 
                $student->user()->update($userData);
            } elseif ($request->boolean('create_account')) {
                // Belum punya akun tapi dicentang -> buatkan sekarang
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password'] ?: str()->random(12)),
                    'email_verified_at' => now(),
                ]);
 
                $user->assignRole('siswa');
 
                $validated['user_id'] = $user->id;
            }
 
            // ===== Handle foto =====
            $photoPath = $student->photo;
            if ($request->hasFile('photo')) {
                if ($student->photo) {
                    Storage::disk('public')->delete($student->photo);
                }
                $photoPath = $request->file('photo')->store('students', 'public');
            }
 
            $student->update([
                'user_id' => $validated['user_id'] ?? $student->user_id,
                'name' => $validated['name'],
                'photo' => $photoPath,
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'] ?? null,
                'class_id' => $validated['class_id'],
                'gender' => $validated['gender'],
                'birth_place' => $validated['birth_place'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => $validated['status'],
            ]);
        });
 
        return redirect()
            ->route('data-master.students.index')
            ->with('status', 'Data siswa berhasil diperbarui.');
    }
    public function destroy(Student $student)
    {
    
        $student->delete();
 
        return redirect()
            ->route('data-master.students.index')
            ->with('status', 'Data siswa berhasil dihapus.');
    }
}