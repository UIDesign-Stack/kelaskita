@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Input Nilai — {{ $assignment->subject->name ?? '-' }}</h4>
            <div class="text-muted small">
                Kelas {{ $assignment->schoolClass->name ?? '-' }} — {{ $assignment->schoolYear->name ?? '-' }}
            </div>
        </div>
        <a href="{{ route('guru.grade-input.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ===== Filter Semester & Jenis Nilai ===== --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('guru.grade-input.create', $assignment) }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select">
                        <option value="ganjil" @selected($semester == 'ganjil')>Ganjil</option>
                        <option value="genap" @selected($semester == 'genap')>Genap</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Nilai</label>
                    <select name="type" class="form-select">
                        <option value="tugas" @selected($type == 'tugas')>Tugas</option>
                        <option value="ulangan_harian" @selected($type == 'ulangan_harian')>Ulangan Harian</option>
                        <option value="uts" @selected($type == 'uts')>UTS</option>
                        <option value="uas" @selected($type == 'uas')>UAS</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Form Input Nilai Massal ===== --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($assignment->schoolClass->students->isEmpty())
                <div class="alert alert-warning mb-0">
                    Belum ada siswa terdaftar di kelas ini.
                </div>
            @else
                <form method="POST" action="{{ route('guru.grade-input.store', $assignment) }}">
                    @csrf
                    <input type="hidden" name="semester" value="{{ $semester }}">
                    <input type="hidden" name="type" value="{{ $type }}">

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th style="width: 150px;">Nilai (0-100)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignment->schoolClass->students as $student)
                                    <tr>
                                        <td>{{ $student->nis }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>
                                            <input type="number" name="scores[{{ $student->id }}]" min="0" max="100"
                                                step="0.1" class="form-control form-control-sm"
                                                value="{{ old('scores.' . $student->id, $existingGrades[$student->id] ?? '') }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

@endsection