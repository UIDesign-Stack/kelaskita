@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Siswa</h4>
        <a href="{{ route('data-master.students.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('data-master.students.store') }}" enctype="multipart/form-data">
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
                                <label for="nis" class="form-label">NIS</label>
                                <input type="text" id="nis" name="nis" value="{{ old('nis') }}"
                                    class="form-control @error('nis') is-invalid @enderror" required>
                                @error('nis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="nisn" class="form-label">NISN</label>
                        <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}"
                            class="form-control @error('nisn') is-invalid @enderror">
                        @error('nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="class_id" class="form-label">Kelas</label>
                        <select id="class_id" name="class_id"
                            class="form-select @error('class_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="gender" class="form-label">Jenis Kelamin</label>
                        <select id="gender" name="gender"
                            class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            <option value="L" @selected(old('gender') == 'L')>Laki-laki</option>
                            <option value="P" @selected(old('gender') == 'P')>Perempuan</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status"
                            class="form-select @error('status') is-invalid @enderror" required>
                            <option value="aktif" @selected(old('status', 'aktif') == 'aktif')>Aktif</option>
                            <option value="pindah" @selected(old('status') == 'pindah')>Pindah</option>
                            <option value="lulus" @selected(old('status') == 'lulus')>Lulus</option>
                            <option value="keluar" @selected(old('status') == 'keluar')>Keluar</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="birth_place" class="form-label">Tempat Lahir</label>
                        <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place') }}"
                            class="form-control @error('birth_place') is-invalid @enderror">
                        @error('birth_place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="birth_date" class="form-label">Tanggal Lahir</label>
                        <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                            class="form-control @error('birth_date') is-invalid @enderror">
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea id="address" name="address" rows="2"
                            class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <h6 class="text-muted mb-3">Akun Login (Opsional)</h6>

                <div class="form-check mb-3">
                    <input type="checkbox" id="create_account" name="create_account" value="1"
                        class="form-check-input" {{ old('create_account') ? 'checked' : '' }}
                        onchange="document.getElementById('account-fields').classList.toggle('d-none', !this.checked)">
                    <label class="form-check-label" for="create_account">
                        Buatkan akun login untuk siswa ini
                    </label>
                </div>

                <div id="account-fields" class="row g-3 mb-4 {{ old('create_account') ? '' : 'd-none' }}">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Minimal 8 karakter.</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('data-master.students.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Siswa</button>
                </div>
            </form>
        </div>
    </div>

@endsection