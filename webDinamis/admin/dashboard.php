<?php
/**
 * Dashboard Admin - TechNime Blog
 * UAS Web Programming - 2388010017
 */
include_once '../config/koneksi.php';

check_admin_login();

$path_prefix = '../';
$page_title  = 'Dashboard Admin';

// Statistik
$all_articles  = get_all_articles();
$it_articles   = get_all_articles('Informatika');
$anime_articles= get_all_articles('Anime');
$projects      = get_all_projects();
$certificates  = get_all_certificates();
$rankings      = get_all_rankings();

include_once '../includes/header.php';
?>

<div class="container py-5 flex-grow-1">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <span class="badge bg-primary text-white mb-2 px-3 py-2" style="font-size:.75rem;border-radius:30px;">
                <i class="fa-solid fa-shield-halved me-1"></i> Admin Area
            </span>
            <h1 class="text-white m-0">Dashboard Control</h1>
            <p class="text-muted m-0">Selamat datang kembali, <strong class="text-white"><?php echo htmlspecialchars($_SESSION['admin_nama'] ?? 'Admin'); ?></strong>.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="../index.php" class="btn btn-outline-custom" target="_blank">
                <i class="fa-solid fa-eye me-1"></i> Lihat Situs Utama
            </a>
            <a href="kelola.php" class="btn btn-gradient">
                <i class="fa-solid fa-pen-to-square me-1"></i> Kelola Data
            </a>
        </div>
    </div>

    <!-- Mode Demo Warning -->
    <?php if (!$is_db_connected): ?>
    <div class="alert p-3 mb-4" style="border-radius:12px;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.15);">
        <div class="d-flex align-items-center text-warning small">
            <i class="fa-solid fa-circle-exclamation fa-lg me-2"></i>
            <div><strong>Mode Demo:</strong> Database belum terhubung. Data disimpan sementara di session browser. Import <code class="text-warning">db_data/uasadmin_2388010017.sql</code> untuk mode permanen.</div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Statistik Cards -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-lg-3">
            <div class="card card-custom p-4" style="border-left:4px solid var(--primary)!important;">
                <p class="text-muted text-uppercase small fw-bold mb-1">Total Artikel</p>
                <h2 class="text-white fw-bold m-0"><?php echo count($all_articles); ?></h2>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-custom p-4" style="border-left:4px solid #6C63FF!important;">
                <p class="text-muted text-uppercase small fw-bold mb-1">Artikel IT</p>
                <h2 class="text-white fw-bold m-0"><?php echo count($it_articles); ?></h2>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-custom p-4" style="border-left:4px solid #FF6584!important;">
                <p class="text-muted text-uppercase small fw-bold mb-1">Artikel Anime</p>
                <h2 class="text-white fw-bold m-0"><?php echo count($anime_articles); ?></h2>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-custom p-4" style="border-left:4px solid #43E97B!important;">
                <p class="text-muted text-uppercase small fw-bold mb-1">Proyek IT</p>
                <h2 class="text-white fw-bold m-0"><?php echo count($projects); ?></h2>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-6 col-lg-3">
            <div class="card card-custom p-4" style="border-left:4px solid #ffc107!important;">
                <p class="text-muted text-uppercase small fw-bold mb-1">Sertifikat</p>
                <h2 class="text-white fw-bold m-0"><?php echo count($certificates); ?></h2>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-custom p-4" style="border-left:4px solid #0dcaf0!important;">
                <p class="text-muted text-uppercase small fw-bold mb-1">Peringkat Anime</p>
                <h2 class="text-white fw-bold m-0"><?php echo count($rankings); ?></h2>
            </div>
        </div>
    </div>

    <!-- Navigasi Cepat -->
    <h4 class="text-white mb-4 brand-font"><i class="fa-solid fa-sliders text-primary me-2"></i>Navigasi Cepat</h4>
    <div class="row g-4">
        <div class="col-md-6">
            <a href="kelola.php" class="text-decoration-none">
                <div class="card card-custom p-4 h-100 text-start">
                    <h5 class="text-white mb-2"><i class="fa-solid fa-database text-primary me-2"></i>Kelola Semua Data</h5>
                    <p class="text-muted small mb-0">CRUD artikel, proyek IT, sertifikat, dan peringkat anime. Upload gambar secara aman ke folder <code class="text-muted">uploads/</code>.</p>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="logout.php" class="text-decoration-none">
                <div class="card card-custom p-4 h-100 text-start">
                    <h5 class="text-danger mb-2"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</h5>
                    <p class="text-muted small mb-0">Keluar dari sesi admin dan kembali ke beranda publik TechNime Blog.</p>
                </div>
            </a>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
