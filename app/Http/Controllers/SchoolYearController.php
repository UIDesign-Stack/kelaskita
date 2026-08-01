<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolYearRequest;
use App\Http\Requests\UpdateSchoolYearRequest;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;

class SchoolYearController extends Controller
{
    public function index()
    {
        $schoolYears = SchoolYear::withCount('classes')->orderByDesc('name')->get();

        return view('school-years.index', compact('schoolYears'));
    }

    public function create()
    {
        return view('school-years.create');
    }

    public function store(StoreSchoolYearRequest $request)
    {
        DB::transaction(function () use ($request) {
            if ($request->boolean('is_active')) {
                SchoolYear::deactivateOthersExcept();
            }

            SchoolYear::create($request->toModelData());
        });

        return redirect()
            ->route('data-master.school-years.index')
            ->with('status', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(SchoolYear $schoolYear)
    {
        return view('school-years.edit', compact('schoolYear'));
    }

    public function update(UpdateSchoolYearRequest $request, SchoolYear $schoolYear)
    {
        DB::transaction(function () use ($request, $schoolYear) {
            if ($request->boolean('is_active')) {
                SchoolYear::deactivateOthersExcept($schoolYear->id);
            }

            $schoolYear->update($request->toModelData());
        });

        return redirect()
            ->route('data-master.school-years.index')
            ->with('status', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(SchoolYear $schoolYear)
    {
        if ($schoolYear->classes()->exists()) {
            return back()->withErrors([
                'delete' => 'Tahun ajaran "' . $schoolYear->name . '" masih memiliki data kelas. Hapus atau pindahkan kelas-kelas tersebut terlebih dahulu (menghapus tahun ajaran akan ikut menghapus semua kelas di dalamnya).',
            ]);
        }

        $schoolYear->delete();

        return redirect()
            ->route('data-master.school-years.index')
            ->with('status', 'Tahun ajaran berhasil dihapus.');
    }
}