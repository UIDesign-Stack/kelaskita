@extends('layouts.app')

@section('title', 'Hasil Ujian')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Hasil Ujian — {{ $result->exam->title }}</h4>
        <a href="{{ route('siswa.exams.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (!$result->finished_at)
        <div class="alert alert-warning">
            Ujian ini belum kamu selesaikan. Kembali ke halaman ujian untuk melanjutkan mengerjakan.
        </div>
        <a href="{{ route('siswa.exams.take', $result) }}" class="btn btn-primary">Lanjutkan Mengerjakan</a>
        @php return; @endphp
    @endif

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body text-center py-5">
            @if ($result->score !== null)
                <h6 class="text-muted mb-2">Nilai Kamu</h6>
                <h1 class="display-4 fw-bold {{ $result->score >= ($result->exam->subject->kkm ?? 70) ? 'text-success' : 'text-danger' }}">
                    {{ number_format($result->score, 1) }}
                </h1>
            @elseif ($result->hasEssayPending())
                <h6 class="text-muted mb-2">Status</h6>
                <h4 class="text-warning">Menunggu Penilaian Guru</h4>
                <p class="text-muted small mb-0">Ujian ini ada soal esai yang perlu dinilai manual oleh guru.</p>
            @else
                <h6 class="text-muted mb-0">Belum ada nilai.</h6>
            @endif
        </div>
    </div>

    <div class="text-muted small mb-3">
        Dikerjakan: {{ $result->started_at?->translatedFormat('d M Y H:i') ?? '-' }} —
        Dikumpulkan: {{ $result->finished_at?->translatedFormat('d M Y H:i') ?? '-' }}
    </div>

    <h6 class="fw-semibold mb-3">Review Jawaban</h6>

    @foreach ($result->exam->questions as $index => $question)
        @php $answer = $result->answers->firstWhere('question_id', $question->id); @endphp
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge text-bg-secondary">Soal {{ $index + 1 }}</span>
                    @if ($question->type === 'pilihan_ganda' && $answer)
                        <span class="badge {{ $answer->is_correct ? 'text-bg-success' : 'text-bg-danger' }}">
                            {{ $answer->is_correct ? 'Benar' : 'Salah' }}
                        </span>
                    @endif
                </div>
                <p class="mb-2">{{ $question->question }}</p>

                @if ($question->type === 'pilihan_ganda')
                    <div class="small">
                        Jawaban kamu: <strong>{{ $answer?->answer ?? '-' }}</strong> —
                        Jawaban benar: <strong class="text-success">{{ $question->correct_answer }}</strong>
                    </div>
                @else
                    <div class="small">
                        <div class="text-muted mb-1">Jawaban kamu:</div>
                        <div class="border rounded p-2 bg-light">{{ $answer?->answer ?? '-' }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach

@endsection