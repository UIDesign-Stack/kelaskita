<div class="sidebar text-white p-3">
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-4 text-white text-decoration-none">
        <span class="fs-5 fw-bold">Kelaskita</span>
    </a>

    <ul class="nav nav-pills flex-column gap-1">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
        </li>

        @role('admin')
            <li class="nav-item"><a href="#" class="nav-link">Data Guru</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Data Siswa</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Data Kelas</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Laporan Sekolah</a></li>
        @endrole

        @role('wali_kelas')
            <li class="nav-item"><a href="#" class="nav-link">Presensi Kelas</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Rapor Digital</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Surat Izin</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Kas Kelas</a></li>
        @endrole

        @role('guru')
            <li class="nav-item"><a href="#" class="nav-link">Input Nilai</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Materi Ajar</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Jurnal Mengajar</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Ujian / CBT</a></li>
        @endrole

        @role('siswa')
            <li class="nav-item"><a href="#" class="nav-link">Nilai Saya</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Jadwal Pelajaran</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Ajukan Izin</a></li>
        @endrole

        @role('orang_tua')
            <li class="nav-item"><a href="#" class="nav-link">Progres Anak</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Buku Penghubung</a></li>
        @endrole
    </ul>
</div>