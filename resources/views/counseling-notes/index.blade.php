@extends('layouts.app')

@section('title', 'Catatan BK')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Catatan BK</h4>
        <a href="{{ route('perilaku.counseling-notes.create') }}" class="btn btn-primary">+ Tambah Catatan</a>
    </div>

    <div class="alert alert-secondary small">
        <i class="bi bi-shield-lock"></i> Halaman ini berisi informasi sensitif — hanya dapat diakses oleh admin.
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('perilaku.counseling-notes.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Kelas</label>
                    <select name="class_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cari Nama Siswa</label>
                    <input type="text" name="student_search" value="{{ request('student_search') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($notes->isEmpty())
                <div class="alert alert-warning mb-0">Belum ada catatan BK.</div>
            @else
                @foreach ($notes as $note)
                    <div class="card border mb-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-semibold mb-0">
                                        {{ $note->student->name ?? '-' }}
                                        @if ($note->is_confidential)
                                            <span class="badge text-bg-dark">
                                                <i class="bi bi-shield-lock"></i> Rahasia
                                            </span>
                                        @endif
                                    </h6>
                                    <span class="text-muted small">
                                        {{ $note->student->schoolClass->name ?? '-' }} —
                                        {{ $note->date->translatedFormat('d F Y') }} —
                                        dicatat oleh {{ $note->recordedBy->name ?? '-' }}
                                    </span>
                                </div>
                                <form action="{{ route('perilaku.counseling-notes.destroy', $note) }}" method="POST"
                                    onsubmit="return confirm('Hapus catatan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                            <p class="mb-0">{{ $note->note }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

@endsection