@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Guru</h4>
        <a href="{{ route('data-master.teachers.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('data-master.teachers.update', $teacher) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('data-master.teachers._teacher-form')

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('data-master.teachers.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

@endsection