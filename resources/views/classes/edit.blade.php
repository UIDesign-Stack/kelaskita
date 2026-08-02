@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Kelas — {{ $class->name }}</h4>
        <a href="{{ route('data-master.classes.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('data-master.classes.update', $class) }}">
                @csrf
                @method('PUT')

                @include('_form')

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('data-master.classes.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

@endsection