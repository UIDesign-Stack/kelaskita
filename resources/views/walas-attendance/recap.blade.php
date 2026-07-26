@extends('layouts.app')

@section('title', 'Rekap Kehadiran')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Rekap Kehadiran</h4>
    </div>

    @if (!$class)
        <div class="alert alert-warning mb-0">
            Anda belum ditugaskan sebagai wali kelas di kelas manapun.
        </div>
    @else
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('wali-kelas.attendance.recap') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Kelas {{ $class->name }}</h6>

                @if ($recap->isEmpty())
                    <div class="alert alert-warning mb-0">Belum ada siswa di kelas ini.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th class="text-center">Hadir</th>
                                    <th class="text-center">Izin</th>
                                    <th class="text-center">Sakit</th>
                                    <th class="text-center">Alpa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recap as $row)
                                    <tr>
                                        <td>{{ $row['student']->name }}</td>
                                        <td class="text-center text-success">{{ $row['hadir'] }}</td>
                                        <td class="text-center text-primary">{{ $row['izin'] }}</td>
                                        <td class="text-center text-warning">{{ $row['sakit'] }}</td>
                                        <td class="text-center text-danger">{{ $row['alpa'] }}</td>
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