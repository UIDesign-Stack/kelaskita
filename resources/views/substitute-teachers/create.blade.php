@extends('layouts.app')

@section('title', 'Catat Guru Pengganti')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Catat Guru Pengganti</h4>
        <a href="{{ route('presensi.substitute-teachers.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('presensi.substitute-teachers.store') }}">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="original_teacher_id" class="form-label">Guru yang Digantikan</label>
                        <select id="original_teacher_id" name="original_teacher_id"
                            class="form-select @error('original_teacher_id') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @selected(old('original_teacher_id') == $teacher->id)>
                                    {{ $teacher->user->name ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('original_teacher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="substitute_teacher_id" class="form-label">Guru Pengganti</label>
                        <select id="substitute_teacher_id" name="substitute_teacher_id"
                            class="form-select @error('substitute_teacher_id') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @selected(old('substitute_teacher_id') == $teacher->id)>
                                    {{ $teacher->user->name ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('substitute_teacher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="class_id" class="form-label">Kelas</label>
                        <select id="class_id" name="class_id"
                            class="form-select @error('class_id') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-muted">(opsional)</span></label>
                        <select id="subject_id" name="subject_id"
                            class="form-select @error('subject_id') is-invalid @enderror">
                            <option value="">-- Tidak Spesifik --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}"
                            class="form-control @error('date') is-invalid @enderror" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Keterangan <span class="text-muted">(opsional)</span></label>
                        <input type="text" id="notes" name="notes" value="{{ old('notes') }}"
                            placeholder="Contoh: Guru asal sedang sakit"
                            class="form-control @error('notes') is-invalid @enderror">
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('presensi.substitute-teachers.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

@endsection