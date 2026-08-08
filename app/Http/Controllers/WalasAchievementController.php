<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalasAchievementController extends Controller
{
    public function index()
    {
        $class = $this->homeroomClass();

        $achievements = collect();

        if ($class) {
            $achievements = Achievement::with('student')
                ->whereIn('student_id', $class->students()->pluck('id'))
                ->latest('date')
                ->get();
        }

        return view('walas-achievements.index', compact('class', 'achievements'));
    }

    public function create()
    {
        $class = $this->homeroomClass();

        $students = $class ? $class->students()->orderBy('name')->get() : collect();

        return view('walas-achievements.create', compact('class', 'students'));
    }

    public function store(Request $request)
    {
        $class = $this->homeroomClass();

        abort_if(!$class, 403, 'Anda bukan wali kelas manapun.');

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'title' => ['required', 'string', 'max:255'],
            'level' => ['required', 'in:sekolah,kecamatan,kabupaten,provinsi,nasional,internasional'],
            'date' => ['required', 'date'],
        ]);

        abort_unless($class->students()->where('id', $validated['student_id'])->exists(), 403);

        Achievement::create([
            ...$validated,
            'recorded_by' => Auth::id(),
        ]);

        return redirect()
            ->route('wali-kelas.achievements.index')
            ->with('status', 'Catatan prestasi berhasil ditambahkan.');
    }

    public function destroy(Achievement $achievement)
    {
        $class = $this->homeroomClass();

        abort_unless($class && $class->students()->where('id', $achievement->student_id)->exists(), 403);

        $achievement->delete();

        return redirect()
            ->route('wali-kelas.achievements.index')
            ->with('status', 'Catatan prestasi berhasil dihapus.');
    }

    private function homeroomClass()
    {
        $teacher = Auth::user()->teacher;

        return $teacher ? $teacher->homeroomClasses()->first() : null;
    }
}