<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="mb-1">{{ $class->name }}</h5>
        <div class="text-muted small mb-3">Tingkat {{ $class->grade_level }}</div>

        <ul class="list-unstyled small mb-0">
            <li class="d-flex justify-content-between py-1 border-bottom">
                <span class="text-muted">Wali Kelas</span>
                <span class="fw-semibold">{{ $class->homeroomTeacher->user->name ?? 'Belum ditentukan' }}</span>
            </li>
            <li class="d-flex justify-content-between py-1 border-bottom">
                <span class="text-muted">Tahun Ajaran</span>
                <span class="fw-semibold">{{ $class->schoolYear->name ?? '-' }}</span>
            </li>
            <li class="d-flex justify-content-between py-1 border-bottom">
                <span class="text-muted">Semester</span>
                <span class="fw-semibold text-capitalize">{{ $class->schoolYear->semester ?? '-' }}</span>
            </li>
            <li class="d-flex justify-content-between py-1">
                <span class="text-muted">Jumlah Siswa</span>
                <span class="fw-semibold">{{ $class->students->count() }} siswa</span>
            </li>
        </ul>
    </div>
</div>