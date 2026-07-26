@extends('layouts.app')

@section('title', 'Presensi Siswa')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Presensi Siswa</h4>
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('presensi.attendances.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Kelas</label>
                    <select name="class_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="hadir" @selected(request('status') == 'hadir')>Hadir</option>
                        <option value="izin" @selected(request('status') == 'izin')>Izin</option>
                        <option value="sakit" @selected(request('status') == 'sakit')>Sakit</option>
                        <option value="alpa" @selected(request('status') == 'alpa')>Alpa</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="text-muted mb-1">Hadir</h6>
                <h3 class="mb-0 text-success">{{ $summary['hadir'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="text-muted mb-1">Izin</h6>
                <h3 class="mb-0 text-primary">{{ $summary['izin'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="text-muted mb-1">Sakit</h6>
                <h3 class="mb-0 text-warning">{{ $summary['sakit'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="text-muted mb-1">Alpa</h6>
                <h3 class="mb-0 text-danger">{{ $summary['alpa'] }}</h3>
            </div></div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($attendances->isEmpty())
                <div class="alert alert-warning mb-0">
                    Belum ada data presensi. Data akan muncul setelah wali kelas mulai mengisi presensi harian.
                </div>
            @else
                <div class="table-responsive">
                    <table id="attendances-table" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->translatedFormat('d M Y') }}</td>
                                    <td>{{ $attendance->student->name ?? '-' }}</td>
                                    <td>{{ $attendance->schoolClass->name ?? '-' }}</td>
                                    <td>
                                        @php
                                            $badgeColor = match($attendance->status) {
                                                'hadir' => 'success', 'izin' => 'primary',
                                                'sakit' => 'warning', 'alpa' => 'danger', default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge text-bg-{{ $badgeColor }} text-capitalize">{{ $attendance->status }}</span>
                                    </td>
                                    <td>{{ $attendance->notes ?? '-' }}</td>
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
            if ($('#attendances-table').length) {
                $('#attendances-table').DataTable({
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