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
}