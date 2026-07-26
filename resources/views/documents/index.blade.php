@extends('layouts.app')

@section('title', 'Dokumen Mengajar (CP/ATP/Modul Ajar)')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Dokumen Mengajar</h4>
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('akademik.documents.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Jenis</label>
                    <select name="document_type_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($documentTypes as $docType)
                            <option value="{{ $docType->id }}" @selected(request('document_type_id') == $docType->id)>
                                {{ $docType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="subject_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(request('subject_id') == $subject->id)>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($documents->isEmpty())
                <div class="alert alert-warning mb-0">
                    Belum ada dokumen yang di-upload guru.
                </div>
            @else
                <div class="table-responsive">
                    <table id="documents-table" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Judul</th>
                                <th>Mata Pelajaran</th>
                                <th>Semester</th>
                                <th>Diunggah Oleh</th>
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
                                    <td>{{ $doc->teacher->user->name ?? '-' }}</td>
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

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            if ($('#documents-table').length) {
                $('#documents-table').DataTable({
                    language: {
                        search: "Cari:", lengthMenu: "Tampilkan _MENU_ data",
                        zeroRecords: "Data tidak ditemukan",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        infoEmpty: "Tidak ada data",
                        paginate: { previous: "Sebelumnya", next: "Selanjutnya" }
                    }
                });
            }
        });
    </script>
@endpush