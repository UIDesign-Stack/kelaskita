@extends('layouts.app')

@section('title', 'Catat Pelanggaran')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Catat Pelanggaran</h4>
        <a href="{{ route('wali-kelas.violations.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('wali-kelas.violations.store') }}">
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

                    <div class="col-md-3">
                        <label for="points" class="form-label">Poin Pelanggaran</label>
                        <input type="number" id="points" name="points" value="{{ old('points', 5) }}" min="1" max="100"
                            class="form-control @error('points') is-invalid @enderror" required>
                        @error('points')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}"
                            class="form-control @error('date') is-invalid @enderror" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Deskripsi Pelanggaran</label>
                        <input type="text" id="description" name="description" value="{{ old('description') }}"
                            placeholder="Contoh: Terlambat masuk kelas, tidak mengerjakan PR"
                            class="form-control @error('description') is-invalid @enderror" required>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('wali-kelas.violations.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

@endsection