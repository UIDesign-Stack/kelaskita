<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassSubjectTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruAttendanceController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        $assignments = $teacher
            ? $teacher->teachingAssignments()->with(['schoolClass', 'subject'])->get()
            : collect();

        return view('guru-attendance.index', compact('assignments'));
    }

    public function create(ClassSubjectTeacher $assignment, Request $request)
    {
        $this->authorizeAssignment($assignment);

        $assignment->load(['schoolClass.students' => fn ($q) => $q->orderBy('name'), 'subject']);

        $date = $request->query('date', now()->format('Y-m-d'));

        $existing = Attendance::where('class_id', $assignment->class_id)
            ->where('subject_id', $assignment->subject_id)
            ->whereDate('date', $date)
            ->pluck('status', 'student_id');

        return view('guru-attendance.create', compact('assignment', 'date', 'existing'));
    }

    public function store(ClassSubjectTeacher $assignment, Request $request)
    {
        $this->authorizeAssignment($assignment);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'status' => ['required', 'array'],
            'status.*' => ['required', 'in:hadir,izin,sakit,alpa'],
        ]);

        DB::transaction(function () use ($validated, $assignment) {
            foreach ($validated['status'] as $studentId => $status) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $assignment->class_id,
                        'subject_id' => $assignment->subject_id,
                        'date' => $validated['date'],
                    ],
                    [
                        'status' => $status,
                        'recorded_by' => Auth::id(),
                    ]
                );
            }
        });

        return redirect()
            ->route('guru.attendance.create', ['assignment' => $assignment->id, 'date' => $validated['date']])
            ->with('status', 'Presensi berhasil disimpan.');
    }

    private function authorizeAssignment(ClassSubjectTeacher $assignment): void
    {
        $teacher = Auth::user()->teacher;

        abort_if(!$teacher || $assignment->teacher_id !== $teacher->id, 403, 'Anda tidak memiliki akses ke kelas/mapel ini.');
    }
}