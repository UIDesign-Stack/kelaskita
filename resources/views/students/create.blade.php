@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Siswa</h4>
        <a href="{{ route('data-master.students.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('data-master.students.store') }}" enctype="multipart/form-data">
                @csrf

                @include('students._student-form', ['student' => null])

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('data-master.students.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Siswa</button>
                </div>
            </form>
        </div>
    </div>

@endsection