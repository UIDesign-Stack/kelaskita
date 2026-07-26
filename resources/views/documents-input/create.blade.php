@extends('layouts.app')

@section('title', 'Upload Dokumen Mengajar')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Upload Dokumen Mengajar</h4>
        <a href="{{ route('guru.documents.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($subjects->isEmpty())
                <div class="alert alert-warning mb-0">
                    Anda belum ditugaskan mengajar mata pelajaran apapun.
                </div>
            @elseif ($documentTypes->isEmpty())
                <div class="alert alert-warning mb-0">
                    Belum ada jenis dokumen yang diaktifkan admin.
                </div>
            @else
                <form method="POST" action="{{ route('guru.documents.store') }}" enctype="multipart/form-data" id="doc-form">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="document_type_id" class="form-label">Jenis Dokumen</label>
                            <select id="document_type_id" name="document_type_id"
                                class="form-select @error('document_type_id') is-invalid @enderror" required>
                                @foreach ($documentTypes as $docType)
                                    <option value="{{ $docType->id }}"
                                        data-requires-semester="{{ $docType->requires_semester ? '1' : '0' }}"
                                        @selected(old('document_type_id', $selectedType->id ?? null) == $docType->id)>
                                        {{ $docType->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('document_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="subject_id" class="form-label">Mata Pelajaran</label>
                            <select id="subject_id" name="subject_id"
                                class="form-select @error('subject_id') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
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

                        <div class="col-md-6" id="semester-field">
                            <label for="semester" class="form-label">Semester</label>
                            <select id="semester" name="semester"
                                class="form-select @error('semester') is-invalid @enderror">
                                <option value="ganjil" @selected(old('semester') == 'ganjil')>Ganjil</option>
                                <option value="genap" @selected(old('semester') == 'genap')>Genap</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                class="form-control @error('title') is-invalid @enderror" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="file" class="form-label">File <span class="text-muted">(opsional)</span></label>
                            <input type="file" id="file" name="file"
                                class="form-control @error('file') is-invalid @enderror">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">PDF atau Word, maks 10MB.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('guru.documents.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('document_type_id');
            const semesterField = document.getElementById('semester-field');

            function toggleSemester() {
                const selected = typeSelect.options[typeSelect.selectedIndex];
                const needsSemester = selected?.dataset.requiresSemester === '1';
                semesterField.classList.toggle('d-none', !needsSemester);
            }

            if (typeSelect) {
                typeSelect.addEventListener('change', toggleSemester);
                toggleSemester();
            }
        });
    </script>
@endpush