<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::withCount('classSubjectTeachers')->latest()->get();

        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', Rule::unique('subjects', 'code')],
        ]);

        Subject::create($validated);

        return redirect()
            ->route('data-master.subjects.index')
            ->with('status', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', Rule::unique('subjects', 'code')->ignore($subject->id)],
        ]);

        $subject->update($validated);

        return redirect()
            ->route('data-master.subjects.index')
            ->with('status', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->classSubjectTeachers()->exists()) {
            return back()->withErrors([
                'delete' => 'Mata pelajaran "' . $subject->name . '" masih dipakai di jadwal pengajaran. Hapus penugasan guru untuk mapel ini terlebih dahulu.',
            ]);
        }

        $subject->delete();

        return redirect()
            ->route('data-master.subjects.index')
            ->with('status', 'Mata pelajaran berhasil dihapus.');
    }
}