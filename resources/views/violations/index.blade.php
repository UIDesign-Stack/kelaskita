@extends('layouts.app')

@section('title', 'Pelanggaran')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Pelanggaran Siswa (Sekolah)</h4>
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('perilaku.violations.index') }}" class="row g-3 align-items-end">
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
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    @if ($topStudents->isNotEmpty())
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">5 Siswa dengan Poin Pelanggaran Tertinggi</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr><th>Siswa</th><th>Kelas</th><th>Jumlah Catatan</th><th>Total Poin</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($topStudents as $row)
                                <tr>
                                    <td>{{ $row['student']->name ?? '-' }}</td>
                                    <td>{{ $row['student']->schoolClass->name ?? '-' }}</td>
                                    <td>{{ $row['total_records'] }}</td>
                                    <td><span class="badge text-bg-danger">{{ $row['total_points'] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($violations->isEmpty())
                <div class="alert alert-warning mb-0">Belum ada catatan pelanggaran.</div>
            @else
                <div class="table-responsive">
                    <table id="violations-table" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Pelanggaran</th>
                                <th>Poin</th>
                                <th>Dicatat Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($violations as $violation)
                                <tr>
                                    <td>{{ $violation->date->translatedFormat('d M Y') }}</td>
                                    <td>{{ $violation->student->name ?? '-' }}</td>
                                    <td>{{ $violation->student->schoolClass->name ?? '-' }}</td>
                                    <td>{{ $violation->description }}</td>
                                    <td><span class="badge text-bg-danger">{{ $violation->points }}</span></td>
                                    <td>{{ $violation->recordedBy->name ?? '-' }}</td>
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
            if ($('#violations-table').length) {
                $('#violations-table').DataTable({
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