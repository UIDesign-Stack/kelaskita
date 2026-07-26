<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = TeacherAttendance::with('teacher.user');

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest('date')->get();

        $summary = [
            'hadir' => $records->where('status', 'hadir')->count(),
            'izin' => $records->where('status', 'izin')->count(),
            'sakit' => $records->where('status', 'sakit')->count(),
            'alpa' => $records->where('status', 'alpa')->count(),
        ];

        return view('teacher-attendances.index', compact('records', 'summary'));
    }

    public function create(Request $request)
    {
        $date = $request->query('date', now()->format('Y-m-d'));

        $teachers = Teacher::with('user')->orderBy('id')->get();

        $existing = TeacherAttendance::whereDate('date', $date)->pluck('status', 'teacher_id');

        return view('teacher-attendances.create', compact('teachers', 'date', 'existing'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'status' => ['required', 'array'],
            'status.*' => ['required', 'in:hadir,izin,sakit,alpa'],
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['status'] as $teacherId => $status) {
                TeacherAttendance::updateOrCreate(
                    ['teacher_id' => $teacherId, 'date' => $validated['date']],
                    ['status' => $status, 'recorded_by' => Auth::id()]
                );
            }
        });

        return redirect()
            ->route('presensi.teacher-attendances.create', ['date' => $validated['date']])
            ->with('status', 'Absensi guru tanggal ' . $validated['date'] . ' berhasil disimpan.');
    }
}