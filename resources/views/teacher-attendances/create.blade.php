@extends('layouts.app')

@section('title', 'Input Absensi Guru')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Input Absensi Guru</h4>
        <a href="{{ route('presensi.teacher-attendances.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('presensi.teacher-attendances.create') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" value="{{ $date }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</h6>

            @if ($teachers->isEmpty())
                <div class="alert alert-warning mb-0">Belum ada data guru.</div>
            @else
                <form method="POST" action="{{ route('presensi.teacher-attendances.store') }}">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Nama Guru</th>
                                    <th class="text-center">Hadir</th>
                                    <th class="text-center">Izin</th>
                                    <th class="text-center">Sakit</th>
                                    <th class="text-center">Alpa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teachers as $teacher)
                                    @php $current = $existing[$teacher->id] ?? 'hadir'; @endphp
                                    <tr>
                                        <td>{{ $teacher->user->name ?? '-' }}</td>
                                        @foreach (['hadir', 'izin', 'sakit', 'alpa'] as $option)
                                            <td class="text-center">
                                                <input type="radio" name="status[{{ $teacher->id }}]"
                                                    value="{{ $option }}" class="form-check-input"
                                                    {{ $current === $option ? 'checked' : '' }}>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

@endsection