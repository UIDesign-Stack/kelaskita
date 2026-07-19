@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Siswa</h4>
        <a href="{{ route('data-master.students.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('data-master.students.update', $student) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h6 class="text-muted mb-3">Data Diri</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Foto Saat Ini</label>
                        <div>
                            <img src="{{ $student->photoUrl() }}" alt="{{ $student->name }}"
                                class="rounded-circle mb-2" width="70" height="70" style="object-fit: cover;">
                        </div>
                        <input type="file" id="photo" name="photo" accept="image/png, image/jpeg"
                            class="form-control form-control-sm @error('photo') is-invalid @enderror">
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Kosongkan jika tidak ingin mengganti foto.</div>
                    </div>

                    <div class="col-md-9">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}"
                                    class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="nis" class="form-label">NIS</label>
                                <input type="text" id="nis" name="nis" value="{{ old('nis', $student->nis) }}"
                                    class="form-control @error('nis') is-invalid @enderror" required>
                                @error('nis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="nisn" class="form-label">NISN</label>
                                <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $student->nisn) }}"
                                    class="form-control @error('nisn') is-invalid @enderror">
                                @error('nisn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="class_id" class="form-label">Kelas</label>
                        <select id="class_id" name="class_id"
                            class="form-select @error('class_id') is-invalid @enderror" required>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected(old('class_id', $student->class_id) == $class->id)>
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
                            <option value="L" @selected(old('gender', $student->gender) == 'L')>Laki-laki</option>
                            <option value="P" @selected(old('gender', $student->gender) == 'P')>Perempuan</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status"
                            class="form-select @error('status') is-invalid @enderror" required>
                            @foreach (['aktif', 'pindah', 'lulus', 'keluar'] as $statusOption)
                                <option value="{{ $statusOption }}" @selected(old('status', $student->status) == $statusOption)>
                                    {{ ucfirst($statusOption) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="birth_place" class="form-label">Tempat Lahir</label>
                        <input type="text" id="birth_place" name="birth_place"
                            value="{{ old('birth_place', $student->birth_place) }}"
                            class="form-control @error('birth_place') is-invalid @enderror">
                        @error('birth_place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="birth_date" class="form-label">Tanggal Lahir</label>
                        <input type="date" id="birth_date" name="birth_date"
                            value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}"
                            class="form-control @error('birth_date') is-invalid @enderror">
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea id="address" name="address" rows="2"
                            class="form-control @error('address') is-invalid @enderror">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <h6 class="text-muted mb-3">Akun Login</h6>

                @if ($student->user)
                    {{-- Sudah punya akun: tinggal update --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $student->user->email) }}"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Kosongkan jika tidak ingin mengganti password.</div>
                        </div>
                    </div>
                @else
                    {{-- Belum punya akun: opsi buat sekarang --}}
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
                @endif

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('data-master.students.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

@endsection