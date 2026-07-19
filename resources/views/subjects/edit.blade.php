@extends('layouts.app')

@section('title', 'Edit Mata Pelajaran')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Mata Pelajaran</h4>
        <a href="{{ route('data-master.subjects.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('data-master.subjects.update', $subject) }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label for="name" class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $subject->name) }}"
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="code" class="form-label">Kode</label>
                        <input type="text" id="code" name="code" value="{{ old('code', $subject->code) }}"
                            class="form-control @error('code') is-invalid @enderror" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('data-master.subjects.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

@endsection