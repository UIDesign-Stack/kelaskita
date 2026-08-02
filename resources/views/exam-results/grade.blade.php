@extends('layouts.app')

@section('title', 'Nilai Esai')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Nilai Esai — {{ $result->student->name ?? '-' }}</h4>
            <div class="text-muted small">{{ $result->exam->title }}</div>
        </div>
        <a href="{{ route('guru.exams.results.index', $result->exam_id) }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <form method="POST" action="{{ route('guru.exams.results.store-grade', $result) }}">
        @csrf

        @foreach ($result->exam->questions as $index => $question)
            @php $answer = $result->answers->firstWhere('question_id', $question->id); @endphp
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge text-bg-secondary">Soal {{ $index + 1 }}</span>
                        <span class="badge {{ $question->type === 'pilihan_ganda' ? 'text-bg-primary' : 'text-bg-info' }}">
                            {{ $question->type === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Esai' }}
                        </span>
                        <span class="text-muted small">Bobot maks: {{ $question->weight }}</span>
                    </div>
                    <p class="mb-3">{{ $question->question }}</p>

                    @if ($question->type === 'pilihan_ganda')
                        <div class="small">
                            Jawaban siswa: <strong>{{ $answer->answer ?? '-' }}</strong> —
                            Jawaban benar: <strong>{{ $question->correct_answer }}</strong> —
                            <span class="badge {{ $answer && $answer->is_correct ? 'text-bg-success' : 'text-bg-danger' }}">
                                {{ $answer && $answer->is_correct ? 'Benar (otomatis)' : 'Salah (otomatis)' }}
                            </span>
                        </div>
                    @else
                        <div class="mb-3">
                            <div class="text-muted small mb-1">Jawaban siswa:</div>
                            <div class="border rounded p-2 bg-light">{{ $answer->answer ?? '(tidak dijawab)' }}</div>
                        </div>

                        <div class="row g-2 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label small mb-0">Nilai (0 - {{ $question->weight }})</label>
                                <input type="number" name="scores[{{ $question->id }}]" min="0" max="{{ $question->weight }}"
                                    value="{{ old('scores.' . $question->id, $answer->score ?? 0) }}"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-end mb-4">
            <button type="submit" class="btn btn-primary">Simpan Nilai</button>
        </div>
    </form>

@endsection