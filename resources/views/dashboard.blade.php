@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- ==================== ADMIN ==================== --}}
    @role('admin')

        @php
            // ===== DATA DUMMY untuk teks trend — ganti dengan data asli setelah modul terkait dibuat =====
            $newStudentsThisMonth = 12;
            $newTeachersThisMonth = 3;
            $attendanceRate = 92.4;
            $attendanceTrend = 4.6;
        @endphp

        @if (!$stats['active_school_year'])
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Belum ada tahun ajaran yang diaktifkan. Silakan tambahkan data tahun ajaran terlebih dahulu.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="text-muted mb-0">Total Siswa</h6>
                            <div class="stat-icon bg-soft-blue">
                                <i class="bi bi-people fs-5"></i>
                            </div>
                        </div>
                        <h3 class="mb-1">{{ number_format($stats['total_students']) }}</h3>
                        <div class="small text-success">
                            <i class="bi bi-arrow-up"></i> {{ $newStudentsThisMonth }} siswa dari bulan lalu
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="text-muted mb-0">Total Guru & Staf</h6>
                            <div class="stat-icon bg-soft-green">
                                <i class="bi bi-person-badge fs-5"></i>
                            </div>
                        </div>
                        <h3 class="mb-1">{{ number_format($stats['total_teachers']) }}</h3>
                        <div class="small text-success">
                            <i class="bi bi-arrow-up"></i> {{ $newTeachersThisMonth }} guru dari bulan lalu
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="text-muted mb-0">Total Kelas</h6>
                            <div class="stat-icon bg-soft-purple">
                                <i class="bi bi-building fs-5"></i>
                            </div>
                        </div>
                        <h3 class="mb-1">{{ number_format($stats['total_classes']) }}</h3>
                        <div class="small text-muted">
                            <i class="bi bi-dash"></i> Sama seperti bulan lalu
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="text-muted mb-0">Tingkat Kehadiran</h6>
                            <div class="stat-icon bg-soft-orange">
                                <i class="bi bi-bar-chart-line fs-5"></i>
                            </div>
                        </div>
                        <h3 class="mb-1">{{ $attendanceRate }}%</h3>
                        <div class="small text-success">
                            <i class="bi bi-arrow-up"></i> {{ $attendanceTrend }}% dari bulan lalu
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Card 1: Ringkasan Kehadiran Siswa ===== --}}
        @php
            $attendanceBreakdown = ['Hadir' => 1150, 'Izin' => 56, 'Sakit' => 24, 'Alpa' => 15];
            $attendanceColors = ['#198754', '#0d6efd', '#ffc107', '#dc3545'];
            $attendanceTotal = array_sum($attendanceBreakdown);
        @endphp

        <div class="row g-3 mb-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Ringkasan Kehadiran Siswa</h6>
                            <select class="form-select form-select-sm w-auto">
                                <option>Bulan Ini</option>
                                <option>Bulan Lalu</option>
                                <option>Semester Ini</option>
                            </select>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <div class="donut-wrap flex-shrink-0">
                                <canvas id="attendanceChart"></canvas>
                                <div class="donut-center-text">
                                    <div class="fw-bold fs-5">{{ number_format($attendanceRate, 1, ',', '.') }}%</div>
                                    <div class="text-muted" style="font-size: .72rem;">Kehadiran</div>
                                </div>
                            </div>

                            <div class="attendance-legend">
                                @foreach ($attendanceBreakdown as $label => $value)
                                    <div class="legend-row">
                                        <span class="d-flex align-items-center gap-2 small">
                                            <span class="legend-dot" style="background: {{ $attendanceColors[$loop->index] }}"></span>
                                            {{ $label }}
                                        </span>
                                        <span class="text-end">
                                            <span class="fw-semibold small">{{ number_format($value, 0, ',', '.') }}</span>
                                            <span class="text-muted" style="font-size: .75rem;">
                                                ({{ number_format($value / $attendanceTotal * 100, 1, ',', '.') }}%)
                                            </span>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <a href="#" class="small d-inline-block mt-3 text-decoration-none">
                            Lihat Laporan Lengkap →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (typeof Chart === 'undefined') {
                        console.warn('Chart.js belum ter-load. Cek import di resources/js/app.js');
                        return;
                    }

                    new Chart(document.getElementById('attendanceChart'), {
                        type: 'doughnut',
                        data: {
                            labels: @json(array_keys($attendanceBreakdown)),
                            datasets: [{
                                data: @json(array_values($attendanceBreakdown)),
                                backgroundColor: @json($attendanceColors),
                                borderWidth: 0,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '75%',
                            plugins: { legend: { display: false }, tooltip: { enabled: true } },
                        },
                    });
                });
            </script>
        @endpush
    @endrole


    {{-- ==================== WALI KELAS ==================== --}}
    @role('wali_kelas')
        @unless ($stats['wali_kelas']['class'])
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Kamu belum ditugaskan sebagai wali kelas di kelas manapun. Hubungi admin untuk mengatur ini.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endunless

        @if ($stats['wali_kelas']['class'])
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Kelas Diampu</h6>
                            <h3 class="mb-0">{{ $stats['wali_kelas']['class']->name }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Jumlah Siswa</h6>
                            <h3 class="mb-0">{{ $stats['wali_kelas']['total_students'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Tahun Ajaran</h6>
                            <h5 class="mb-0">{{ $stats['wali_kelas']['class']->schoolYear->name ?? '-' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endrole

    {{-- ==================== GURU ==================== --}}
    @role('guru')
        @if (!$stats['guru']['teacher'] || $stats['guru']['total_classes_taught'] == 0)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Kamu belum ditugaskan mengajar di kelas/mapel manapun. Hubungi admin untuk mengatur jadwal mengajar.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Kelas Diajar</h6>
                        <h3 class="mb-0">{{ $stats['guru']['total_classes_taught'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Mata Pelajaran Diampu</h6>
                        <h3 class="mb-0">{{ $stats['guru']['total_subjects'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Spesialisasi</h6>
                        <h5 class="mb-0">{{ $stats['guru']['teacher']->specialization ?? '-' }}</h5>
                    </div>
                </div>
            </div>
        </div>
    @endrole

    {{-- ==================== SISWA ==================== --}}
    @role('siswa')
        @unless ($stats['siswa']['student'])
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Data siswa kamu belum terhubung ke akun ini. Hubungi wali kelas atau admin.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endunless

        @if ($stats['siswa']['student'])
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">NIS</h6>
                            <h4 class="mb-0">{{ $stats['siswa']['student']->nis }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Kelas</h6>
                            <h4 class="mb-0">{{ $stats['siswa']['class']->name ?? '-' }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Status</h6>
                            <span class="badge text-bg-success text-capitalize">{{ $stats['siswa']['student']->status }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endrole

    {{-- ==================== ORANG TUA ==================== --}}
    @role('orang_tua')
        <h5 class="mb-3">Data Anak</h5>

        @if ($stats['orang_tua']['children']->isEmpty())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Belum ada data anak yang terhubung ke akun ini. Hubungi admin sekolah untuk menautkan akun.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @foreach ($stats['orang_tua']['children'] as $child)
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
        @endforeach
    @endrole

@endsection