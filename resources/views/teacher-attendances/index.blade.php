@extends('layouts.app')

@section('title', 'Absensi Guru')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Absensi Guru</h4>
        <a href="{{ route('presensi.teacher-attendances.create') }}" class="btn btn-primary">
            + Input Absensi
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('presensi.teacher-attendances.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-md-3">
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
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="text-muted mb-1">Hadir</h6><h3 class="mb-0 text-success">{{ $summary['hadir'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="text-muted mb-1">Izin</h6><h3 class="mb-0 text-primary">{{ $summary['izin'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="text-muted mb-1">Sakit</h6><h3 class="mb-0 text-warning">{{ $summary['sakit'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="text-muted mb-1">Alpa</h6><h3 class="mb-0 text-danger">{{ $summary['alpa'] }}</h3>
            </div></div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($records->isEmpty())
                <div class="alert alert-warning mb-0">Belum ada data absensi guru.</div>
            @else
                <div class="table-responsive">
                    <table id="teacher-attendances-table" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Guru</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr>
                                    <td>{{ $record->date->translatedFormat('d M Y') }}</td>
                                    <td>{{ $record->teacher->user->name ?? '-' }}</td>
                                    <td>
                                        @php
                                            $badgeColor = match($record->status) {
                                                'hadir' => 'success', 'izin' => 'primary',
                                                'sakit' => 'warning', 'alpa' => 'danger', default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge text-bg-{{ $badgeColor }} text-capitalize">{{ $record->status }}</span>
                                    </td>
                                    <td>{{ $record->notes ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $records->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            if ($('#teacher-attendances-table').length) {
                $('#teacher-attendances-table').DataTable({
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