@extends('layouts.app')

@section('title', 'Buku Penghubung')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Buku Penghubung</h4>
        @if ($class)
            <a href="{{ route('wali-kelas.communication-logs.create') }}" class="btn btn-primary">+ Tulis Catatan</a>
        @endif
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (!$class)
        <div class="alert alert-warning mb-0">
            Anda belum ditugaskan sebagai wali kelas di kelas manapun.
        </div>
    @elseif ($logs->isEmpty())
        <div class="alert alert-warning mb-0">Belum ada catatan buku penghubung.</div>
    @else
        @foreach ($logs as $log)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-semibold mb-0">{{ $log->student->name ?? '-' }}</h6>
                            <span class="text-muted small">{{ $log->date->translatedFormat('d F Y') }}</span>
                        </div>
                        @if ($log->parent_note)
                            <span class="badge text-bg-success">Sudah Dibalas Ortu</span>
                        @else
                            <span class="badge text-bg-secondary">Menunggu Balasan</span>
                        @endif
                    </div>

                    <div class="mb-2">
                        <div class="text-muted small mb-1">Catatan Wali Kelas:</div>
                        <div class="border rounded p-2 bg-light">{{ $log->teacher_note }}</div>
                    </div>

                    @if ($log->parent_note)
                        <div>
                            <div class="text-muted small mb-1">Balasan Orang Tua:</div>
                            <div class="border rounded p-2 bg-success-subtle">{{ $log->parent_note }}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

@endsection