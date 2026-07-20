<?php
 
namespace App\Http\Controllers;
 
use App\Models\SchoolClass;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
 
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
 
        SchoolClass::create($validated);
 
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
 
        $subjects = \App\Models\Subject::orderBy('name')->get();
        $teachers = \App\Models\Teacher::with('user')->get();
 
        return view('classes.show', compact('class', 'subjects', 'teachers'));
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
 
        $class->update($validated);
 
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
 
        $class->delete();
 
        return redirect()
            ->route('data-master.classes.index')
            ->with('status', 'Data kelas berhasil dihapus.');
    }
}
 