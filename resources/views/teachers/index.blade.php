@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Guru</h4>
        <a href="{{ route('data-master.teachers.create') }}" class="btn btn-primary">
            + Tambah Guru
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="teachers-table" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>NUPTK</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Spesialisasi</th>
                            <th>Wali Kelas Dari</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teachers as $teacher)
                            <tr>
                                <td>
                                    <img src="{{ $teacher->photoUrl() }}" alt="{{ $teacher->user->name ?? 'Guru' }}"
                                        class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                                </td>
                                <td>{{ $teacher->nuptk ?? '-' }}</td>
                                <td>{{ $teacher->user->name ?? '-' }}</td>
                                <td>{{ $teacher->user->email ?? '-' }}</td>
                                <td>{{ $teacher->specialization ?? '-' }}</td>
                                <td>
                                    @forelse ($teacher->homeroomClasses as $class)
                                        <span class="badge text-bg-secondary">{{ $class->name }}</span>
                                    @empty
                                        <span class="text-muted">-</span>
                                    @endforelse
                                </td>
                                <td>
                                    <a href="{{ route('data-master.teachers.show', $teacher) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                    <a href="{{ route('data-master.teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('data-master.teachers.destroy', $teacher) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus guru ini? Akun login guru ini juga akan ikut terhapus.');">
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
            $('#teachers-table').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ guru",
                    infoEmpty: "Tidak ada data",
                    paginate: { previous: "Sebelumnya", next: "Selanjutnya" }
                }
            });
        });
    </script>
@endpush