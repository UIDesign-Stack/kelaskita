@extends('layouts.app')

@section('title', 'Tambah Guru')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Guru</h4>
        <a href="{{ route('data-master.teachers.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('data-master.teachers.store') }}" enctype="multipart/form-data">
                @csrf

                <h6 class="text-muted mb-3">Data Diri</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="photo" class="form-label">Foto</label>
                        <input type="file" id="photo" name="photo" accept="image/png, image/jpeg"
                            class="form-control @error('photo') is-invalid @enderror">
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format JPG/PNG, maksimal 2MB.</div>
                    </div>

                    <div class="col-md-9">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="nuptk" class="form-label">NUPTK <span class="text-muted">(opsional)</span></label>
                                <input type="text" id="nuptk" name="nuptk" value="{{ old('nuptk') }}"
                                    class="form-control @error('nuptk') is-invalid @enderror">
                                @error('nuptk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="specialization" class="form-label">Spesialisasi / Bidang Studi</label>
                        <input type="text" id="specialization" name="specialization"
                            value="{{ old('specialization') }}" placeholder="Contoh: Matematika, IPA, Bahasa Inggris"
                            class="form-control @error('specialization') is-invalid @enderror">
                        @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <h6 class="text-muted mb-3">Akun Login</h6>
                <div class="alert alert-info small">
                    Setiap guru wajib memiliki akun login untuk mengakses sistem.
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Minimal 8 karakter.</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('data-master.teachers.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Guru</button>
                </div>
            </form>
        </div>
    </div>

@endsection