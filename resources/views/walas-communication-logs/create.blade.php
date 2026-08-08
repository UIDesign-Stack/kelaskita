@extends('layouts.app')

@section('title', 'Tulis Catatan Buku Penghubung')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tulis Catatan Buku Penghubung</h4>
        <a href="{{ route('wali-kelas.communication-logs.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('wali-kelas.communication-logs.store') }}">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="student_id" class="form-label">Siswa</label>
                        <select id="student_id" name="student_id"
                            class="form-select @error('student_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                    {{ $student->name }} ({{ $student->nis }})
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
                        <label for="teacher_note" class="form-label">Catatan untuk Orang Tua</label>
                        <textarea id="teacher_note" name="teacher_note" rows="4"
                            placeholder="Contoh: Ananda hari ini tidak membawa buku PR, mohon diingatkan untuk besok."
                            class="form-control @error('teacher_note') is-invalid @enderror" required>{{ old('teacher_note') }}</textarea>
                        @error('teacher_note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('wali-kelas.communication-logs.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>

@endsection