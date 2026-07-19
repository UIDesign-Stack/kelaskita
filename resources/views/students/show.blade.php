@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Detail Siswa</h4>
        <a href="{{ route('data-master.students.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="row g-3">
        {{-- ===== Kolom Kiri: Foto & Info Utama ===== --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <img src="{{ $student->photoUrl() }}" alt="{{ $student->name }}"
                        class="rounded-circle mb-3" width="110" height="110" style="object-fit: cover;">

                    <h5 class="mb-1">{{ $student->name }}</h5>
                    <div class="text-muted small mb-2">NIS: {{ $student->nis }}</div>

                    @php
                        $statusColor = match ($student->status) {
                            'aktif' => 'success',
                            'pindah' => 'warning',
                            'lulus' => 'primary',
                            'keluar' => 'secondary',
                            default => 'secondary',
                        };
                    @endphp
                    <span class="badge text-bg-{{ $statusColor }} text-capitalize">{{ $student->status }}</span>

                    <hr>

                    <ul class="list-unstyled text-start small mb-0">
                        <li class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">NISN</span>
                            <span class="fw-semibold">{{ $student->nisn ?? '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">Kelas</span>
                            <span class="fw-semibold">{{ $student->schoolClass->name ?? '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">Jenis Kelamin</span>
                            <span class="fw-semibold">{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">Tempat, Tgl Lahir</span>
                            <span class="fw-semibold text-end">
                                {{ $student->birth_place ?? '-' }},
                                {{ $student->birth_date?->translatedFormat('d F Y') ?? '-' }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-1">
                            <span class="text-muted">Status Akun</span>
                            @if ($student->user)
                                <span class="badge text-bg-success">Punya Akun</span>
                            @else
                                <span class="badge text-bg-secondary">Belum Ada Akun</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- ===== Kolom Kanan: Alamat, Akun, Orang Tua ===== --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Alamat</h6>
                    <p class="mb-0 text-muted">{{ $student->address ?? 'Belum ada data alamat.' }}</p>
                </div>
            </div>

            @if ($student->user)
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Akun Login</h6>
                        <ul class="list-unstyled small mb-0">
                            <li class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">Email</span>
                                <span class="fw-semibold">{{ $student->user->email }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1">
                                <span class="text-muted">Status</span>
                                <span class="badge {{ $student->user->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $student->user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Orang Tua / Wali</h6>

                    @if ($student->guardians->isEmpty())
                        <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                            Belum ada data orang tua/wali yang terhubung ke siswa ini.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Hubungan</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($student->guardians as $guardian)
                                        <tr>
                                            <td>{{ $guardian->user->name ?? '-' }}</td>
                                            <td class="text-capitalize">{{ $guardian->relationship }}</td>
                                            <td>{{ $guardian->user->email ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection