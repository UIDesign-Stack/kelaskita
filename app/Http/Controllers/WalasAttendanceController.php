<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWalasAttendanceRequest;
use App\Models\Attendance;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalasAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $class = $this->homeroomClass();

        $date = $request->query('date', now()->format('Y-m-d'));

        $students = collect();
        $existing = collect();

        if ($class) {
            $students = $class->students()->orderBy('name')->get();

            $existing = Attendance::where('class_id', $class->id)
                ->whereNull('subject_id')
                ->whereDate('date', $date)
                ->pluck('status', 'student_id');
        }

        return view('walas-attendance.index', compact('class', 'students', 'existing', 'date'));
    }

    public function store(StoreWalasAttendanceRequest $request)
    {
        $class = $request->resolveHomeroomClass();
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $class) {
            foreach ($validated['status'] as $studentId => $status) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $class->id,
                        'subject_id' => null,
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
            ->route('wali-kelas.attendance.index', ['date' => $validated['date']])
            ->with('status', 'Presensi tanggal ' . $validated['date'] . ' berhasil disimpan.');
    }

    public function recap(Request $request)
    {
        $class = $this->homeroomClass();

        $dateFrom = $request->query('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->query('date_to', now()->format('Y-m-d'));

        $recap = collect();

        if ($class) {
            $students = $class->students()->orderBy('name')->get();

            $counts = Attendance::whereIn('student_id', $students->pluck('id'))
                ->whereNull('subject_id')
                ->whereDate('date', '>=', $dateFrom)
                ->whereDate('date', '<=', $dateTo)
                ->selectRaw('student_id, status, count(*) as total')
                ->groupBy('student_id', 'status')
                ->get()
                ->groupBy('student_id');

            $recap = $students->map(function ($student) use ($counts) {
                $studentCounts = $counts->get($student->id, collect())->pluck('total', 'status');

                return [
                    'student' => $student,
                    'hadir' => $studentCounts['hadir'] ?? 0,
                    'izin' => $studentCounts['izin'] ?? 0,
                    'sakit' => $studentCounts['sakit'] ?? 0,
                    'alpa' => $studentCounts['alpa'] ?? 0,
                ];
            });
        }

        return view('walas-attendance.recap', compact('class', 'recap', 'dateFrom', 'dateTo'));
    }

    private function homeroomClass(): ?SchoolClass
    {
        $teacher = Auth::user()->teacher;

        if (! $teacher) {
            return null;
        }

        return $teacher->homeroomClasses()
            ->whereHas('schoolYear', fn ($q) => $q->where('is_active', true))
            ->with('students')
            ->first();
    }
}