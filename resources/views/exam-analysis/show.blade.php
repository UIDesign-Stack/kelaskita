@extends('layouts.app')

@section('title', 'Analisis Butir Soal')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Analisis Butir Soal — {{ $exam->title }}</h4>
            <div class="text-muted small">
                {{ $exam->subject->name ?? '-' }} — {{ $totalFinished }} siswa sudah menyelesaikan ujian
            </div>
        </div>
        <a href="{{ route('guru.exams.show', $exam) }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    @if ($totalFinished === 0)
        <div class="alert alert-warning">
            Belum ada siswa yang menyelesaikan ujian ini, jadi analisis belum bisa ditampilkan.
        </div>
    @else
        @foreach ($analysis as $index => $row)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-bg-secondary">Soal {{ $index + 1 }}</span>
                            <span class="badge {{ $row['question']->type === 'pilihan_ganda' ? 'text-bg-primary' : 'text-bg-info' }}">
                                {{ $row['question']->type === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Esai' }}
                            </span>
                            @if ($row['difficulty'])
                                @php
                                    $diffColor = match($row['difficulty']) {
                                        'Sulit' => 'danger', 'Sedang' => 'warning', 'Mudah' => 'success', default => 'secondary',
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $diffColor }}">{{ $row['difficulty'] }}</span>
                            @endif
                        </div>
                        @if ($row['percentage'] !== null)
                            <span class="fw-bold">{{ $row['percentage'] }}% benar</span>
                        @endif
                    </div>

                    <p class="mb-2">{{ $row['question']->question }}</p>

                    @if ($row['question']->type === 'pilihan_ganda')
                        @if ($row['percentage'] !== null)
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar bg-{{ $row['percentage'] < 40 ? 'danger' : ($row['percentage'] < 70 ? 'warning' : 'success') }}"
                                    style="width: {{ $row['percentage'] }}%"></div>
                            </div>

                            <div class="text-muted small mb-2">
                                {{ $row['correct_count'] }} dari {{ $row['total_answered'] }} siswa menjawab benar
                            </div>

                            <div class="row g-2 small">
                                @foreach (['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $label => $field)
                                    @if ($row['question']->$field)
                                        @php $pickedCount = $row['option_distribution'][$label] ?? 0; @endphp
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between {{ $row['question']->correct_answer === $label ? 'text-success fw-semibold' : '' }}">
                                                <span>{{ $label }}. {{ $row['question']->$field }}</span>
                                                <span>{{ $pickedCount }} dipilih</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted small">Belum ada yang menjawab soal ini.</div>
                        @endif
                    @else
                        <div class="text-muted small">
                            {{ $row['total_answered'] }} siswa sudah menjawab (soal esai, dinilai manual).
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

@endsection