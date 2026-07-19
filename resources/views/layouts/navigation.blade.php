<div class="sidebar text-white p-3">
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 mb-3 text-white text-decoration-none">
        <img src="{{ asset('images/logo.png') }}" alt="Kelaskita" class="sidebar-logo">
        <span class="fs-5 fw-bold">Kelaskita</span>
    </a>

    <ul class="nav nav-pills flex-column gap-1 mb-2">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
                class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
        </li>
    </ul>

    @php
        // Setiap role punya daftar grup menu: [judul_grup => [nama_menu => url]]
        $menuGroups = [];

        if (auth()->user()->hasRole('admin')) {
            $menuGroups = [
                'Data Master' => [
                    'Data Siswa' => route('data-master.students.index'),
                    'Data Guru' => route('data-master.teachers.index'),
                    'Data Kelas' => route('data-master.classes.index'),
                    'Mata Pelajaran' => route('data-master.subjects.index'),
                    'Tahun Ajaran' => route('data-master.school-years.index'),
                ],
                'Akademik' => [
                    'Rekap Nilai Sekolah' => '#',
                    'Rapor Digital' => '#',
                    'Bank Soal / Materi Ajar' => '#',
                    'RPP & Silabus' => '#',
                ],
                'Presensi' => [
                    'Presensi Siswa' => '#',
                    'Absensi Guru' => '#',
                    'Log Guru Pengganti' => '#',
                ],
                'Ujian & Evaluasi' => [
                    'Bank Soal Ujian' => '#',
                    'CBT / Kuis Online' => '#',
                    'Analisis Butir Soal' => '#',
                ],
                'Perilaku & Bimbingan' => [
                    'Pelanggaran' => '#',
                    'Prestasi' => '#',
                    'Buku Penghubung' => '#',
                    'Catatan BK' => '#',
                    'Profil Kesehatan' => '#',
                    'Minat & Bakat' => '#',
                ],
                'Administrasi' => [
                    'Surat Izin' => '#',
                    'Generate Surat' => '#',
                    'Jurnal Mengajar' => '#',
                    'Agenda Kelas' => '#',
                    'Piket Kelas' => '#',
                    'Perpustakaan' => '#',
                ],
                'Keuangan' => [
                    'Kas Kelas' => '#',
                    'Pembayaran SPP' => '#',
                ],
                'Komunikasi' => [
                    'Pengumuman' => '#',
                    'Pesan / Chat' => '#',
                    'Notifikasi' => '#',
                    'Survey Kepuasan' => '#',
                ],
                'Gamifikasi' => [
                    'Leaderboard' => '#',
                    'Badge Pencapaian' => '#',
                ],
                'Sistem' => [
                    'Manajemen User & Role' => '#',
                    'Log Aktivitas' => '#',
                    'Backup Data' => '#',
                ],
            ];
        } elseif (auth()->user()->hasRole('wali_kelas')) {
            $menuGroups = [
                'Kelas Saya' => [
                    'Data Siswa Kelas' => '#',
                    'Presensi Harian' => '#',
                    'Rekap Kehadiran' => '#',
                ],
                'Akademik' => [
                    'Rekap Nilai' => '#',
                    'Rapor Digital' => '#',
                ],
                'Perilaku & Bimbingan' => [
                    'Pelanggaran' => '#',
                    'Prestasi' => '#',
                    'Buku Penghubung' => '#',
                    'Profil Kesehatan' => '#',
                    'Minat & Bakat' => '#',
                ],
                'Administrasi' => [
                    'Surat Izin' => '#',
                    'Generate Surat' => '#',
                    'Jurnal Wali Kelas' => '#',
                    'Agenda Kelas' => '#',
                    'Piket Kelas' => '#',
                ],
                'Keuangan' => [
                    'Kas Kelas' => '#',
                ],
                'Komunikasi' => [
                    'Pengumuman' => '#',
                    'Pesan / Chat' => '#',
                ],
            ];
        } elseif (auth()->user()->hasRole('guru')) {
            $menuGroups = [
                'Akademik' => [
                    'Input Nilai' => '#',
                    'Materi Ajar' => '#',
                    'RPP' => '#',
                    'Silabus' => '#',
                    'Jadwal Mengajar' => '#',
                ],
                'Ujian & Evaluasi' => [
                    'Buat Soal Ujian' => '#',
                    'CBT / Kuis' => '#',
                    'Analisis Butir Soal' => '#',
                ],
                'Presensi' => [
                    'Presensi per Mapel' => '#',
                ],
                'Administrasi' => [
                    'Jurnal Mengajar' => '#',
                    'Catatan Hafalan' => '#',
                ],
                'Komunikasi' => [
                    'Pesan ke Wali Kelas' => '#',
                ],
            ];
        } elseif (auth()->user()->hasRole('siswa')) {
            $menuGroups = [
                'Akademik' => [
                    'Jadwal Pelajaran' => '#',
                    'Nilai & Rapor' => '#',
                    'Materi Ajar' => '#',
                    'Ujian / Kuis' => '#',
                ],
                'Administrasi' => [
                    'Ajukan Surat Izin' => '#',
                    'Agenda Kelas' => '#',
                ],
                'Perilaku' => [
                    'Poin Saya' => '#',
                    'Leaderboard' => '#',
                    'Badge Saya' => '#',
                ],
                'Perpustakaan' => [
                    'Peminjaman Buku' => '#',
                ],
                'Komunikasi' => [
                    'Pengumuman' => '#',
                ],
            ];
        } elseif (auth()->user()->hasRole('orang_tua')) {
            $menuGroups = [
                'Pantau Anak' => [
                    'Nilai & Rapor' => '#',
                    'Kehadiran' => '#',
                    'Poin & Prestasi' => '#',
                    'Jadwal Pelajaran' => '#',
                ],
                'Administrasi' => [
                    'Ajukan Surat Izin' => '#',
                    'Buku Penghubung' => '#',
                    'Kas & SPP' => '#',
                ],
                'Komunikasi' => [
                    'Pengumuman' => '#',
                    'Pesan / Chat' => '#',
                    'Survey Kepuasan' => '#',
                ],
            ];
        }

        // Mapping ikon untuk tiap judul grup menu (Bootstrap Icons)
        $groupIcons = [
            'Data Master' => 'bi-database',
            'Akademik' => 'bi-journal-bookmark',
            'Presensi' => 'bi-calendar2-check',
            'Ujian & Evaluasi' => 'bi-clipboard2-check',
            'Perilaku & Bimbingan' => 'bi-star',
            'Administrasi' => 'bi-file-earmark-text',
            'Keuangan' => 'bi-cash-coin',
            'Komunikasi' => 'bi-chat-dots',
            'Gamifikasi' => 'bi-trophy',
            'Sistem' => 'bi-shield-lock',
            'Kelas Saya' => 'bi-people',
            'Perilaku' => 'bi-star',
            'Perpustakaan' => 'bi-book',
            'Pantau Anak' => 'bi-eye',
        ];
    @endphp

    <div class="sidebar-accordion">
        @foreach ($menuGroups as $groupTitle => $items)
            @php
                $groupId = 'menu-' . Str::slug($groupTitle);
                $groupIcon = $groupIcons[$groupTitle] ?? 'bi-folder2';
            @endphp
            <div class="sidebar-group">
                <a href="#{{ $groupId }}" class="sidebar-group-toggle collapsed" data-bs-toggle="collapse"
                    role="button" aria-expanded="false" aria-controls="{{ $groupId }}">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi {{ $groupIcon }}"></i>
                        {{ $groupTitle }}
                    </span>
                    <i class="chevron"></i>
                </a>
                <div class="collapse" id="{{ $groupId }}">
                    <ul class="nav flex-column">
                        @foreach ($items as $label => $url)
                            <li class="nav-item">
                                <a href="{{ $url }}" class="nav-link">{{ $label }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</div>