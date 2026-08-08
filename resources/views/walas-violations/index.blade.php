@extends('layouts.app')

@section('title', 'Pelanggaran Siswa')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Pelanggaran Siswa</h4>
        @if ($class)
            <a href="{{ route('wali-kelas.violations.create') }}" class="btn btn-primary">+ Catat Pelanggaran</a>
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
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body">
                @if ($violations->isEmpty())
                    <div class="alert alert-warning mb-0">Belum ada catatan pelanggaran di kelas ini.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Siswa</th>
                                    <th>Pelanggaran</th>
                                    <th>Poin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($violations as $violation)
                                    <tr>
                                        <td>{{ $violation->date->translatedFormat('d M Y') }}</td>
                                        <td>{{ $violation->student->name ?? '-' }}</td>
                                        <td>{{ $violation->description }}</td>
                                        <td><span class="badge text-bg-danger">{{ $violation->points }}</span></td>
                                        <td>
                                            <form action="{{ route('wali-kelas.violations.destroy', $violation) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Hapus catatan ini?');">
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
    @endif

@endsection