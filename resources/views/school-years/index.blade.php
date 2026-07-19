@extends('layouts.app')

@section('title', 'Tahun Ajaran')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tahun Ajaran</h4>
        <a href="{{ route('data-master.school-years.create') }}" class="btn btn-primary">
            + Tambah Tahun Ajaran
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first('delete') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="school-years-table" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th>Tahun Ajaran</th>
                            <th>Semester</th>
                            <th>Jumlah Kelas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schoolYears as $year)
                            <tr>
                                <td>{{ $year->name }}</td>
                                <td class="text-capitalize">{{ $year->semester }}</td>
                                <td>{{ $year->classes_count }} kelas</td>
                                <td>
                                    @if ($year->is_active)
                                        <span class="badge text-bg-success">Aktif</span>
                                    @else
                                        <span class="badge text-bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('data-master.school-years.edit', $year) }}"
                                        class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('data-master.school-years.destroy', $year) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus tahun ajaran ini?');">
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
            $('#school-years-table').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ tahun ajaran",
                    infoEmpty: "Tidak ada data",
                    paginate: { previous: "Sebelumnya", next: "Selanjutnya" }
                }
            });
        });
    </script>
@endpush