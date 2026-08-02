@extends('layouts.app')

@section('title', 'Hasil Ujian — ' . $exam->title)

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Hasil Ujian — {{ $exam->title }}</h4>
            <div class="text-muted small">{{ $exam->subject->name ?? '-' }} — Kelas {{ $exam->schoolClass->name ?? '-' }}</div>
        </div>
        <a href="{{ route('guru.exams.show', $exam) }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Status</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr>
                                <td>{{ $row['student']->nis }}</td>
                                <td>{{ $row['student']->name }}</td>
                                <td>
                                    @if (!$row['result'])
                                        <span class="badge text-bg-secondary">Belum Mengerjakan</span>
                                    @elseif (!$row['result']->finished_at)
                                        <span class="badge text-bg-warning">Sedang Mengerjakan</span>
                                    @elseif ($row['result']->score !== null)
                                        <span class="badge text-bg-success">Selesai Dinilai</span>
                                    @else
                                        <span class="badge text-bg-danger">Perlu Nilai Esai</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $row['result'] && $row['result']->score !== null ? number_format($row['result']->score, 1) : '-' }}
                                </td>
                                <td>
                                    @if ($row['result'] && $row['result']->finished_at)
                                        <a href="{{ route('guru.exams.results.grade', $row['result']) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            {{ $row['result']->score !== null ? 'Lihat / Edit Nilai' : 'Nilai Esai' }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection