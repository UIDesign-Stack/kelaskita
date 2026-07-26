<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KelasKita</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/sass/app.scss'])
</head>
<body>

    <!-- ===== Navbar ===== -->
    <nav class="navbar navbar-expand-lg navbar-kk sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">KelasKita</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#kkNav" style="border-color: rgba(245,241,232,.4);">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="kkNav">
                <ul class="navbar-nav mx-auto mt-3 mt-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kenapa">Kenapa KelasKita</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimoni">Testimoni</a></li>
                    <li class="nav-item"><a class="nav-link" href="#gabung">Mulai</a></li>
                </ul>
                <div class="d-flex gap-2 mt-3 mt-lg-0">
                    <a href="{{ route('login') ?? '#' }}" class="btn btn-chalk-outline">Masuk</a>
                    <a href="{{ route('register') ?? '#' }}" class="btn btn-coral">Coba Gratis</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===== Hero ===== -->
    <header class="hero-board">
        <!-- chalk doodles -->
        <svg class="hero-doodle d-none d-lg-block" style="top: 90px; right: 8%; width: 90px;" viewBox="0 0 100 100" fill="none">
            <path d="M20 70 Q40 20 80 30" stroke="#F2C14E" stroke-width="2.5" stroke-linecap="round"/>
            <path d="M70 22 L80 30 L70 38" stroke="#F2C14E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </svg>
        <svg class="hero-doodle d-none d-lg-block" style="bottom: 40px; right: 18%; width: 44px;" viewBox="0 0 40 40" fill="none">
            <path d="M20 2 L23 16 L37 16 L26 25 L30 39 L20 30 L10 39 L14 25 L3 16 L17 16 Z" stroke="#F5F1E8" stroke-width="1.8" fill="none"/>
        </svg>

        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="hero-title">
                        Satu sistem,<br>
                        pantau
                        <span class="underline-word">semua murid
                            <svg viewBox="0 0 220 18" preserveAspectRatio="none"><path d="M2 10 Q55 2 110 9 T218 8" stroke="#E8623D" stroke-width="4" fill="none" stroke-linecap="round"/></svg>
                        </span><br>
                        tanpa ribet.
                    </h1>
                    <p class="hero-sub">
                        Absensi, rapor, dan perkembangan siswa jadi satu di dashboard — memudahkan wali kelas, guru mapel, dan staf sekolah memantau tiap murid secara real-time.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#gabung" class="btn btn-coral btn-lg px-4">Coba KelasKita Gratis</a>
                        <a href="#fitur" class="btn btn-chalk-outline btn-lg px-4">Lihat Fitur</a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block text-center">
                    <svg viewBox="0 0 320 260" width="100%">
                        <rect x="10" y="10" width="300" height="200" rx="10" fill="none" stroke="#F5F1E8" stroke-opacity="0.5" stroke-width="2"/>
                        <line x1="30" y1="60" x2="180" y2="60" stroke="#F2C14E" stroke-width="4" stroke-linecap="round"/>
                        <line x1="30" y1="90" x2="220" y2="90" stroke="#F5F1E8" stroke-opacity="0.6" stroke-width="3" stroke-linecap="round"/>
                        <line x1="30" y1="118" x2="150" y2="118" stroke="#F5F1E8" stroke-opacity="0.6" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="250" cy="150" r="34" stroke="#E8623D" stroke-width="3" fill="none"/>
                        <path d="M236 150 l10 10 l20 -24" stroke="#E8623D" stroke-width="3.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        <line x1="30" y1="230" x2="120" y2="230" stroke="#5cc2e0" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>
    </header>

    <!-- ===== Stat strip ===== -->
    <div class="stat-strip">
        <div class="container">
            <div class="row gx-0">
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-num">120+</div>
                    <div class="stat-label">Sekolah pengguna</div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-num">5.000+</div>
                    <div class="stat-label">Guru & wali kelas aktif</div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-num">80rb+</div>
                    <div class="stat-label">Murid terpantau</div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-num">99.9%</div>
                    <div class="stat-label">Uptime sistem</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Fitur Utama ===== -->
    <section id="fitur" class="notebook-section">
        <div class="notebook-spine d-none d-md-block"></div>
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 600px;">
                <span class="section-eyebrow">Semua kebutuhan akademik</span>
                <h2 class="section-title">Fitur Utama KelasKita</h2>
                <p class="section-desc mx-auto mt-2">Dari absensi harian sampai rapor akhir semester, semua tercatat rapi di satu tempat.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="sticky-note rot-1">
                        <div class="cat-icon"><i class="bi bi-calendar-check"></i></div>
                        <h5 class="fw-bold">Absensi Digital</h5>
                        <p class="text-muted small mb-2">Guru dan wali kelas catat kehadiran murid tiap hari, otomatis terekap per kelas.</p>
                        <span class="cat-count">Real-time</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sticky-note rot-2">
                        <div class="cat-icon"><i class="bi bi-journal-text"></i></div>
                        <h5 class="fw-bold">Rapor Otomatis</h5>
                        <p class="text-muted small mb-2">Nilai dari semua guru mapel otomatis tersusun jadi rapor yang siap cetak.</p>
                        <span class="cat-count">Per semester</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sticky-note rot-3">
                        <div class="cat-icon"><i class="bi bi-eye"></i></div>
                        <h5 class="fw-bold">Monitoring Wali Kelas</h5>
                        <p class="text-muted small mb-2">Wali kelas pantau perkembangan tiap murid di kelasnya dari satu dashboard.</p>
                        <span class="cat-count">Per kelas</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sticky-note rot-4">
                        <div class="cat-icon"><i class="bi bi-calendar3"></i></div>
                        <h5 class="fw-bold">Jadwal & Nilai Ujian</h5>
                        <p class="text-muted small mb-2">Jadwal pelajaran dan input nilai ujian dalam satu sistem yang terintegrasi.</p>
                        <span class="cat-count">Terintegrasi</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sticky-note rot-5">
                        <div class="cat-icon"><i class="bi bi-people"></i></div>
                        <h5 class="fw-bold">Akses Siswa & Orang Tua</h5>
                        <p class="text-muted small mb-2">Murid dan orang tua bisa cek absensi serta nilai kapan saja lewat akun masing-masing.</p>
                        <span class="cat-count">24/7</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sticky-note rot-6">
                        <div class="cat-icon"><i class="bi bi-bar-chart"></i></div>
                        <h5 class="fw-bold">Laporan & Statistik</h5>
                        <p class="text-muted small mb-2">Kepala sekolah dan staf lihat statistik kehadiran serta nilai seluruh sekolah.</p>
                        <span class="cat-count">Tingkat sekolah</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== Kenapa KelasKita ===== -->
    <section id="kenapa" class="py-5" style="background: #fff;">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <span class="section-eyebrow">Kenapa sekolah pilih kami</span>
                    <h2 class="section-title mb-3">Satu sistem buat semua peran</h2>
                    <p class="section-desc">
                        KelasKita kami bangun bukan cuma buat guru, tapi buat semua orang yang terlibat memantau murid — dari wali kelas sampai orang tua di rumah.
                    </p>
                </div>
                <div class="col-lg-7">
                    <div class="d-flex gap-3 mb-4">
                        <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Data aman & akurat</h6>
                            <p class="text-muted small mb-0">Setiap input absensi dan nilai tercatat dengan jejak waktu, jadi datanya bisa dipertanggungjawabkan.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-4">
                        <div class="feature-icon"><i class="bi bi-diagram-3"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Akses sesuai peran</h6>
                            <p class="text-muted small mb-0">Wali kelas, guru mapel, staf TU, siswa, dan orang tua masing-masing punya tampilan sesuai kebutuhannya.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div class="feature-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Rapor tersusun otomatis</h6>
                            <p class="text-muted small mb-0">Nggak perlu rekap manual, nilai dari semua guru mapel langsung jadi rapor yang rapi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== Testimoni ===== -->
    <section id="testimoni" class="testi-section">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 600px;">
                <span class="section-eyebrow">Kata mereka</span>
                <h2 class="section-title">Dipakai Wali Kelas, Guru, dan Orang Tua</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testi-card">
                        <p class="testi-text">Dulu rekap absensi manual makan waktu sejam tiap hari. Sekarang tinggal centang, dan langsung kelihatan siapa yang belum masuk.</p>
                        <div class="d-flex align-items-center gap-2 mt-3">
                            <div class="testi-avatar">S</div>
                            <div>
                                <p class="testi-name">Siti Marlina</p>
                                <p class="testi-role mb-0">Wali Kelas VII-A</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testi-card">
                        <p class="testi-text">Rapor semester ini yang pertama kali selesai tanpa lembur. Nilai dari semua guru mapel udah otomatis terkumpul.</p>
                        <div class="d-flex align-items-center gap-2 mt-3">
                            <div class="testi-avatar">A</div>
                            <div>
                                <p class="testi-name">Andre Wijaya</p>
                                <p class="testi-role mb-0">Guru Mapel Matematika</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testi-card">
                        <p class="testi-text">Sekarang saya bisa cek nilai dan kehadiran anak saya langsung dari HP, nggak perlu nunggu rapor dibagikan.</p>
                        <div class="d-flex align-items-center gap-2 mt-3">
                            <div class="testi-avatar">R</div>
                            <div>
                                <p class="testi-name">Ratna Kusuma</p>
                                <p class="testi-role mb-0">Orang Tua Murid</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section id="gabung" class="py-5" style="background: #F5F1E8;">
        <div class="container">
            <div class="cta-board">
                <h2 class="mb-3">Yuk, rapikan sistem akademik sekolahmu.</h2>
                <p class="mb-4" style="color: rgba(245,241,232,.8);">Absensi, rapor, dan monitoring murid — semua jadi lebih ringkas dalam satu sistem.</p>
                <a href="{{ route('register') ?? '#' }}" class="btn btn-coral btn-lg px-5">Coba KelasKita Sekarang</a>
            </div>
        </div>
    </section>

    <!-- ===== Footer ===== -->
    <footer class="footer-kk">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-brand mb-2">KelasKita</div>
                    <p class="small mb-3">Sistem informasi akademik buat sekolah yang mau pantau muridnya lebih rapi.</p>
                    <div class="d-flex gap-2">
                        <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>Produk</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#fitur">Fitur</a></li>
                        <li class="mb-2"><a href="#">Untuk Sekolah</a></li>
                        <li class="mb-2"><a href="#gabung">Harga</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>Perusahaan</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Tentang Kami</a></li>
                        <li class="mb-2"><a href="#">Karier</a></li>
                        <li class="mb-2"><a href="#">Blog</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>Bantuan</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Pusat Bantuan</a></li>
                        <li class="mb-2"><a href="#">Kontak</a></li>
                        <li class="mb-2"><a href="#">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            <hr class="footer-rule my-4">
            <p class="small mb-0">&copy; {{ date('Y') }} KelasKita. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>