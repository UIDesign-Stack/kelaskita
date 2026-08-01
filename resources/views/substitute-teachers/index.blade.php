@extends('layouts.app')

@section('title', 'Log Guru Pengganti')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Log Guru Pengganti</h4>
        <a href="{{ route('presensi.substitute-teachers.create') }}" class="btn btn-primary">
            + Catat Guru Pengganti
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($logs->isEmpty())
                <div class="alert alert-warning mb-0">Belum ada catatan guru pengganti.</div>
            @else
                <div class="table-responsive">
                    <table id="substitute-table" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Guru Asal</th>
                                <th>Guru Pengganti</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $log->date->translatedFormat('d M Y') }}</td>
                                    <td>{{ $log->originalTeacher->user->name ?? '-' }}</td>
                                    <td>{{ $log->substituteTeacher->user->name ?? '-' }}</td>
                                    <td>{{ $log->schoolClass->name ?? '-' }}</td>
                                    <td>{{ $log->subject->name ?? '-' }}</td>
                                    <td>{{ $log->notes ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('presensi.substitute-teachers.destroy', $log) }}" method="POST"
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

                    {{ $logs->links() }}
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
            if ($('#substitute-table').length) {
                $('#substitute-table').DataTable({
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