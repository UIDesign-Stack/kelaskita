@extends('layouts.app')

@section('title', 'Dokumen Mengajar')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Dokumen Mengajar Saya</h4>
        <a href="{{ route('guru.documents.create') }}" class="btn btn-primary btn-sm">+ Upload Dokumen</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($documents->isEmpty())
                <div class="alert alert-warning mb-0">
                    Anda belum meng-upload dokumen mengajar apapun.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Judul</th>
                                <th>Mata Pelajaran</th>
                                <th>Semester</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $doc)
                                <tr>
                                    <td><span class="badge text-bg-primary">{{ $doc->documentType->name ?? '-' }}</span></td>
                                    <td>{{ $doc->title }}</td>
                                    <td>{{ $doc->subject->name ?? '-' }}</td>
                                    <td class="text-capitalize">{{ $doc->semester ?? '-' }}</td>
                                    <td>
                                        @if ($doc->fileUrl())
                                            <a href="{{ $doc->fileUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">Unduh</a>
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