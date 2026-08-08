@extends('layouts.app')

@section('title', 'Prestasi Siswa')

@section('content')

    @php
        $levelStyles = [
            'sekolah' => ['color' => 'secondary'],
            'kecamatan' => ['color' => 'info'],
            'kabupaten' => ['color' => 'primary'],
            'provinsi' => ['color' => 'warning'],
            'nasional' => ['color' => 'danger'],
            'internasional' => ['color' => 'dark', 'icon' => '🏆'],
        ];
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Prestasi Siswa</h4>
        @if ($class)
            <a href="{{ route('wali-kelas.achievements.create') }}" class="btn btn-primary">+ Catat Prestasi</a>
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
                @if ($achievements->isEmpty())
                    <div class="alert alert-warning mb-0">Belum ada catatan prestasi di kelas ini.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Siswa</th>
                                    <th>Prestasi</th>
                                    <th>Tingkat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($achievements as $achievement)
                                    @php
                                        $levelStyle = $levelStyles[$achievement->level] ?? ['color' => 'secondary'];
                                    @endphp
                                    <tr>
                                        <td>{{ $achievement->date->translatedFormat('d M Y') }}</td>
                                        <td>{{ $achievement->student->name ?? '-' }}</td>
                                        <td>{{ $achievement->title }}</td>
                                        <td>
                                            <span class="badge text-bg-{{ $levelStyle['color'] }} text-capitalize">
                                                @if (!empty($levelStyle['icon']))
                                                    {{ $levelStyle['icon'] }}
                                                @endif
                                                {{ $achievement->level }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('wali-kelas.achievements.destroy', $achievement) }}" method="POST"
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