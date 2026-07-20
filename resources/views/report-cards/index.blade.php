@extends('layouts.app')

@section('title', 'Rapor Digital')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Rapor Digital</h4>
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('akademik.report-cards.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Kelas</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="ganjil" @selected(request('semester') == 'ganjil')>Ganjil</option>
                        <option value="genap" @selected(request('semester') == 'genap')>Genap</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="school_year_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach ($schoolYears as $year)
                            <option value="{{ $year->id }}" @selected(request('school_year_id') == $year->id)>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    @if ($students->isNotEmpty())
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Status Rapor</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $student->nis }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>
                                        @if (!$student->reportCard)
                                            <span class="badge text-bg-secondary">Belum Dibuat</span>
                                        @elseif ($student->reportCard->status === 'final')
                                            <span class="badge text-bg-success">Final</span>
                                        @else
                                            <span class="badge text-bg-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('akademik.report-cards.show', ['student' => $student->id, 'semester' => request('semester'), 'school_year_id' => request('school_year_id')]) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            Lihat / Buat Rapor
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif (request()->has('class_id'))
        <div class="alert alert-warning">Tidak ada siswa di kelas ini.</div>
    @endif

@endsection