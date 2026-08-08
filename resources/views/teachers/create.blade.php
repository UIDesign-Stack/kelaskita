@extends('layouts.app')

@section('title', 'Tambah Guru')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Guru</h4>
        <a href="{{ route('data-master.teachers.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('data-master.teachers.store') }}" enctype="multipart/form-data">
                @csrf

                @include('data-master.teachers._teacher-form', ['teacher' => null])

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('data-master.teachers.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Guru</button>
                </div>
            </form>
        </div>
    </div>

@endsection