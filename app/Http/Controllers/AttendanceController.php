<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();

        $query = Attendance::with(['student', 'schoolClass']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->latest('date')->get();

        $summary = [
            'hadir' => $attendances->where('status', 'hadir')->count(),
            'izin' => $attendances->where('status', 'izin')->count(),
            'sakit' => $attendances->where('status', 'sakit')->count(),
            'alpa' => $attendances->where('status', 'alpa')->count(),
        ];

        return view('attendances.index', compact('classes', 'attendances', 'summary'));
    }
}