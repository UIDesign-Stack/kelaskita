@extends('layouts.app')

@section('title', 'Detail Guru')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Detail Guru</h4>
        <a href="{{ route('data-master.teachers.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="row g-3">
        {{-- ===== Kolom Kiri: Foto & Info Utama ===== --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <img src="{{ $teacher->photoUrl() }}" alt="{{ $teacher->user->name ?? '-' }}"
                        class="rounded-circle mb-3" width="110" height="110" style="object-fit: cover;">

                    <h5 class="mb-1">{{ $teacher->user->name ?? '-' }}</h5>
                    <div class="text-muted small mb-2">{{ $teacher->specialization ?? 'Belum ada spesialisasi' }}</div>

                    @if ($teacher->homeroomClasses->isNotEmpty())
                        @foreach ($teacher->homeroomClasses as $class)
                            <span class="badge text-bg-primary">Wali Kelas {{ $class->name }}</span>
                        @endforeach
                    @else
                        <span class="badge text-bg-secondary">Bukan Wali Kelas</span>
                    @endif

                    <hr>

                    <ul class="list-unstyled text-start small mb-0">
                        <li class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">NUPTK</span>
                            <span class="fw-semibold">{{ $teacher->nuptk ?? '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">Email</span>
                            <span class="fw-semibold">{{ $teacher->user->email ?? '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-1">
                            <span class="text-muted">Status Akun</span>
                            @if ($teacher->user && $teacher->user->is_active)
                                <span class="badge text-bg-success">Aktif</span>
                            @else
                                <span class="badge text-bg-secondary">Nonaktif</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- ===== Kolom Kanan: Kelas & Mapel Diampu ===== --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Mata Pelajaran & Kelas Diampu</h6>

                    @if ($teacher->teachingAssignments->isEmpty())
                        <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                            Guru ini belum ditugaskan mengajar di kelas/mata pelajaran manapun.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Tahun Ajaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teacher->teachingAssignments as $assignment)
                                        <tr>
                                            <td>{{ $assignment->subject->name ?? '-' }}</td>
                                            <td>{{ $assignment->schoolClass->name ?? '-' }}</td>
                                            <td>{{ $assignment->schoolYear->name ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Riwayat Wali Kelas</h6>

                    @if ($teacher->homeroomClasses->isEmpty())
                        <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                            Guru ini belum pernah / sedang tidak menjadi wali kelas.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Kelas</th>
                                        <th>Tahun Ajaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teacher->homeroomClasses as $class)
                                        <tr>
                                            <td>{{ $class->name }}</td>
                                            <td>{{ $class->schoolYear->name ?? '-' }}</td>
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