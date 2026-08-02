@extends('layouts.app')

@section('title', 'Review — ' . $exam->title)

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">{{ $exam->title }}</h4>
            <div class="text-muted small">
                {{ $exam->subject->name ?? '-' }} — Kelas {{ $exam->schoolClass->name ?? '-' }} —
                Guru: {{ $exam->teacher->user->name ?? '-' }} —
                {{ $exam->questions->count() }} soal — {{ $exam->duration_minutes }} menit
            </div>
        </div>
        <a href="{{ route('ujian.exam-review.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    @php
        $statuses = [
            'pending'  => ['label' => 'Menunggu Persetujuan', 'color' => 'warning'],
            'approved' => ['label' => 'Disetujui',            'color' => 'success'],
            'rejected' => ['label' => 'Ditolak',              'color' => 'danger'],
        ];
        $examStatus = $statuses[$exam->status] ?? ['label' => ucfirst($exam->status), 'color' => 'secondary'];
    @endphp

    <div class="alert alert-{{ $examStatus['color'] }}">
        Status saat ini: <strong>{{ $examStatus['label'] }}</strong>
        @if ($exam->status !== 'pending')
            — oleh {{ $exam->reviewedBy->name ?? '-' }} pada {{ $exam->reviewed_at?->translatedFormat('d M Y H:i') }}
        @endif
        @if ($exam->status === 'rejected' && $exam->rejection_reason)
            <div class="mt-1">Alasan: {{ $exam->rejection_reason }}</div>
        @endif
    </div>

    {{-- ===== Aksi Approve / Tolak (hanya kalau masih pending atau mau diubah ulang) ===== --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Keputusan</h6>
            <div class="d-flex gap-2 mb-3">
                <form action="{{ route('ujian.exam-review.approve', $exam) }}" method="POST"
                    onsubmit="return confirm('Setujui paket ujian ini?');">
                    @csrf
                    <button type="submit" class="btn btn-success" {{ $exam->status === 'approved' ? 'disabled' : '' }}>
                        ✓ Setujui
                    </button>
                </form>

                <button type="button" class="btn btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#reject-form">
                    ✕ Tolak
                </button>
            </div>

            <div class="collapse {{ $errors->has('rejection_reason') ? 'show' : '' }}" id="reject-form">
                <form action="{{ route('ujian.exam-review.reject', $exam) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3"
                            placeholder="Contoh: Soal nomor 3 jawabannya salah, soal terlalu sedikit (minimal 10), dsb."
                            class="form-control @error('rejection_reason') is-invalid @enderror" required>{{ old('rejection_reason') }}</textarea>
                        @error('rejection_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== Daftar Soal (Read-only) ===== --}}
    <h6 class="fw-semibold mb-3">Daftar Soal</h6>

    @if ($exam->questions->isEmpty())
        <div class="alert alert-warning">Belum ada soal di paket ujian ini.</div>
    @else
        @foreach ($exam->questions as $index => $question)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
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
                                        @if ($question->correct_answer === $label) ✓ @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

@endsection