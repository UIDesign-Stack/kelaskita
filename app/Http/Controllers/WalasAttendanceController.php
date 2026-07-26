<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
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

    public function store(Request $request)
    {
        $class = $this->homeroomClass();

        abort_if(!$class, 403, 'Anda bukan wali kelas manapun.');

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'status' => ['required', 'array'],
            'status.*' => ['required', 'in:hadir,izin,sakit,alpa'],
        ]);

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

            $recap = $students->map(function ($student) use ($dateFrom, $dateTo) {
                $counts = Attendance::where('student_id', $student->id)
                    ->whereNull('subject_id')
                    ->whereDate('date', '>=', $dateFrom)
                    ->whereDate('date', '<=', $dateTo)
                    ->selectRaw('status, count(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status');

                return [
                    'student' => $student,
                    'hadir' => $counts['hadir'] ?? 0,
                    'izin' => $counts['izin'] ?? 0,
                    'sakit' => $counts['sakit'] ?? 0,
                    'alpa' => $counts['alpa'] ?? 0,
                ];
            });
        }

        return view('walas-attendance.recap', compact('class', 'recap', 'dateFrom', 'dateTo'));
    }

    private function homeroomClass()
    {
        $teacher = Auth::user()->teacher;

        return $teacher ? $teacher->homeroomClasses()->with('students')->first() : null;
    }
}