@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Kelas</h4>
        <a href="{{ route('data-master.classes.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('data-master.classes.store') }}">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Kelas</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            placeholder="Contoh: 7A, 8B, 9C"
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="grade_level" class="form-label">Tingkat</label>
                        <input type="text" id="grade_level" name="grade_level" value="{{ old('grade_level') }}"
                            placeholder="Contoh: 7, 8, 9"
                            class="form-control @error('grade_level') is-invalid @enderror" required>
                        @error('grade_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="school_year_id" class="form-label">Tahun Ajaran</label>
                        <select id="school_year_id" name="school_year_id"
                            class="form-select @error('school_year_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @forelse ($schoolYears as $year)
                                <option value="{{ $year->id }}" @selected(old('school_year_id') == $year->id)>
                                    {{ $year->name }} ({{ ucfirst($year->semester) }})
                                    {{ $year->is_active ? '— Aktif' : '' }}
                                </option>
                            @empty
                                <option value="" disabled>Belum ada data tahun ajaran</option>
                            @endforelse
                        </select>
                        @error('school_year_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($schoolYears->isEmpty())
                            <div class="form-text text-danger">
                                Tambahkan data Tahun Ajaran terlebih dahulu sebelum membuat kelas.
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label for="homeroom_teacher_id" class="form-label">
                            Wali Kelas <span class="text-muted">(opsional)</span>
                        </label>
                        <select id="homeroom_teacher_id" name="homeroom_teacher_id"
                            class="form-select @error('homeroom_teacher_id') is-invalid @enderror">
                            <option value="">-- Belum Ditentukan --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @selected(old('homeroom_teacher_id') == $teacher->id)>
                                    {{ $teacher->user->name ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('homeroom_teacher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('data-master.classes.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary" {{ $schoolYears->isEmpty() ? 'disabled' : '' }}>
                        Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection