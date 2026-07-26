@extends('layouts.app')

@section('title', $exam->title)

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">{{ $exam->title }}</h4>
            <div class="text-muted small">
                {{ $exam->subject->name ?? '-' }} — Kelas {{ $exam->schoolClass->name ?? '-' }} —
                {{ $exam->duration_minutes }} menit — {{ $exam->questions->count() }} soal
            </div>
        </div>
        <a href="{{ route('guru.exams.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('guru.exams.questions.create', $exam) }}" class="btn btn-primary btn-sm">+ Tambah Soal</a>
    </div>

    @if ($exam->questions->isEmpty())
        <div class="alert alert-warning">Belum ada soal di paket ujian ini.</div>
    @else
        @foreach ($exam->questions as $index => $question)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge text-bg-secondary">Soal {{ $index + 1 }}</span>
                                <span class="badge {{ $question->type === 'pilihan_ganda' ? 'text-bg-primary' : 'text-bg-info' }}">
                                    {{ $question->type === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Esai' }}
                                </span>
                                <span class="text-muted small">Bobot: {{ $question->weight }}</span>
                            </div>
                            <p class="mb-2">{{ $question->question }}</p>

                            @if ($question->type === 'pilihan_ganda')
                                <ul class="list-unstyled small mb-0">
                                    @foreach (['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $label => $field)
                                        @if ($question->$field)
                                            <li class="{{ $question->correct_answer === $label ? 'text-success fw-semibold' : '' }}">
                                                {{ $label }}. {{ $question->$field }}
                                                @if ($question->correct_answer === $label)
                                                    <i class="bi bi-check-circle-fill"></i>
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <form action="{{ route('guru.exams.questions.destroy', [$exam, $question]) }}" method="POST"
                            onsubmit="return confirm('Hapus soal ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

@endsection