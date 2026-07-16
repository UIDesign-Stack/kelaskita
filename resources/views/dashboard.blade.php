@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- ==================== ADMIN ==================== --}}
    @role('admin')
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total Siswa</h6>
                        <h3 class="mb-0">{{ $stats['total_students'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total Guru</h6>
                        <h3 class="mb-0">{{ $stats['total_teachers'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total Kelas</h6>
                        <h3 class="mb-0">{{ $stats['total_classes'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Tahun Ajaran Aktif</h6>
                        <h5 class="mb-0">
                            {{ $stats['active_school_year']->name ?? '-' }}
                            @if ($stats['active_school_year'])
                                <span class="badge text-bg-success">{{ $stats['active_school_year']->semester }}</span>
                            @endif
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        @if (!$stats['active_school_year'])
            <div class="alert alert-warning">
                Belum ada tahun ajaran yang diaktifkan. Silakan tambahkan data tahun ajaran terlebih dahulu.
            </div>
        @endif
    @endrole

    {{-- ==================== WALI KELAS ==================== --}}
    @role('wali_kelas')
        @if ($stats['class'])
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Kelas Diampu</h6>
                            <h3 class="mb-0">{{ $stats['class']->name }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Jumlah Siswa</h6>
                            <h3 class="mb-0">{{ $stats['total_students'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Tahun Ajaran</h6>
                            <h5 class="mb-0">{{ $stats['class']->schoolYear->name ?? '-' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                Kamu belum ditugaskan sebagai wali kelas di kelas manapun. Hubungi admin untuk mengatur ini.
            </div>
        @endif
    @endrole

    {{-- ==================== GURU ==================== --}}
    @role('guru')
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Kelas Diajar</h6>
                        <h3 class="mb-0">{{ $stats['total_classes_taught'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Mata Pelajaran Diampu</h6>
                        <h3 class="mb-0">{{ $stats['total_subjects'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Spesialisasi</h6>
                        <h5 class="mb-0">{{ $stats['teacher']->specialization ?? '-' }}</h5>
                    </div>
                </div>
            </div>
        </div>

        @if (!$stats['teacher'] || $stats['total_classes_taught'] == 0)
            <div class="alert alert-warning">
                Kamu belum ditugaskan mengajar di kelas/mapel manapun. Hubungi admin untuk mengatur jadwal mengajar.
            </div>
        @endif
    @endrole

    {{-- ==================== SISWA ==================== --}}
    @role('siswa')
        @if ($stats['student'])
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">NIS</h6>
                            <h4 class="mb-0">{{ $stats['student']->nis }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Kelas</h6>
                            <h4 class="mb-0">{{ $stats['class']->name ?? '-' }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Status</h6>
                            <span class="badge text-bg-success text-capitalize">{{ $stats['student']->status }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                Data siswa kamu belum terhubung ke akun ini. Hubungi wali kelas atau admin.
            </div>
        @endif
    @endrole

    {{-- ==================== ORANG TUA ==================== --}}
    @role('orang_tua')
        <h5 class="mb-3">Data Anak</h5>

        @forelse ($stats['children'] as $child)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ $child->user->name ?? $child->nis }}</h5>
                        <span class="text-muted small">NIS: {{ $child->nis }}</span>
                    </div>
                    <div class="text-end">
                        <span class="badge text-bg-primary">{{ $child->schoolClass->name ?? 'Belum ada kelas' }}</span>
                        <div class="small text-muted mt-1 text-capitalize">{{ $child->status }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-warning">
                Belum ada data anak yang terhubung ke akun ini. Hubungi admin sekolah untuk menautkan akun.
            </div>
        @endforelse
    @endrole

@endsection