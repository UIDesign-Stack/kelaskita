@extends('layouts.app')

@section('title', 'Presensi per Mapel')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Presensi per Mapel</h4>
    </div>

    @if ($assignments->isEmpty())
        <div class="alert alert-warning mb-0">
            Anda belum ditugaskan mengajar di kelas/mata pelajaran manapun. Hubungi admin untuk mengatur penugasan mengajar.
        </div>
    @else
        <div class="row g-3">
            @foreach ($assignments as $assignment)
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-1">{{ $assignment->subject->name ?? '-' }}</h6>
                            <div class="text-muted small mb-3">Kelas {{ $assignment->schoolClass->name ?? '-' }}</div>
                            <a href="{{ route('guru.attendance.create', $assignment) }}" class="btn btn-primary btn-sm">
                                Isi Presensi
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection