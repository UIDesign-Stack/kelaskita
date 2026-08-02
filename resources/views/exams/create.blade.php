@extends('layouts.app')

@section('title', 'Buat Paket Ujian')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Buat Paket Ujian</h4>
        <a href="{{ route('guru.exams.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($assignments->isEmpty())
                <div class="alert alert-warning mb-0">
                    Anda belum ditugaskan mengajar di kelas/mapel manapun.
                </div>
            @else
                @php
                    $hasPreviousSubmission = count(old()) > 0;
                    $isCbtChecked = $hasPreviousSubmission ? old('is_cbt') : true;
                @endphp

                <form method="POST" action="{{ route('guru.exams.store') }}">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="assignment_id" class="form-label">Kelas & Mata Pelajaran</label>
                            <select id="assignment_id" name="assignment_id"
                                class="form-select @error('assignment_id') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($assignments as $assignment)
                                    <option value="{{ $assignment->id }}" @selected(old('assignment_id') == $assignment->id)>
                                        {{ $assignment->subject->name ?? '-' }} — Kelas {{ $assignment->schoolClass->name ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assignment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="type" class="form-label">Jenis Ujian</label>
                            <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="kuis" @selected(old('type') == 'kuis')>Kuis</option>
                                <option value="tryout" @selected(old('type') == 'tryout')>Tryout</option>
                                <option value="uts" @selected(old('type') == 'uts')>UTS</option>
                                <option value="uas" @selected(old('type') == 'uas')>UAS</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label for="title" class="form-label">Judul Ujian</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                placeholder="Contoh: Kuis Bab 1 - Aljabar"
                                class="form-control @error('title') is-invalid @enderror" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="duration_minutes" class="form-label">Durasi (menit)</label>
                            <input type="number" id="duration_minutes" name="duration_minutes"
                                value="{{ old('duration_minutes', 60) }}" min="1" max="300"
                                class="form-control @error('duration_minutes') is-invalid @enderror" required>
                            @error('duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                {{-- Hidden fallback: kalau di-uncheck, field tetap terkirim sebagai 0
                                     alih-alih hilang total dari request. --}}
                                <input type="hidden" name="is_cbt" value="0">
                                <input type="checkbox" id="is_cbt" name="is_cbt" value="1" class="form-check-input"
                                    {{ $isCbtChecked ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_cbt">
                                    Ujian dikerjakan online (CBT)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('guru.exams.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Buat & Lanjut Tambah Soal</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

@endsection