@extends('layouts.app')

@section('title', 'Bank Soal / Materi Ajar')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Bank Soal / Materi Ajar</h4>
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('akademik.materials.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Filter Mata Pelajaran</label>
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
            @if ($materials->isEmpty())
                <div class="alert alert-warning mb-0">
                    Belum ada materi ajar yang di-upload. Data akan muncul di sini setelah guru mulai upload materi
                    lewat menu Akademik → Materi Ajar.
                </div>
            @else
                <div class="table-responsive">
                    <table id="materials-table" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Mata Pelajaran</th>
                                <th>Diunggah Oleh</th>
                                <th>Tanggal</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materials as $material)
                                <tr>
                                    <td>
                                        {{ $material->title }}
                                        @if ($material->description)
                                            <div class="text-muted small">{{ Str::limit($material->description, 60) }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $material->subject->name ?? '-' }}</td>
                                    <td>{{ $material->teacher->user->name ?? '-' }}</td>
                                    <td>{{ $material->created_at->translatedFormat('d M Y') }}</td>
                                    <td>
                                        @if ($material->fileUrl())
                                            <a href="{{ $material->fileUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                Unduh
                                            </a>
                                        @else
                                            <span class="text-muted small">Tidak ada file</span>
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
            if ($('#materials-table').length) {
                $('#materials-table').DataTable({
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
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