<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::with(['schoolYear', 'homeroomTeacher.user'])
            ->withCount('students')
            ->latest()
            ->get();

        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        $schoolYears = SchoolYear::orderByDesc('name')->get();
        $teachers = Teacher::with('user')->get();

        return view('classes.create', compact('schoolYears', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'grade_level' => ['required', 'string', 'max:20'],
            'school_year_id' => ['required', 'exists:school_years,id'],
            'homeroom_teacher_id' => ['nullable', 'exists:teachers,id'],
        ]);

        if (!empty($validated['homeroom_teacher_id'])) {
            $alreadyHomeroom = SchoolClass::where('homeroom_teacher_id', $validated['homeroom_teacher_id'])
                ->where('school_year_id', $validated['school_year_id'])
                ->exists();

            if ($alreadyHomeroom) {
                return back()
                    ->withInput()
                    ->withErrors(['homeroom_teacher_id' => 'Guru ini sudah menjadi wali kelas lain di tahun ajaran yang sama.']);
            }
        }

        $class = SchoolClass::create($validated);

        $this->syncWaliKelasRole(null, $class->homeroom_teacher_id);

        return redirect()
            ->route('data-master.classes.index')
            ->with('status', 'Data kelas berhasil ditambahkan.');
    }

    public function show(SchoolClass $class)
    {
        $class->load([
            'schoolYear',
            'homeroomTeacher.user',
            'students' => fn ($query) => $query->orderBy('name'),
            'teachingAssignments.subject',
            'teachingAssignments.teacher.user',
        ]);

        return view('classes.show', compact('class'));
    }

    public function edit(SchoolClass $class)
    {
        $schoolYears = SchoolYear::orderByDesc('name')->get();
        $teachers = Teacher::with('user')->get();

        return view('classes.edit', compact('class', 'schoolYears', 'teachers'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'grade_level' => ['required', 'string', 'max:20'],
            'school_year_id' => ['required', 'exists:school_years,id'],
            'homeroom_teacher_id' => ['nullable', 'exists:teachers,id'],
        ]);

        if (!empty($validated['homeroom_teacher_id'])) {
            $alreadyHomeroom = SchoolClass::where('homeroom_teacher_id', $validated['homeroom_teacher_id'])
                ->where('school_year_id', $validated['school_year_id'])
                ->where('id', '!=', $class->id)
                ->exists();

            if ($alreadyHomeroom) {
                return back()
                    ->withInput()
                    ->withErrors(['homeroom_teacher_id' => 'Guru ini sudah menjadi wali kelas lain di tahun ajaran yang sama.']);
            }
        }

        $oldTeacherId = $class->homeroom_teacher_id;

        $class->update($validated);

        $this->syncWaliKelasRole($oldTeacherId, $class->homeroom_teacher_id);

        return redirect()
            ->route('data-master.classes.index')
            ->with('status', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(SchoolClass $class)
    {
        if ($class->students()->exists()) {
            return back()->withErrors([
                'delete' => 'Kelas "' . $class->name . '" masih memiliki siswa. Pindahkan siswa ke kelas lain terlebih dahulu sebelum menghapus kelas ini.',
            ]);
        }

        $oldTeacherId = $class->homeroom_teacher_id;

   
        $class->delete();

        $this->syncWaliKelasRole($oldTeacherId, null);

        return redirect()
            ->route('data-master.classes.index')
            ->with('status', 'Data kelas berhasil dihapus.');
    }

   
    private function syncWaliKelasRole(?int $oldTeacherId, ?int $newTeacherId): void
    {
        if ($oldTeacherId && $oldTeacherId !== $newTeacherId) {
            $stillHomeroom = SchoolClass::where('homeroom_teacher_id', $oldTeacherId)->exists();

            if (!$stillHomeroom) {
                $oldTeacher = Teacher::with('user')->find($oldTeacherId);
                $oldTeacher?->user?->removeRole('wali_kelas');
            }
        }

        if ($newTeacherId) {
            $newTeacher = Teacher::with('user')->find($newTeacherId);

            if ($newTeacher?->user && !$newTeacher->user->hasRole('wali_kelas')) {
                $newTeacher->user->assignRole('wali_kelas');
            }
        }
    }
}