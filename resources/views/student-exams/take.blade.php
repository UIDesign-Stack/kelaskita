@extends('layouts.app')

@section('title', 'Mengerjakan Ujian')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">{{ $result->exam->title }}</h4>
            <div class="text-muted small">{{ $result->exam->subject->name ?? '-' }}</div>
        </div>
        <div class="card border-danger">
            <div class="card-body py-2 px-3 text-center">
                <div class="small text-muted">Sisa Waktu</div>
                <div class="fs-5 fw-bold text-danger" id="timer">--:--</div>
            </div>
        </div>
    </div>

    <div class="alert alert-info small">
        Jangan tutup atau refresh halaman ini. Jawaban akan otomatis dikumpulkan saat waktu habis.
    </div>

    <form method="POST" action="{{ route('siswa.exams.submit', $result) }}" id="exam-form">
        @csrf

        @foreach ($result->exam->questions as $index => $question)
            @php
                $currentAnswer = old('answers.' . $question->id, $existingAnswers[$question->id] ?? null);
            @endphp
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge text-bg-secondary">Soal {{ $index + 1 }}</span>
                        <span class="text-muted small">Bobot: {{ $question->weight }}</span>
                    </div>
                    <p class="mb-3">{{ $question->question }}</p>

                    @if ($question->type === 'pilihan_ganda')
                        @foreach (['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $label => $field)
                            @if ($question->$field)
                                <div class="form-check mb-2">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $label }}"
                                        id="q{{ $question->id }}-{{ $label }}" class="form-check-input"
                                        {{ $currentAnswer === $label ? 'checked' : '' }}>
                                    <label class="form-check-label" for="q{{ $question->id }}-{{ $label }}">
                                        {{ $label }}. {{ $question->$field }}
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <textarea name="answers[{{ $question->id }}]" rows="4" class="form-control"
                            placeholder="Tulis jawabanmu di sini...">{{ $currentAnswer }}</textarea>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-end mb-4">
            <button type="submit" class="btn btn-primary" id="submit-btn"
                onclick="return confirm('Kumpulkan jawaban sekarang? Jawaban tidak bisa diubah lagi setelah dikumpulkan.');">
                Kumpulkan Jawaban
            </button>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        (function () {
            const deadline = new Date('{{ $deadline->toIso8601String() }}').getTime();
            const timerEl = document.getElementById('timer');
            const form = document.getElementById('exam-form');
            let autoSubmitted = false;

            function updateTimer() {
                const now = new Date().getTime();
                const remaining = deadline - now;

                if (remaining <= 0) {
                    timerEl.textContent = '00:00';
                    if (!autoSubmitted) {
                        autoSubmitted = true;
                        alert('Waktu habis! Jawaban akan otomatis dikumpulkan.');
                        form.submit();
                    }
                    return;
                }

                const minutes = Math.floor(remaining / 60000);
                const seconds = Math.floor((remaining % 60000) / 1000);
                timerEl.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
            }

            updateTimer();
            setInterval(updateTimer, 1000);
        })();
    </script>
@endpush