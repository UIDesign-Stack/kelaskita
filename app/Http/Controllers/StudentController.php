<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\StudentAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function __construct(private StudentAccountService $studentAccountService)
    {
    }

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

    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request) {
            $userId = $this->studentAccountService->createForNewStudent(
                $validated,
                $request->boolean('create_account')
            );

            $photoPath = $this->storePhoto($request);

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

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $student) {
            $userId = $this->studentAccountService->syncForExistingStudent(
                $student,
                $validated,
                $request->boolean('create_account')
            );

            $photoPath = $this->storePhoto($request, $student->photo);

            $student->update([
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
            ->with('status', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();

        return redirect()
            ->route('data-master.students.index')
            ->with('status', 'Data siswa berhasil dihapus.');
    }

    private function storePhoto(Request $request, ?string $existingPath = null): ?string
    {
        if (! $request->hasFile('photo')) {
            return $existingPath;
        }

        if ($existingPath) {
            Storage::disk('public')->delete($existingPath);
        }

        return $request->file('photo')->store('students', 'public');
    }
}