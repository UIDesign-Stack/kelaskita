@extends('layouts.app')

@section('title', 'Ujian / Kuis')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Ujian / Kuis</h4>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (!$student)
        <div class="alert alert-warning mb-0">
            Data siswa kamu belum terhubung ke akun ini. Hubungi wali kelas atau admin.
        </div>
    @else
        @php
            $visibleExams = $exams->where('status', 'approved');
        @endphp

        @if ($visibleExams->isEmpty())
            <div class="alert alert-warning mb-0">
                Belum ada ujian/kuis yang tersedia untuk kelasmu saat ini.
            </div>
        @else
            <div class="row g-3">
                @foreach ($visibleExams as $exam)
                    @php
                        $result = $exam->results->firstWhere('student_id', $student->id);
                    @endphp
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge text-bg-primary text-uppercase">{{ $exam->type }}</span>
                                    @if ($result && $result->finished_at)
                                        <span class="badge text-bg-success">Selesai</span>
                                    @elseif ($result)
                                        <span class="badge text-bg-warning">Sedang Berjalan</span>
                                    @else
                                        <span class="badge text-bg-secondary">Belum Dikerjakan</span>
                                    @endif
                                </div>
                                <h6 class="fw-semibold mb-1">{{ $exam->title }}</h6>
                                <div class="text-muted small mb-3">
                                    {{ $exam->subject->name ?? '-' }} —
                                    {{ $exam->questions_count }} soal —
                                    {{ $exam->duration_minutes }} menit
                                </div>

                                @if ($result && $result->finished_at)
                                    <a href="{{ route('siswa.exams.result', $result) }}" class="btn btn-outline-primary btn-sm">
                                        Lihat Hasil
                                    </a>
                                @elseif ($result)
                                    <a href="{{ route('siswa.exams.take', $result) }}" class="btn btn-warning btn-sm">
                                        Lanjutkan Mengerjakan
                                    </a>
                                @else
                                    <form action="{{ route('siswa.exams.start', $exam) }}" method="POST"
                                        onsubmit="return confirm('Timer akan mulai berjalan begitu kamu klik OK. Lanjutkan?');">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Mulai Kerjakan</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

@endsection