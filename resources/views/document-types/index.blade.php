@extends('layouts.app')

@section('title', 'Jenis Dokumen')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Jenis Dokumen Mengajar</h4>
    </div>

    <div class="alert alert-info">
        Halaman ini mengatur jenis dokumen yang bisa di-upload guru (RPP, Silabus, CP, ATP, Modul Ajar, dst).
        Kalau kebijakan kurikulum berganti, cukup tambah/ubah/nonaktifkan di sini — <strong>tidak perlu ubah kode aplikasi</strong>.
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

    <div class="row g-3">
        {{-- ===== Form Tambah ===== --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Tambah Jenis Dokumen</h6>
                    <form method="POST" action="{{ route('data-master.document-types.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Contoh: Modul Ajar"
                                class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kode (unik, huruf kecil, tanpa spasi)</label>
                            <input type="text" name="code" value="{{ old('code') }}"
                                placeholder="Contoh: modul_ajar"
                                class="form-control @error('code') is-invalid @enderror" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="requires_semester" value="1" class="form-check-input"
                                id="requires_semester" {{ old('requires_semester') ? 'checked' : '' }}>
                            <label class="form-check-label" for="requires_semester">
                                Perlu pilih semester saat upload
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Tambah</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===== Daftar ===== --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>Butuh Semester?</th>
                                    <th>Jumlah Dokumen</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documentTypes as $type)
                                    <tr>
                                        <td>{{ $type->name }}</td>
                                        <td><code>{{ $type->code }}</code></td>
                                        <td>{{ $type->requires_semester ? 'Ya' : 'Tidak' }}</td>
                                        <td>{{ $type->documents_count }}</td>
                                        <td>
                                            <span class="badge {{ $type->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                                {{ $type->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="collapse" data-bs-target="#edit-{{ $type->id }}">
                                                Edit
                                            </button>
                                            @if ($type->documents_count == 0)
                                                <form action="{{ route('data-master.document-types.destroy', $type) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Hapus jenis dokumen ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="edit-{{ $type->id }}">
                                        <td colspan="6" class="bg-light">
                                            <form method="POST" action="{{ route('data-master.document-types.update', $type) }}"
                                                class="row g-2 align-items-end py-2">
                                                @csrf
                                                @method('PUT')
                                                <div class="col-md-4">
                                                    <input type="text" name="name" value="{{ $type->name }}"
                                                        class="form-control form-control-sm" required>
                                                </div>
                                                <div class="col-md-3 form-check">
                                                    <input type="checkbox" name="requires_semester" value="1"
                                                        class="form-check-input" {{ $type->requires_semester ? 'checked' : '' }}>
                                                    <label class="form-check-label small">Butuh semester</label>
                                                </div>
                                                <div class="col-md-3 form-check">
                                                    <input type="checkbox" name="is_active" value="1"
                                                        class="form-check-input" {{ $type->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label small">Aktif</label>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="submit" class="btn btn-sm btn-primary w-100">Simpan</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection