@extends('layouts.app')

@section('title', 'Tambah Soal')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Soal — {{ $exam->title }}</h4>
        <a href="{{ route('guru.exams.show', $exam) }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('guru.exams.questions.store', $exam) }}" id="question-form">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="type" class="form-label">Jenis Soal</label>
                        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="pilihan_ganda" @selected(old('type', 'pilihan_ganda') == 'pilihan_ganda')>Pilihan Ganda</option>
                            <option value="esai" @selected(old('type') == 'esai')>Esai</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="weight" class="form-label">Bobot Nilai</label>
                        <input type="number" id="weight" name="weight" value="{{ old('weight', 10) }}" min="1" max="100"
                            class="form-control @error('weight') is-invalid @enderror" required>
                        @error('weight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="question" class="form-label">Pertanyaan</label>
                        <textarea id="question" name="question" rows="3"
                            class="form-control @error('question') is-invalid @enderror" required>{{ old('question') }}</textarea>
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="pg-fields" class="col-12">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="option_a" class="form-label">Pilihan A</label>
                                <input type="text" id="option_a" name="option_a" value="{{ old('option_a') }}"
                                    class="form-control pg-required @error('option_a') is-invalid @enderror">
                                @error('option_a')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="option_b" class="form-label">Pilihan B</label>
                                <input type="text" id="option_b" name="option_b" value="{{ old('option_b') }}"
                                    class="form-control pg-required @error('option_b') is-invalid @enderror">
                                @error('option_b')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="option_c" class="form-label">Pilihan C <span class="text-muted">(opsional)</span></label>
                                <input type="text" id="option_c" name="option_c" value="{{ old('option_c') }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="option_d" class="form-label">Pilihan D <span class="text-muted">(opsional)</span></label>
                                <input type="text" id="option_d" name="option_d" value="{{ old('option_d') }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="correct_answer" class="form-label">Jawaban Benar</label>
                                <select id="correct_answer" name="correct_answer"
                                    class="form-select pg-required @error('correct_answer') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="A" @selected(old('correct_answer') == 'A')>A</option>
                                    <option value="B" @selected(old('correct_answer') == 'B')>B</option>
                                    <option value="C" @selected(old('correct_answer') == 'C')>C</option>
                                    <option value="D" @selected(old('correct_answer') == 'D')>D</option>
                                </select>
                                @error('correct_answer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('guru.exams.show', $exam) }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Soal</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('type');
            const pgFields = document.getElementById('pg-fields');

            function toggleFields() {
                const isPG = typeSelect.value === 'pilihan_ganda';

                pgFields.classList.toggle('d-none', !isPG);

                pgFields.querySelectorAll('input, select').forEach(function (el) {
                    el.disabled = !isPG;
                });

                pgFields.querySelectorAll('.pg-required').forEach(function (el) {
                    el.required = isPG;
                });
            }

            typeSelect.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
@endpush