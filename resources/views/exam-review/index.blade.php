@extends('layouts.app')

@section('title', 'Bank Soal Ujian — Review')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Bank Soal Ujian (Review Admin)</h4>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="text-muted mb-1">Menunggu Persetujuan</h6>
                <h3 class="mb-0 text-warning">{{ $summary['pending'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="text-muted mb-1">Disetujui</h6>
                <h3 class="mb-0 text-success">{{ $summary['approved'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="text-muted mb-1">Ditolak</h6>
                <h3 class="mb-0 text-danger">{{ $summary['rejected'] }}</h3>
            </div></div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ujian.exam-review.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="pending" @selected(request('status') == 'pending')>Menunggu Persetujuan</option>
                        <option value="approved" @selected(request('status') == 'approved')>Disetujui</option>
                        <option value="rejected" @selected(request('status') == 'rejected')>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kelas</label>
                    <select name="class_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="subject_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(request('subject_id') == $subject->id)>{{ $subject->name }}</option>
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
            @if ($exams->isEmpty())
                <div class="alert alert-warning mb-0">Belum ada paket ujian yang di-submit guru.</div>
            @else
                <div class="table-responsive">
                    <table id="exam-review-table" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Guru</th>
                                <th>Kelas</th>
                                <th>Mapel</th>
                                <th>Jml Soal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($exams as $exam)
                                <tr>
                                    <td>{{ $exam->title }}</td>
                                    <td>{{ $exam->teacher->user->name ?? '-' }}</td>
                                    <td>{{ $exam->schoolClass->name ?? '-' }}</td>
                                    <td>{{ $exam->subject->name ?? '-' }}</td>
                                    <td>{{ $exam->questions_count }}</td>
                                    <td>
                                        @php
                                            $statusColor = match($exam->status) {
                                                'approved' => 'success', 'rejected' => 'danger', default => 'warning',
                                            };
                                            $statusLabel = match($exam->status) {
                                                'approved' => 'Disetujui', 'rejected' => 'Ditolak', default => 'Menunggu',
                                            };
                                        @endphp
                                        <span class="badge text-bg-{{ $statusColor }}">{{ $statusLabel }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('ujian.exam-review.show', $exam) }}" class="btn btn-sm btn-outline-primary">
                                            Review
                                        </a>
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

@push('scripts')
    <script>
        $(document).ready(function () {
            if ($('#exam-review-table').length) {
                $('#exam-review-table').DataTable({
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