@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Mata Pelajaran</h4>
        <a href="{{ route('data-master.subjects.create') }}" class="btn btn-primary">
            + Tambah Mata Pelajaran
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
                <table id="subjects-table" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>KKM</th>
                            <th>Dipakai di Jadwal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subjects as $subject)
                            <tr>
                                <td><span class="badge text-bg-secondary">{{ $subject->code }}</span></td>
                                <td>{{ $subject->name }}</td>
                                <td>{{ $subject->kkm }}</td>
                                <td>{{ $subject->class_subject_teachers_count }} kelas</td>
                                <td>
                                    <a href="{{ route('data-master.subjects.edit', $subject) }}"
                                        class="btn btn-sm btn-outline-primary">Edit</a>

                                    @if ($subject->class_subject_teachers_count == 0)
                                        <form action="{{ route('data-master.subjects.destroy', $subject) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-danger" disabled
                                            title="Tidak bisa hapus, masih dipakai di {{ $subject->class_subject_teachers_count }} penugasan mengajar">
                                            Hapus
                                        </button>
                                    @endif
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
            $('#subjects-table').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ mata pelajaran",
                    infoEmpty: "Tidak ada data",
                    paginate: { previous: "Sebelumnya", next: "Selanjutnya" }
                }
            });
        });
    </script>
@endpush