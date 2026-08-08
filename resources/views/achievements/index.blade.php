@extends('layouts.app')

@section('title', 'Prestasi')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Prestasi Siswa (Sekolah)</h4>
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('perilaku.achievements.index') }}" class="row g-3 align-items-end">
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
                    <label class="form-label">Tingkat</label>
                    <select name="level" class="form-select">
                        <option value="">Semua</option>
                        <option value="sekolah" @selected(request('level') == 'sekolah')>Sekolah</option>
                        <option value="kecamatan" @selected(request('level') == 'kecamatan')>Kecamatan</option>
                        <option value="kabupaten" @selected(request('level') == 'kabupaten')>Kabupaten/Kota</option>
                        <option value="provinsi" @selected(request('level') == 'provinsi')>Provinsi</option>
                        <option value="nasional" @selected(request('level') == 'nasional')>Nasional</option>
                        <option value="internasional" @selected(request('level') == 'internasional')>Internasional</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($achievements->isEmpty())
                <div class="alert alert-warning mb-0">Belum ada catatan prestasi.</div>
            @else
                <div class="table-responsive">
                    <table id="achievements-table" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Prestasi</th>
                                <th>Tingkat</th>
                                <th>Dicatat Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($achievements as $achievement)
                                <tr>
                                    <td>{{ $achievement->date->translatedFormat('d M Y') }}</td>
                                    <td>{{ $achievement->student->name ?? '-' }}</td>
                                    <td>{{ $achievement->student->schoolClass->name ?? '-' }}</td>
                                    <td>{{ $achievement->title }}</td>
                                    <td><span class="badge text-bg-success text-capitalize">{{ $achievement->level }}</span></td>
                                    <td>{{ $achievement->recordedBy->name ?? '-' }}</td>
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
            if ($('#achievements-table').length) {
                $('#achievements-table').DataTable({
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