<?php

namespace App\Http\Controllers;

use App\Models\CommunicationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalasCommunicationController extends Controller
{
    public function index()
    {
        $class = $this->homeroomClass();

        $logs = collect();

        if ($class) {
            $logs = CommunicationLog::with('student')
                ->whereHas('student', fn ($q) => $q->where('class_id', $class->id))
                ->latest('date')
                ->get();
        }

        return view('walas-communication-logs.index', compact('class', 'logs'));
    }

    public function create()
    {
        $class = $this->homeroomClass();

        $students = $class ? $class->students()->orderBy('name')->get() : collect();

        return view('walas-communication-logs.create', compact('class', 'students'));
    }

    public function store(Request $request)
    {
        $class = $this->homeroomClass();

        abort_if(!$class, 403, 'Anda bukan wali kelas manapun.');

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'date' => ['required', 'date'],
            'teacher_note' => ['required', 'string', 'max:1000'],
        ]);

        abort_unless($class->students()->where('id', $validated['student_id'])->exists(), 403);

        CommunicationLog::create([
            ...$validated,
            'recorded_by' => Auth::id(),
        ]);

        return redirect()
            ->route('wali-kelas.communication-logs.index')
            ->with('status', 'Catatan buku penghubung berhasil dikirim.');
    }

    private function homeroomClass()
    {
        return Auth::user()->teacher?->activeHomeroomClass();
    }
}