<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalasViolationController extends Controller
{
    public function index()
    {
        $class = $this->homeroomClass();

        $violations = collect();

        if ($class) {
            $violations = Violation::with('student')
                ->whereHas('student', fn ($q) => $q->where('class_id', $class->id))
                ->latest('date')
                ->get();
        }

        return view('walas-violations.index', compact('class', 'violations'));
    }

    public function create()
    {
        $class = $this->homeroomClass();

        $students = $class ? $class->students()->orderBy('name')->get() : collect();

        return view('walas-violations.create', compact('class', 'students'));
    }

    public function store(Request $request)
    {
        $class = $this->homeroomClass();

        abort_if(!$class, 403, 'Anda bukan wali kelas manapun.');

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'description' => ['required', 'string', 'max:255'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'date' => ['required', 'date'],
        ]);

        abort_unless($class->students()->where('id', $validated['student_id'])->exists(), 403);

        Violation::create([
            ...$validated,
            'recorded_by' => Auth::id(),
        ]);

        return redirect()
            ->route('wali-kelas.violations.index')
            ->with('status', 'Catatan pelanggaran berhasil ditambahkan.');
    }

    public function destroy(Violation $violation)
    {
        $class = $this->homeroomClass();

        abort_unless($class && $class->students()->where('id', $violation->student_id)->exists(), 403);

        $violation->delete();

        return redirect()
            ->route('wali-kelas.violations.index')
            ->with('status', 'Catatan pelanggaran berhasil dihapus.');
    }

    private function homeroomClass()
    {
        return Auth::user()->teacher?->activeHomeroomClass();
    }
}