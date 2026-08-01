<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Subject;

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

    public function store(StoreSubjectRequest $request)
    {
        Subject::create($request->validated());

        return redirect()
            ->route('data-master.subjects.index')
            ->with('status', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $subject->update($request->validated());

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