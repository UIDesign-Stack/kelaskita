@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Mata Pelajaran</h4>
        <a href="{{ route('data-master.subjects.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('data-master.subjects.store') }}">
                @csrf

                @include('subjects._subject-form', ['subject' => null])

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('data-master.subjects.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

@endsection