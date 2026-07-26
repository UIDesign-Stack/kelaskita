<div class="sidebar text-white p-3">
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 text-white text-decoration-none">
        <span class="fs-5 fw-bold">Kelaskita</span>
    </a>

    <ul class="nav nav-pills flex-column gap-1 mb-2">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
        </li>
    </ul>

    @php
        $user = auth()->user();
        $roleMenus = [];

        $roleMenus['admin'] = [
            'Data Master' => [
                'Data Siswa' => route('data-master.students.index'),
                'Data Guru' => route('data-master.teachers.index'),
                'Data Kelas' => route('data-master.classes.index'),
                'Mata Pelajaran' => route('data-master.subjects.index'),
                'Tahun Ajaran' => route('data-master.school-years.index'),
                'Jenis Dokumen' => route('data-master.document-types.index'),
            ],
            'Akademik' => [
                'Rekap Nilai Sekolah' => route('akademik.grades.index'),
                'Rapor Digital' => route('akademik.report-cards.index'),
                'Bank Soal / Materi Ajar' => route('akademik.materials.index'),
                'Dokumen Mengajar (CP/ATP/Modul Ajar)' => route('akademik.documents.index'),
            ],
            'Presensi' => [
                'Presensi Siswa' => route('presensi.attendances.index'),
                'Absensi Guru' => route('presensi.teacher-attendances.index'),
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

        $roleMenus['wali_kelas'] = [
            'Kelas Saya' => [
                'Data Siswa Kelas' => '#',
                'Presensi Harian' => route('wali-kelas.attendance.index'),
                'Rekap Kehadiran' => route('wali-kelas.attendance.recap'),
            ],
            'Akademik (Wali Kelas)' => [
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
            'Administrasi (Wali Kelas)' => [
                'Surat Izin' => '#',
                'Generate Surat' => '#',
                'Jurnal Wali Kelas' => '#',
                'Agenda Kelas' => '#',
                'Piket Kelas' => '#',
            ],
            'Keuangan' => [
                'Kas Kelas' => '#',
            ],
            'Komunikasi (Wali Kelas)' => [
                'Pengumuman' => '#',
                'Pesan / Chat' => '#',
            ],
        ];

        $roleMenus['guru'] = [
            'Akademik' => [
                'Input Nilai' => route('guru.grade-input.index'),
                'Materi Ajar' => route('guru.materials.index'),
                'Dokumen Mengajar' => route('guru.documents.index'),
                'Jadwal Mengajar' => '#',
            ],
            'Ujian & Evaluasi' => [
                'Buat Soal Ujian' => '#',
                'CBT / Kuis' => '#',
                'Analisis Butir Soal' => '#',
            ],
            'Presensi' => [
                'Presensi per Mapel' => route('guru.attendance.index'),
            ],
            'Administrasi' => [
                'Jurnal Mengajar' => '#',
                'Catatan Hafalan' => '#',
            ],
            'Komunikasi' => [
                'Pesan ke Wali Kelas' => '#',
            ],
        ];

        $roleMenus['siswa'] = [
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

        $roleMenus['orang_tua'] = [
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
        $menuGroups = [];

        if ($user->hasRole('admin')) {
            $menuGroups = $roleMenus['admin'];
        } else {
            foreach (['wali_kelas', 'guru', 'siswa', 'orang_tua'] as $roleName) {
                if ($user->hasRole($roleName)) {
                    foreach ($roleMenus[$roleName] as $groupTitle => $items) {
                        $menuGroups[$groupTitle] = array_merge($menuGroups[$groupTitle] ?? [], $items);
                    }
                }
            }
        }
    @endphp

    <div class="sidebar-accordion">
        @foreach ($menuGroups as $groupTitle => $items)
            @php $groupId = 'menu-' . Str::slug($groupTitle); @endphp
            <div class="sidebar-group">
                <a href="#{{ $groupId }}" class="sidebar-group-toggle collapsed" data-bs-toggle="collapse"
                    role="button" aria-expanded="false" aria-controls="{{ $groupId }}">
                    <span>{{ $groupTitle }}</span>
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