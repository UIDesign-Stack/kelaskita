@extends('layouts.app')

@section('title', 'Bank Soal Ujian — Review')

@section('content')

    @php
        $statuses = [
            'pending'  => ['label' => 'Menunggu Persetujuan', 'badge_label' => 'Menunggu',  'color' => 'warning'],
            'approved' => ['label' => 'Disetujui',            'badge_label' => 'Disetujui', 'color' => 'success'],
            'rejected' => ['label' => 'Ditolak',              'badge_label' => 'Ditolak',   'color' => 'danger'],
        ];
    @endphp

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
        @foreach ($statuses as $value => $status)
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">{{ $status['label'] }}</h6>
                        <h3 class="mb-0 text-{{ $status['color'] }}">{{ $summary[$value] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ujian.exam-review.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($statuses as $value => $status)
                            <option value="{{ $value }}" @selected(request('status') == $value)>
                                {{ $status['label'] }}
                            </option>
                        @endforeach
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
                                @php
                                    $examStatus = $statuses[$exam->status] ?? ['color' => 'secondary', 'badge_label' => ucfirst($exam->status)];
                                @endphp
                                <tr>
                                    <td>{{ $exam->title }}</td>
                                    <td>{{ $exam->teacher->user->name ?? '-' }}</td>
                                    <td>{{ $exam->schoolClass->name ?? '-' }}</td>
                                    <td>{{ $exam->subject->name ?? '-' }}</td>
                                    <td>{{ $exam->questions_count }}</td>
                                    <td>
                                        <span class="badge text-bg-{{ $examStatus['color'] }}">{{ $examStatus['badge_label'] }}</span>
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