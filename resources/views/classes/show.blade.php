@extends('layouts.app')

@section('title', 'Detail Kelas')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Detail Kelas — {{ $class->name }}</h4>
        <a href="{{ route('data-master.classes.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="row g-3">
        {{-- ===== Kolom Kiri: Info Kelas ===== --}}
        <div class="col-md-4">
            @include('partials.classes._info-card')
            @include('partials.classes._teaching-assignments-card')
        </div>

        {{-- ===== Kolom Kanan: Daftar Siswa ===== --}}
        <div class="col-md-8">
            @include('partials.classes._students-table-card')
        </div>
    </div>

@endsection