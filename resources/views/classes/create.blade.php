@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Kelas</h4>
        <a href="{{ route('data-master.classes.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('data-master.classes.store') }}">
                @csrf

                @include('partials.classes._form')

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('data-master.classes.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary" {{ $schoolYears->isEmpty() ? 'disabled' : '' }}>
                        Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection