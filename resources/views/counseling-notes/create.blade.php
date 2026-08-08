@extends('layouts.app')

@section('title', 'Tambah Catatan BK')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Catatan BK</h4>
        <a href="{{ route('perilaku.counseling-notes.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @php
                $hasPreviousSubmission = count(old()) > 0;
                $isConfidentialChecked = $hasPreviousSubmission ? old('is_confidential') : true;
            @endphp

            <form method="POST" action="{{ route('perilaku.counseling-notes.store') }}">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="student_id" class="form-label">Siswa</label>
                        <select id="student_id" name="student_id"
                            class="form-select @error('student_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                    {{ $student->name }} — {{ $student->schoolClass->name ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}"
                            class="form-control @error('date') is-invalid @enderror" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="note" class="form-label">Catatan</label>
                        <textarea id="note" name="note" rows="5"
                            class="form-control @error('note') is-invalid @enderror" required>{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            {{-- Hidden fallback: kalau di-uncheck, field tetap terkirim sebagai 0
                                 alih-alih hilang total dari request. --}}
                            <input type="hidden" name="is_confidential" value="0">
                            <input type="checkbox" id="is_confidential" name="is_confidential" value="1"
                                class="form-check-input" {{ $isConfidentialChecked ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_confidential">
                                Tandai sebagai rahasia
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('perilaku.counseling-notes.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

@endsection