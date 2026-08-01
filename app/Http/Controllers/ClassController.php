<?php

namespace App\Http\Controllers;


use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\SchoolYear;
use App\Services\WaliKelasRoleService;;
use App\Http\Requests\StoreSchoolClassRequest;
use App\Http\Requests\UpdateSchoolClassRequest;

class ClassController extends Controller
{

    public function __construct(private WaliKelasRoleService $waliKelasRoleService)
    {
    }

    public function index()
    {
        $classes = SchoolClass::with(['schoolYear', 'homeroomTeacher.user'])
            ->withCount('students')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        $schoolYears = SchoolYear::orderByDesc('name')->get();
        $teachers = Teacher::with('user')->get();

        return view('classes.create', compact('schoolYears', 'teachers'));
    }

    public function store(StoreSchoolClassRequest $request)
    {
        $class = SchoolClass::create($request->validated());

        $this->waliKelasRoleService->sync(null, $class->homeroom_teacher_id);

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

    public function update(UpdateSchoolClassRequest $request, SchoolClass $class)
    {
        $oldTeacherId = $class->homeroom_teacher_id;

        $class->update($request->validated());

        $this->waliKelasRoleService->sync($oldTeacherId, $class->homeroom_teacher_id);

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

        $this->waliKelasRoleService->sync($oldTeacherId, null);

        return redirect()
            ->route('data-master.classes.index')
            ->with('status', 'Data kelas berhasil dihapus.');
    }
}
