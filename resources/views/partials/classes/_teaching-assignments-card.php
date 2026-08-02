<div class="card shadow-sm border-0 mt-3">
    <div class="card-body">
        <h6 class="fw-semibold mb-3">Mata Pelajaran Diajarkan</h6>

        @if ($errors->has('subject_id'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first('subject_id') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($class->teachingAssignments->isEmpty())
            <div class="alert alert-warning mb-3">
                Belum ada guru mata pelajaran yang ditugaskan di kelas ini.
            </div>
        @else
            <ul class="list-unstyled small mb-3">
                @foreach ($class->teachingAssignments as $assignment)
                    <li class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <span>
                            {{ $assignment->subject->name ?? '-' }}
                            <span class="text-muted"> — {{ $assignment->teacher->user->name ?? '-' }}</span>
                        </span>
                        <form action="{{ route('data-master.teaching-assignments.destroy', $assignment) }}"
                            method="POST" onsubmit="return confirm('Hapus guru pengampu ini dari kelas?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1">×</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('data-master.classes.teaching-assignments.store', $class) }}" method="POST">
            @csrf
            <div class="row g-2">
                <div class="col-12">
                    <select name="subject_id" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <select name="teacher_id" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->user->name ?? '-' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-sm btn-primary w-100">+ Tambah Guru Pengampu</button>
                </div>
            </div>
        </form>
    </div>
</div>