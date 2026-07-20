@extends('layouts.app')

@section('title', 'Materi Ajar')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Materi Ajar Saya</h4>
        <a href="{{ route('guru.materials.create') }}" class="btn btn-primary">+ Upload Materi</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($materials->isEmpty())
                <div class="alert alert-warning mb-0">
                    Anda belum meng-upload materi ajar apapun.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Mata Pelajaran</th>
                                <th>Tanggal</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materials as $material)
                                <tr>
                                    <td>{{ $material->title }}</td>
                                    <td>{{ $material->subject->name ?? '-' }}</td>
                                    <td>{{ $material->created_at->translatedFormat('d M Y') }}</td>
                                    <td>
                                        @if ($material->fileUrl())
                                            <a href="{{ $material->fileUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">Unduh</a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection