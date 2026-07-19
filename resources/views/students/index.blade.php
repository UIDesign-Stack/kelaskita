@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Siswa</h4>
        <a href="{{ route('data-master.students.create') }}" class="btn btn-primary">
            + Tambah Siswa
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="students-table" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jenis Kelamin</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td>
                                    <img src="{{ $student->photoUrl() }}" alt="{{ $student->name }}"
                                        class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                                </td>
                                <td>{{ $student->nis }}</td>
                                <td>{{ $student->nisn ?? '-' }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->schoolClass->name ?? '-' }}</td>
                                <td>{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>
                                    <span class="badge text-bg-success text-capitalize">{{ $student->status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('data-master.students.show', $student) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                    <a href="{{ route('data-master.students.edit', $student) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('data-master.students.destroy', $student) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus data siswa ini?');">
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
            $('#students-table').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ siswa",
                    infoEmpty: "Tidak ada data",
                    paginate: { previous: "Sebelumnya", next: "Selanjutnya" }
                }
            });
        });
    </script>
@endpush