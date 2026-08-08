@extends('layouts.app')

@section('title', 'Catat Prestasi')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Catat Prestasi</h4>
        <a href="{{ route('wali-kelas.achievements.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('wali-kelas.achievements.store') }}">
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
                        <label for="level" class="form-label">Tingkat</label>
                        <select id="level" name="level" class="form-select @error('level') is-invalid @enderror" required>
                            <option value="sekolah" @selected(old('level') == 'sekolah')>Sekolah</option>
                            <option value="kecamatan" @selected(old('level') == 'kecamatan')>Kecamatan</option>
                            <option value="kabupaten" @selected(old('level') == 'kabupaten')>Kabupaten/Kota</option>
                            <option value="provinsi" @selected(old('level') == 'provinsi')>Provinsi</option>
                            <option value="nasional" @selected(old('level') == 'nasional')>Nasional</option>
                            <option value="internasional" @selected(old('level') == 'internasional')>Internasional</option>
                        </select>
                        @error('level')
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
                        <label for="title" class="form-label">Judul Prestasi</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                            placeholder="Contoh: Juara 1 Lomba Cerdas Cermat"
                            class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('wali-kelas.achievements.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

@endsection