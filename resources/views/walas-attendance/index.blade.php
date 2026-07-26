@extends('layouts.app')

@section('title', 'Presensi Harian')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Presensi Harian</h4>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (!$class)
        <div class="alert alert-warning mb-0">
            Anda belum ditugaskan sebagai wali kelas di kelas manapun. Hubungi admin untuk mengatur ini.
        </div>
    @else
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('wali-kelas.attendance.index') }}" class="row g-3 align-items-end">
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
                <h6 class="fw-semibold mb-3">
                    Kelas {{ $class->name }} — {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                </h6>

                @if ($students->isEmpty())
                    <div class="alert alert-warning mb-0">Belum ada siswa di kelas ini.</div>
                @else
                    <form method="POST" action="{{ route('wali-kelas.attendance.store') }}">
                        @csrf
                        <input type="hidden" name="date" value="{{ $date }}">

                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th class="text-center">Hadir</th>
                                        <th class="text-center">Izin</th>
                                        <th class="text-center">Sakit</th>
                                        <th class="text-center">Alpa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                        @php $current = $existing[$student->id] ?? 'hadir'; @endphp
                                        <tr>
                                            <td>{{ $student->nis }}</td>
                                            <td>{{ $student->name }}</td>
                                            @foreach (['hadir', 'izin', 'sakit', 'alpa'] as $option)
                                                <td class="text-center">
                                                    <input type="radio" name="status[{{ $student->id }}]"
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
                            <button type="submit" class="btn btn-primary">Simpan Presensi</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif

@endsection