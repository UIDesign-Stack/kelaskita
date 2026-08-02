<div class="card shadow-sm border-0">
    <div class="card-body">
        <h6 class="fw-semibold mb-3">Daftar Siswa di Kelas Ini</h6>

        @if ($class->students->isEmpty())
            <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                Belum ada siswa yang terdaftar di kelas ini.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($class->students as $student)
                            <tr>
                                <td>{{ $student->nis }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>
                                    <span class="badge text-bg-secondary text-capitalize">{{ $student->status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('data-master.students.show', $student) }}"
                                        class="btn btn-sm btn-outline-secondary">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>