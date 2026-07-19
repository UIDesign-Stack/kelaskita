@extends('layouts.app')

@section('title', 'Edit Tahun Ajaran')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Tahun Ajaran</h4>
        <a href="{{ route('data-master.school-years.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('data-master.school-years.update', $schoolYear) }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Tahun Ajaran</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $schoolYear->name) }}"
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="semester" class="form-label">Semester</label>
                        <select id="semester" name="semester"
                            class="form-select @error('semester') is-invalid @enderror" required>
                            <option value="ganjil" @selected(old('semester', $schoolYear->semester) == 'ganjil')>Ganjil</option>
                            <option value="genap" @selected(old('semester', $schoolYear->semester) == 'genap')>Genap</option>
                        </select>
                        @error('semester')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                class="form-check-input" {{ old('is_active', $schoolYear->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Jadikan tahun ajaran aktif
                            </label>
                        </div>
                        <div class="form-text">
                            Mengaktifkan ini akan otomatis menonaktifkan tahun ajaran lain yang sedang aktif.
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('data-master.school-years.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

@endsection