<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherAttendanceRequest;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = TeacherAttendance::query()
            ->when($request->filled('date_from'), fn ($q) => $q->whereDate('date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($q) => $q->whereDate('date', '<=', $request->date_to));

        $summary = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $summary = [
            'hadir' => $summary['hadir'] ?? 0,
            'izin' => $summary['izin'] ?? 0,
            'sakit' => $summary['sakit'] ?? 0,
            'alpa' => $summary['alpa'] ?? 0,
        ];

        $records = (clone $baseQuery)
            ->with('teacher.user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest('date')
            ->paginate(20)
            ->withQueryString();

        return view('teacher-attendances.index', compact('records', 'summary'));
    }

    public function create(Request $request)
    {
        $date = $request->query('date', now()->format('Y-m-d'));

        $teachers = Teacher::with('user')->orderBy('id')->get();

        $existing = TeacherAttendance::whereDate('date', $date)->pluck('status', 'teacher_id');

        return view('teacher-attendances.create', compact('teachers', 'date', 'existing'));
    }

    public function store(StoreTeacherAttendanceRequest $request)
    {
        $validated = $request->validated();

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