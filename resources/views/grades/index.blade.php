@extends('layouts.app')

@section('title', 'Rekap Nilai')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Rekap Nilai Sekolah</h4>
    </div>

    {{-- ===== Filter ===== --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('akademik.grades.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="school_year_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($schoolYears as $year)
                            <option value="{{ $year->id }}" @selected(request('school_year_id') == $year->id)>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select">
                        <option value="">Semua</option>
                        <option value="ganjil" @selected(request('semester') == 'ganjil')>Ganjil</option>
                        <option value="genap" @selected(request('semester') == 'genap')>Genap</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kelas</label>
                    <select name="class_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="subject_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(request('subject_id') == $subject->id)>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Ringkasan ===== --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Entri Nilai</h6>
                    <h3 class="mb-0">{{ $grades->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Rata-rata Nilai</h6>
                    <h3 class="mb-0">{{ $averageScore ?? '-' }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Tabel ===== --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($grades->isEmpty())
                <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                    Belum ada data nilai yang tercatat. Data akan muncul di sini setelah guru mulai menginput nilai
                    lewat menu Akademik → Input Nilai.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @else
                <div class="table-responsive">
                    <table id="grades-table" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Jenis</th>
                                <th>Semester</th>
                                <th>Tahun Ajaran</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($grades as $grade)
                                <tr>
                                    <td>{{ $grade->student->name ?? '-' }}</td>
                                    <td>{{ $grade->student->schoolClass->name ?? '-' }}</td>
                                    <td>{{ $grade->subject->name ?? '-' }}</td>
                                    <td>{{ $grade->teacher->user->name ?? '-' }}</td>
                                    <td class="text-capitalize">{{ str_replace('_', ' ', $grade->type) }}</td>
                                    <td class="text-capitalize">{{ $grade->semester }}</td>
                                    <td>{{ $grade->schoolYear->name ?? '-' }}</td>
                                    <td>
                                        <span class="fw-semibold {{ $grade->score < 70 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($grade->score, 1) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            if ($('#grades-table').length) {
                $('#grades-table').DataTable({
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        zeroRecords: "Data tidak ditemukan",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        infoEmpty: "Tidak ada data",
                        paginate: { previous: "Sebelumnya", next: "Selanjutnya" }
                    }
                });
            }
        });
    </script>
@endpush