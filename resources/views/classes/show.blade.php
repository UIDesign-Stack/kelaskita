@extends('layouts.app')

@section('title', 'Detail Kelas')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Detail Kelas — {{ $class->name }}</h4>
        <a href="{{ route('data-master.classes.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="row g-3">
        {{-- ===== Kolom Kiri: Info Kelas ===== --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-1">{{ $class->name }}</h5>
                    <div class="text-muted small mb-3">Tingkat {{ $class->grade_level }}</div>

                    <ul class="list-unstyled small mb-0">
                        <li class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">Wali Kelas</span>
                            <span class="fw-semibold">{{ $class->homeroomTeacher->user->name ?? 'Belum ditentukan' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">Tahun Ajaran</span>
                            <span class="fw-semibold">{{ $class->schoolYear->name ?? '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">Semester</span>
                            <span class="fw-semibold text-capitalize">{{ $class->schoolYear->semester ?? '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-1">
                            <span class="text-muted">Jumlah Siswa</span>
                            <span class="fw-semibold">{{ $class->students->count() }} siswa</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Mata Pelajaran Diajarkan</h6>
 
                    @if ($errors->has('subject_id'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first('subject_id') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
 
                    @if ($class->teachingAssignments->isEmpty())
                        <div class="alert alert-warning mb-3">
                            Belum ada guru mata pelajaran yang ditugaskan di kelas ini.
                        </div>
                    @else
                        <ul class="list-unstyled small mb-3">
                            @foreach ($class->teachingAssignments as $assignment)
                                <li class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                    <span>
                                        {{ $assignment->subject->name ?? '-' }}
                                        <span class="text-muted"> — {{ $assignment->teacher->user->name ?? '-' }}</span>
                                    </span>
                                    <form action="{{ route('data-master.teaching-assignments.destroy', $assignment) }}"
                                        method="POST" onsubmit="return confirm('Hapus guru pengampu ini dari kelas?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1">×</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
 
                    <form action="{{ route('data-master.classes.teaching-assignments.store', $class) }}" method="POST">
                        @csrf
                        <div class="row g-2">
                            <div class="col-12">
                                <select name="subject_id" class="form-select form-select-sm" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <select name="teacher_id" class="form-select form-select-sm" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->user->name ?? '-' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-sm btn-primary w-100">+ Tambah Guru Pengampu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===== Kolom Kanan: Daftar Siswa ===== --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Daftar Siswa di Kelas Ini</h6>

                    @if ($class->students->isEmpty())
                        <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                            Belum ada siswa yang terdaftar di kelas ini.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($class->students as $student)
                                        <tr>
                                            <td>{{ $student->nis }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                            <td>
                                                <span class="badge text-bg-secondary text-capitalize">{{ $student->status }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('data-master.students.show', $student) }}"
                                                    class="btn btn-sm btn-outline-secondary">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection