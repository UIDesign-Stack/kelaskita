<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'in:ganjil,genap'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            if ($request->boolean('is_active')) {
                // Pastikan cuma 1 tahun ajaran yang aktif dalam satu waktu
                SchoolYear::where('is_active', true)->update(['is_active' => false]);
            }

            SchoolYear::create([
                'name' => $validated['name'],
                'semester' => $validated['semester'],
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        return redirect()
            ->route('data-master.school-years.index')
            ->with('status', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(SchoolYear $schoolYear)
    {
        return view('school-years.edit', compact('schoolYear'));
    }

    public function update(Request $request, SchoolYear $schoolYear)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'in:ganjil,genap'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $request, $schoolYear) {
            if ($request->boolean('is_active')) {
                SchoolYear::where('id', '!=', $schoolYear->id)->where('is_active', true)->update(['is_active' => false]);
            }

            $schoolYear->update([
                'name' => $validated['name'],
                'semester' => $validated['semester'],
                'is_active' => $request->boolean('is_active'),
            ]);
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