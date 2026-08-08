@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

    @php
        $studentStatuses = [
            'aktif' => 'success',
            'pindah' => 'secondary',
            'lulus' => 'primary',
            'keluar' => 'danger',
        ];
    @endphp

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
                            @php $photoUrl = $student->photoUrl(); @endphp
                            <tr>
                                <td>
                                    @if ($photoUrl)
                                        <img src="{{ $photoUrl }}" alt="{{ $student->name }}"
                                            class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center fw-semibold"
                                            style="width: 36px; height: 36px;" title="{{ $student->name }}">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $student->nis }}</td>
                                <td>{{ $student->nisn ?? '-' }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->schoolClass->name ?? '-' }}</td>
                                <td>{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>
                                    <span class="badge text-bg-{{ $studentStatuses[$student->status] ?? 'secondary' }} text-capitalize">
                                        {{ $student->status }}
                                    </span>
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

@push('scripts')
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