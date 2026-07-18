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
            $gradeTrend = [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                'values' => [65, 70, 75, 78, 80, 82.6],
            ];
            $announcements = [
                ['icon' => 'bi-megaphone', 'color' => 'purple', 'title' => 'Libur Hari Raya Idul Adha', 'desc' => 'Kegiatan belajar mengajar diliburkan pada 17 Juni.', 'date' => '10 Jun 2026'],
                ['icon' => 'bi-calendar-event', 'color' => 'success', 'title' => 'Ujian Akhir Semester Genap', 'desc' => 'Ujian akan dilaksanakan mulai 2 - 10 Juni.', 'date' => '08 Jun 2026'],
                ['icon' => 'bi-cash-coin', 'color' => 'warning', 'title' => 'Pembayaran SPP Bulan Juni', 'desc' => 'Pembayaran paling lambat 10 Juni.', 'date' => '05 Jun 2026'],
                ['icon' => 'bi-people', 'color' => 'info', 'title' => 'Workshop Guru', 'desc' => 'Workshop peningkatan kompetensi guru akan diadakan...', 'date' => '03 Jun 2026'],
            ];
            $calToday = \Carbon\Carbon::now();
            $calFirstDay = $calToday->copy()->startOfMonth();
            $calDaysInMonth = $calToday->daysInMonth;
            $calStartOffset = $calFirstDay->dayOfWeekIso - 1; // 0 = Senin
            $calPrevMonthDays = $calFirstDay->copy()->subMonth()->daysInMonth;
        
            $calCells = [];
            for ($i = 0; $i < 42; $i++) {
                $dayOffset = $i - $calStartOffset + 1;
        
                if ($dayOffset < 1) {
                    $calCells[] = ['day' => $calPrevMonthDays + $dayOffset, 'current' => false, 'today' => false];
                } elseif ($dayOffset > $calDaysInMonth) {
                    $calCells[] = ['day' => $dayOffset - $calDaysInMonth, 'current' => false, 'today' => false];
                } else {
                    $calCells[] = ['day' => $dayOffset, 'current' => true, 'today' => $dayOffset === $calToday->day];
                }
            }
            $calWeeks = array_chunk($calCells, 7);
        
            $agenda = [
                [
                    'label' => $calToday->translatedFormat('d F Y') . ', Hari ini',
                    'items' => [
                        ['dot' => '#ffc107', 'title' => 'Ujian Praktik TIK', 'time' => '08:00 - 12:00'],
                    ],
                ],
                [
                    'label' => $calToday->copy()->addDays(2)->translatedFormat('d F Y'),
                    'items' => [
                        ['dot' => '#198754', 'title' => 'Pengumpulan Tugas Akhir', 'time' => '23:59'],
                    ],
                ],
                [
                    'label' => $calToday->copy()->addDays(6)->translatedFormat('d F Y'),
                    'items' => [
                        ['dot' => '#dc3545', 'title' => 'Rapat Wali Kelas', 'time' => '13:00 - 15:00'],
                    ],
                ],
            ];
            $activities = [
                ['icon' => 'bi-file-earmark-text', 'bg' => '#dbe9fe', 'fg' => '#0d6efd', 'bold' => 'Siti Aisyah', 'rest' => 'mengajukan surat izin', 'time' => '2 menit yang lalu'],
                ['icon' => 'bi-check-square', 'bg' => '#d4f5e2', 'fg' => '#198754', 'bold' => 'Budi Santoso', 'rest' => 'mengisi presensi kelas 10A - Matematika', 'time' => '15 menit yang lalu'],
                ['icon' => 'bi-journal-text', 'bg' => '#f1e9fc', 'fg' => '#6f42c1', 'bold' => '', 'rest' => 'Nilai UTS Matematika kelas 11 IPA telah dipublikasi', 'time' => '1 jam yang lalu'],
                ['icon' => 'bi-wallet2', 'bg' => '#ffe9d1', 'fg' => '#fd7e14', 'bold' => '', 'rest' => 'Pembayaran SPP oleh Andi Pratama', 'time' => '2 jam yang lalu'],
                ['icon' => 'bi-chat-dots', 'bg' => '#d1f3f7', 'fg' => '#0dcaf0', 'bold' => 'Rina Wati', 'rest' => 'mengirim pesan di buku penghubung', 'time' => '3 jam yang lalu'],
            ];
            $disciplinePoints = ['Positif' => 2450, 'Netral' => 550, 'Negatif' => 245];
            $disciplineColors = ['#198754', '#ffc107', '#dc3545'];
            $disciplineTotal = array_sum($disciplinePoints);
        @endphp

        <div class="row g-3 mb-4">
            <div class="col-lg-3">
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

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Nilai Rata-rata Sekolah</h6>
                            <select class="form-select form-select-sm w-auto">
                                <option>Semester Genap</option>
                                <option>Semester Ganjil</option>
                            </select>
                        </div>
            
                        <div class="chart-box" style="height: 200px;">
                            <canvas id="gradeChart"></canvas>
                        </div>
            
                        <a href="#" class="small d-inline-block mt-3 text-decoration-none">
                            Lihat Laporan Nilai →
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Pengumuman Terbaru</h6>
                            <a href="#" class="small text-decoration-none">Lihat Semua</a>
                        </div>
            
                        <ul class="list-unstyled mb-0">
                            @foreach ($announcements as $item)
                                <li class="d-flex gap-2 {{ !$loop->last ? 'border-bottom pb-2 mb-2' : '' }}">
                                    <div class="stat-icon bg-soft-{{ $item['color'] }} flex-shrink-0" style="width: 34px; height: 34px;">
                                        <i class="bi {{ $item['icon'] }}" style="font-size: .85rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold small">{{ $item['title'] }}</span>
                                            <span class="text-muted small text-nowrap ms-2">{{ $item['date'] }}</span>
                                        </div>
                                        <div class="text-muted small">{{ $item['desc'] }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Kalender Akademik</h6>
                            <a href="#" class="small text-decoration-none">Lihat Kalender</a>
                        </div>
            
                        <div class="d-flex gap-3 align-items-start">
                            <div class="mini-calendar flex-shrink-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <a href="#" class="text-muted small">&lsaquo;</a>
                                    <span class="fw-semibold small">{{ $calToday->translatedFormat('F Y') }}</span>
                                    <a href="#" class="text-muted small">&rsaquo;</a>
                                </div>
            
                                <table class="table table-sm text-center mb-0 mini-calendar-table">
                                    <thead>
                                        <tr class="text-muted">
                                            <th>S</th><th>S</th><th>R</th><th>K</th><th>J</th><th>S</th><th>M</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($calWeeks as $week)
                                            <tr>
                                                @foreach ($week as $cell)
                                                    <td class="{{ !$cell['current'] ? 'text-muted opacity-50' : '' }}">
                                                        @if ($cell['today'])
                                                            <span class="today-badge">{{ $cell['day'] }}</span>
                                                        @else
                                                            {{ $cell['day'] }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
            
                            <div class="agenda-box flex-grow-1 ps-2 border-start">
                                @foreach ($agenda as $group)
                                    <div class="mb-3">
                                        <div class="small fw-semibold {{ $loop->first ? 'text-primary' : 'text-dark' }} mb-1">
                                            {{ $group['label'] }}
                                        </div>
                                        @foreach ($group['items'] as $item)
                                            <div class="d-flex align-items-start gap-2 small">
                                                <span class="legend-dot mt-1" style="background: {{ $item['dot'] }};"></span>
                                                <div>
                                                    <div class="fw-semibold">{{ $item['title'] }}</div>
                                                    <div class="text-muted">{{ $item['time'] }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Aktivitas Terbaru</h6>
                            <a href="#" class="small text-decoration-none">Lihat Semua</a>
                        </div>
            
                        <ul class="list-unstyled mb-0">
                            @foreach ($activities as $activity)
                                <li class="d-flex gap-2 {{ !$loop->last ? 'mb-3' : '' }}">
                                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center"
                                        style="width: 34px; height: 34px; border-radius: 50%; background: {{ $activity['bg'] }}; color: {{ $activity['fg'] }};">
                                        <i class="bi {{ $activity['icon'] }}" style="font-size: .85rem;"></i>
                                    </div>
                                    <div>
                                        <div class="small">
                                            @if ($activity['bold'])
                                                <span class="fw-semibold">{{ $activity['bold'] }}</span>
                                            @endif
                                            {{ $activity['rest'] }}
                                        </div>
                                        <div class="text-muted small">{{ $activity['time'] }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Distribusi Poin Disiplin</h6>
                            <select class="form-select form-select-sm w-auto">
                                <option>Bulan Ini</option>
                                <option>Bulan Lalu</option>
                                <option>Semester Ini</option>
                            </select>
                        </div>
            
                        <div class="d-flex align-items-center gap-3">
                            <div class="donut-wrap flex-shrink-0">
                                <canvas id="disciplineChart"></canvas>
                                <div class="donut-center-text">
                                    <div class="text-muted" style="font-size: .72rem;">Total Poin</div>
                                    <div class="fw-bold fs-5">{{ number_format($disciplineTotal, 0, ',', '.') }}</div>
                                </div>
                            </div>
            
                            <div class="attendance-legend">
                                @foreach ($disciplinePoints as $label => $value)
                                    <div class="legend-row">
                                        <span class="d-flex align-items-center gap-2 small">
                                            <span class="legend-dot" style="background: {{ $disciplineColors[$loop->index] }}"></span>
                                            {{ $label }}
                                        </span>
                                        <span class="text-end">
                                            <span class="fw-semibold small">{{ number_format($value, 0, ',', '.') }}</span>
                                            <span class="text-muted" style="font-size: .75rem;">
                                                ({{ number_format($value / $disciplineTotal * 100, 1, ',', '.') }}%)
                                            </span>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
            
                        <a href="#" class="small d-inline-block mt-3 text-decoration-none">
                            Lihat Detail →
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

                // ============================
                // Attendance Doughnut Chart
                // ============================
                const attendanceCanvas = document.getElementById('attendanceChart');

                if (attendanceCanvas) {
                    new Chart(attendanceCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: @json(array_keys($attendanceBreakdown)),
                            datasets: [{
                                data: @json(array_values($attendanceBreakdown)),
                                backgroundColor: @json($attendanceColors),
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '75%',
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    enabled: true
                                }
                            }
                        }
                    });
                }

                // ============================
                // Discipline Doughnut Chart
                // ============================
                const disciplineCanvas = document.getElementById('disciplineChart');

                if (disciplineCanvas) {
                    new Chart(disciplineCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: @json(array_keys($disciplinePoints)),
                            datasets: [{
                                data: @json(array_values($disciplinePoints)),
                                backgroundColor: @json($disciplineColors),
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '75%',
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    enabled: true
                                }
                            }
                        }
                    });
                }

                // ============================
                // Grade Trend Line Chart
                // ============================
                const gradeCanvas = document.getElementById('gradeChart');

                if (gradeCanvas) {

                    const gradeValues = @json($gradeTrend['values']);
                    const lastValue = gradeValues[gradeValues.length - 1];

                    new Chart(gradeCanvas, {
                        type: 'line',

                        data: {
                            labels: @json($gradeTrend['labels']),
                            datasets: [{
                                label: 'Nilai Rata-rata',
                                data: gradeValues,
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13,110,253,.08)',
                                pointBackgroundColor: '#0d6efd',
                                pointRadius: 3,
                                tension: 0.4,
                                fill: true
                            }]
                        },

                        options: {
                            responsive: true,
                            maintainAspectRatio: false,

                            plugins: {
                                legend: {
                                    display: false
                                }
                            },

                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        stepSize: 20
                                    }
                                }
                            }
                        },

                        plugins: [{
                            id: 'lastPointBadge',

                            afterDatasetsDraw(chart) {

                                const meta = chart.getDatasetMeta(0);

                                if (!meta.data.length) return;

                                const point = meta.data[meta.data.length - 1];
                                const { ctx } = chart;
                                const { x, y } = point.tooltipPosition();

                                const value = String(lastValue).replace('.', ',');

                                ctx.save();

                                ctx.font = '600 11px sans-serif';

                                const padding = 14;
                                const boxHeight = 20;
                                const textWidth = ctx.measureText(value).width;
                                const boxWidth = textWidth + padding;

                                const boxX = x - boxWidth / 2;
                                const boxY = y - boxHeight - 10;

                                ctx.fillStyle = '#0d6efd';

                                ctx.beginPath();
                                ctx.roundRect(boxX, boxY, boxWidth, boxHeight, 4);
                                ctx.fill();

                                ctx.fillStyle = '#ffffff';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.fillText(value, x, boxY + boxHeight / 2);

                                ctx.restore();
                            }
                        }]
                    });
                }

            });
            </script>
            @endpush
    @endrole


    {{-- ==================== WALI KELAS ==================== --}}
    @role('wali_kelas')
        @unless ($stats['class'])
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Kamu belum ditugaskan sebagai wali kelas di kelas manapun. Hubungi admin untuk mengatur ini.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endunless

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
        @endif
    @endrole

    {{-- ==================== GURU ==================== --}}
    @role('guru')
        @if (!$stats['teacher'] || $stats['total_classes_taught'] == 0)
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
    @endrole

    {{-- ==================== SISWA ==================== --}}
    @role('siswa')
        @unless ($stats['student'])
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Data siswa kamu belum terhubung ke akun ini. Hubungi wali kelas atau admin.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endunless

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
        @endif
    @endrole

    {{-- ==================== ORANG TUA ==================== --}}
    @role('orang_tua')
        <h5 class="mb-3">Data Anak</h5>

        @if ($stats['children']->isEmpty())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Belum ada data anak yang terhubung ke akun ini. Hubungi admin sekolah untuk menautkan akun.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @foreach ($stats['children'] as $child)
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