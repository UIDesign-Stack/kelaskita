@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Input Nilai</h4>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($assignments->isEmpty())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Anda belum ditugaskan mengajar di kelas/mata pelajaran manapun. Hubungi admin untuk mengatur penugasan mengajar.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @else
        <div class="row g-3">
            @foreach ($assignments as $assignment)
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-1">{{ $assignment->subject->name ?? '-' }}</h6>
                            <div class="text-muted small mb-3">
                                Kelas {{ $assignment->schoolClass->name ?? '-' }} —
                                {{ $assignment->schoolYear->name ?? '-' }}
                            </div>
                            <a href="{{ route('guru.grade-input.create', $assignment) }}" class="btn btn-primary btn-sm">
                                Input Nilai
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection