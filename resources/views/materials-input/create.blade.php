@extends('layouts.app')

@section('title', 'Upload Materi Ajar')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Upload Materi Ajar</h4>
        <a href="{{ route('guru.materials.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($subjects->isEmpty())
                <div class="alert alert-warning mb-0">
                    Anda belum ditugaskan mengajar mata pelajaran apapun. Hubungi admin untuk mengatur penugasan mengajar.
                </div>
            @else
                <form method="POST" action="{{ route('guru.materials.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 mb-4">
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

                        <div class="col-md-6">
                            <label for="file" class="form-label">File <span class="text-muted">(opsional)</span></label>
                            <input type="file" id="file" name="file"
                                class="form-control @error('file') is-invalid @enderror">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">PDF, Word, PowerPoint, atau gambar. Maks 10MB.</div>
                        </div>

                        <div class="col-12">
                            <label for="title" class="form-label">Judul Materi</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                class="form-control @error('title') is-invalid @enderror" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Deskripsi <span class="text-muted">(opsional)</span></label>
                            <textarea id="description" name="description" rows="3"
                                class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('guru.materials.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

@endsection