@extends('layouts.app')

@section('title', 'Bank Soal Ujian')

@section('content')

    @php
        $statuses = [
            'pending'  => ['label' => 'Menunggu',  'color' => 'warning'],
            'approved' => ['label' => 'Disetujui', 'color' => 'success'],
            'rejected' => ['label' => 'Ditolak',   'color' => 'danger'],
        ];
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Bank Soal Ujian</h4>
        <a href="{{ route('guru.exams.create') }}" class="btn btn-primary">+ Buat Paket Ujian</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($exams->isEmpty())
                <div class="alert alert-warning mb-0">Anda belum membuat paket ujian apapun.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Jenis</th>
                                <th>Jumlah Soal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($exams as $exam)
                                @php
                                    $examStatus = $statuses[$exam->status] ?? ['label' => ucfirst($exam->status), 'color' => 'secondary'];
                                @endphp
                                <tr>
                                    <td>{{ $exam->title }}</td>
                                    <td>{{ $exam->subject->name ?? '-' }}</td>
                                    <td>{{ $exam->schoolClass->name ?? '-' }}</td>
                                    <td class="text-uppercase">{{ $exam->type }}</td>
                                    <td>{{ $exam->questions_count }} soal</td>
                                    <td>
                                        <span class="badge text-bg-{{ $examStatus['color'] }}">{{ $examStatus['label'] }}</span>
                                        @if ($exam->status === 'rejected' && $exam->rejection_reason)
                                            <div class="text-danger small mt-1">{{ Str::limit($exam->rejection_reason, 60) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('guru.exams.show', $exam) }}" class="btn btn-sm btn-outline-primary">Kelola Soal</a>
                                        <form action="{{ route('guru.exams.destroy', $exam) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus paket ujian ini beserta semua soalnya?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
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