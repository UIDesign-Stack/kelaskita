<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubstituteTeacherRequest;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\SubstituteTeacher;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;

class SubstituteTeacherController extends Controller
{
    public function index()
    {
        $logs = SubstituteTeacher::with(['originalTeacher.user', 'substituteTeacher.user', 'schoolClass', 'subject'])
            ->latest('date')
            ->paginate(20)
            ->withQueryString();

        return view('substitute-teachers.index', compact('logs'));
    }

    public function create()
    {
        $teachers = Teacher::with('user')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('substitute-teachers.create', compact('teachers', 'classes', 'subjects'));
    }

    public function store(StoreSubstituteTeacherRequest $request)
    {
        SubstituteTeacher::create([
            ...$request->validated(),
            'recorded_by' => Auth::id(),
        ]);

        return redirect()
            ->route('presensi.substitute-teachers.index')
            ->with('status', 'Log guru pengganti berhasil dicatat.');
    }

    public function destroy(SubstituteTeacher $substituteTeacher)
    {
        $substituteTeacher->delete();

        return redirect()
            ->route('presensi.substitute-teachers.index')
            ->with('status', 'Log guru pengganti berhasil dihapus.');
    }
}