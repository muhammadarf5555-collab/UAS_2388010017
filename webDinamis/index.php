<?php
/**
 * Halaman Utama User - TechNime Blog
 * UAS Web Programming - 2388010017
 */
// Koneksi DB menggunakan koneksi.php yang Docker-friendly
include_once 'config/koneksi.php';

$path_prefix = './';
$page_title = 'MUHAMMAD ARIF RIZKY - 2388010017';

$public_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_public_cert'])) {
    $nama = trim($_POST['nama_sertifikat'] ?? '');
    $penerbit = trim($_POST['penerbit'] ?? '');
    $gambar = 'default-cert.png';

    if ($nama === '' || $penerbit === '') {
        $public_msg = '<div class="alert alert-danger mx-3 mt-3">Nama dan Penerbit wajib diisi!</div>';
    } else {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }
        if (isset($_FILES['gambar_sertifikat']) && $_FILES['gambar_sertifikat']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['gambar_sertifikat'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (in_array($file['type'], $allowedTypes) && $file['size'] <= 5 * 1024 * 1024) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $uniqueName = time() . '_' . mt_rand(1000, 9999) . '.' . strtolower($ext);
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $uniqueName)) {
                    $gambar = $uniqueName;
                }
            }
        }
        if (add_certificate($nama, $penerbit, $gambar)) {
            $public_msg = '<div class="alert alert-success mx-3 mt-3">Sertifikat berhasil dikirim!</div>';
        } else {
            $public_msg = '<div class="alert alert-danger mx-3 mt-3">Gagal mengirim sertifikat.</div>';
        }
    }
}

// Ambil data dari database / fallback session
$it_articles = get_all_articles('Informatika');
$anime_articles = get_all_articles('Anime');
$admin_info = get_admin_info();
$projects = get_all_projects();
$certificates = get_all_certificates();
$rankings = get_all_rankings(); // ORDER BY skor_rating DESC

include_once 'includes/header.php';
?>

<!-- ======================================================
     SECTION #home — Hero Banner
====================================================== -->
<section id="home" style="min-height: 100vh; display: flex; align-items: center; padding-top: 80px;">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge-it mb-3 d-inline-block">
                    <i class="fa-solid fa-rocket me-1 text-warning"></i> UAS Web Programming 2026
                </span>
                <h1 class="display-4 fw-bold text-white mb-3 lh-sm" style="letter-spacing: -1.5px;">
                    Portal <span
                        style="background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Informatika</span>
                    &amp; <span class="text-secondary">Anime</span> Terkini
                </h1>
                <p class="lead text-muted mb-2" style="font-size: 1.1rem; line-height: 1.8;">
                    <strong class="text-white">TechNime Blog</strong> — wadah berbagi informasi teknologi kecerdasan
                    buatan (AI), berita anime terhangat, dan portofolio IT mahasiswa.
                </p>
                <p class="text-muted small mb-4">
                    <i class="fa-solid fa-id-badge text-primary me-1"></i> Muhammad Arif Rizky &nbsp;&bull;&nbsp;
                    <i class="fa-solid fa-hashtag text-secondary me-1"></i> NIM: 2388010017
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#informatika" class="btn btn-gradient px-4"><i class="fa-solid fa-brain me-2"></i>Berita
                        AI</a>
                    <a href="#anime" class="btn btn-outline-custom px-4"><i class="fa-solid fa-film me-2"></i>Dunia
                        Anime</a>
                    <a href="#portofolio" class="btn btn-outline-custom px-4"><i
                            class="fa-solid fa-briefcase me-2"></i>Portofolio</a>
                    <a href="#peringkat" class="btn btn-outline-custom px-4"><i
                            class="fa-solid fa-trophy me-2"></i>Peringkat</a>
                </div>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-flex justify-content-center">
                <div class="card bg-dark shadow-lg p-4"
                    style="border-radius: 24px; border: 1px solid var(--border); width: 380px;">
                    <div class="overflow-hidden mb-3"
                        style="border-radius: 16px; height: 200px; background: var(--gradient); display: flex; align-items: center; justify-content: center;">
                        <div class="text-center text-white px-3">
                            <i class="fa-solid fa-robot fa-3x mb-2 text-warning"></i>
                            <h4 class="fw-bold brand-font m-0">TechNime Blog</h4>
                            <span class="badge bg-dark mt-1">UAS 2388010017</span>
                        </div>
                    </div>
                    <div class="text-start">
                        <p class="text-white fw-bold small mb-1"><i
                                class="fa-solid fa-circle-nodes text-primary me-2"></i>AI &amp; Pop Culture Hub</p>
                        <p class="text-muted small mb-0">MUHAMMAD ARIF RIZKY SANG JUARA COC Konten edukatif IT
                            dikombinasikan dengan kesukaan hobi menonton film
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!$is_db_connected): ?>
    <div class="container">
        <div class="alert p-3"
            style="border-radius:12px;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.15);">
            <div class="d-flex align-items-center text-warning small">
                <i class="fa-solid fa-triangle-exclamation fa-lg me-2"></i>
                <div><strong>Mode Demo Aktif:</strong> Database MySQL belum terhubung. Menampilkan data session sementara.
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- ======================================================
     SECTION #informatika — Berita Teknologi AI
====================================================== -->
<section id="informatika" class="py-5" style="border-top: 1px solid var(--border);">
    <div class="container py-3">
        <div class="d-flex align-items-center mb-5">
            <div class="rounded-3 me-3 d-flex align-items-center justify-content-center"
                style="width:48px;height:48px;background:rgba(108,99,255,.12);border:1px solid rgba(108,99,255,.3);color:var(--primary);">
                <i class="fa-solid fa-microchip fa-xl"></i>
            </div>
            <div>
                <h2 class="text-white m-0">Berita Teknologi AI</h2>
                <p class="text-muted small m-0">Update terbaru seputar kecerdasan buatan, machine learning, dan
                    programming.</p>
            </div>
        </div>

        <?php if (empty($it_articles)): ?>
            <div class="text-center py-5 text-muted"><i class="fa-regular fa-folder-open fa-3x mb-3"></i>
                <p class="m-0">Belum ada artikel Informatika.</p>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach (array_slice((array) $it_articles, 0, 4) as $art): ?>
                    <?php
                    $artImg = 'uploads/' . ($art['gambar_fitur'] ?? 'default-post.png');
                    $hasArtImg = !empty($art['gambar_fitur']) && $art['gambar_fitur'] !== 'default-post.png';
                    ?>
                    <div class="col">
                        <div class="card card-custom">
                            <?php if ($hasArtImg): ?>
                                <img src="<?php echo htmlspecialchars($artImg); ?>" class="card-img-top"
                                    style="height:180px;object-fit:cover;" alt="<?php echo htmlspecialchars($art['judul']); ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center"
                                    style="height:120px;background:linear-gradient(135deg,rgba(108,99,255,.12),rgba(108,99,255,.04));">
                                    <i class="fa-solid fa-microchip fa-2x" style="color:var(--primary);opacity:.5;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body p-4 d-flex flex-column h-100">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge-it"><i class="fa-solid fa-code me-1"></i>Informatika</span>
                                    <small class="text-muted" style="font-size:.72rem;"><i
                                            class="fa-regular fa-calendar me-1"></i><?php echo date('d M Y', strtotime($art['tanggal'])); ?></small>
                                </div>
                                <h5 class="text-white mb-2"><?php echo htmlspecialchars($art['judul']); ?></h5>
                                <p class="text-muted small mb-4" style="line-height:1.7;">
                                    <?php echo htmlspecialchars(get_synopsis($art['isi'], 150)); ?>
                                </p>
                                <div class="mt-auto">
                                    <button class="btn btn-outline-custom btn-sm w-100 py-2" data-bs-toggle="modal"
                                        data-bs-target="#modalIT<?php echo $art['id']; ?>">
                                        Baca Selengkapnya <i class="fa-solid fa-arrow-right-long ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="modalIT<?php echo $art['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content text-white"
                                style="background:#111124;border:1px solid var(--border);border-radius:20px;">
                                <?php if ($hasArtImg): ?>
                                    <img src="<?php echo htmlspecialchars($artImg); ?>"
                                        style="height:220px;object-fit:cover;border-radius:20px 20px 0 0;width:100%;"
                                        alt="<?php echo htmlspecialchars($art['judul']); ?>">
                                <?php endif; ?>
                                <div class="modal-header border-0 p-4 pb-0">
                                    <span class="badge-it"><i class="fa-solid fa-code me-1"></i>Informatika</span>
                                    <button type="button" class="btn-close btn-close-white ms-auto"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4 pt-3">
                                    <h3 class="fw-bold brand-font text-white mb-1">
                                        <?php echo htmlspecialchars($art['judul']); ?>
                                    </h3>
                                    <p class="text-muted small mb-3"><i
                                            class="fa-regular fa-calendar me-1"></i><?php echo date('d F Y', strtotime($art['tanggal'])); ?>
                                    </p>
                                    <hr style="border-color:var(--border);">
                                    <div style="line-height:1.9;white-space:pre-line;color:#D0D5DE;">
                                        <?php echo htmlspecialchars($art['isi']); ?>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-4 pt-0">
                                    <button class="btn btn-outline-custom" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- ======================================================
     SECTION #anime — Berita Anime
====================================================== -->
<section id="anime" class="py-5" style="border-top: 1px solid var(--border); background: rgba(255,101,132,.01);">
    <div class="container py-3">
        <div class="d-flex align-items-center mb-5">
            <div class="rounded-3 me-3 d-flex align-items-center justify-content-center"
                style="width:48px;height:48px;background:rgba(255,101,132,.12);border:1px solid rgba(255,101,132,.3);color:var(--secondary);">
                <i class="fa-solid fa-ghost fa-xl"></i>
            </div>
            <div>
                <h2 class="text-white m-0">Berita Anime</h2>
                <p class="text-muted small m-0">Review anime terbaru, kabar perilisan, dan analisis plot mendalam.</p>
            </div>
        </div>

        <?php if (empty($anime_articles)): ?>
            <div class="text-center py-5 text-muted"><i class="fa-regular fa-folder-open fa-3x mb-3"></i>
                <p class="m-0">Belum ada artikel Anime.</p>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach (array_slice((array) $anime_articles, 0, 4) as $art): ?>
                    <?php
                    $artImg = 'uploads/' . ($art['gambar_fitur'] ?? 'default-post.png');
                    $hasArtImg = !empty($art['gambar_fitur']) && $art['gambar_fitur'] !== 'default-post.png';
                    ?>
                    <div class="col">
                        <div class="card card-custom">
                            <?php if ($hasArtImg): ?>
                                <img src="<?php echo htmlspecialchars($artImg); ?>" class="card-img-top"
                                    style="height:180px;object-fit:cover;" alt="<?php echo htmlspecialchars($art['judul']); ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center"
                                    style="height:120px;background:linear-gradient(135deg,rgba(255,101,132,.12),rgba(255,101,132,.04));">
                                    <i class="fa-solid fa-ghost fa-2x" style="color:var(--secondary);opacity:.5;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body p-4 d-flex flex-column h-100">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge-anime"><i class="fa-solid fa-ghost me-1"></i>Anime</span>
                                    <small class="text-muted" style="font-size:.72rem;"><i
                                            class="fa-regular fa-calendar me-1"></i><?php echo date('d M Y', strtotime($art['tanggal'])); ?></small>
                                </div>
                                <h5 class="text-white mb-2"><?php echo htmlspecialchars($art['judul']); ?></h5>
                                <p class="text-muted small mb-4" style="line-height:1.7;">
                                    <?php echo htmlspecialchars(get_synopsis($art['isi'], 150)); ?>
                                </p>
                                <div class="mt-auto">
                                    <button class="btn btn-outline-custom btn-sm w-100 py-2" data-bs-toggle="modal"
                                        data-bs-target="#modalANIME<?php echo $art['id']; ?>">
                                        Baca Selengkapnya <i class="fa-solid fa-arrow-right-long ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="modalANIME<?php echo $art['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content text-white"
                                style="background:#111124;border:1px solid var(--border);border-radius:20px;">
                                <?php if ($hasArtImg): ?>
                                    <img src="<?php echo htmlspecialchars($artImg); ?>"
                                        style="height:220px;object-fit:cover;border-radius:20px 20px 0 0;width:100%;"
                                        alt="<?php echo htmlspecialchars($art['judul']); ?>">
                                <?php endif; ?>
                                <div class="modal-header border-0 p-4 pb-0">
                                    <span class="badge-anime"><i class="fa-solid fa-ghost me-1"></i>Anime</span>
                                    <button type="button" class="btn-close btn-close-white ms-auto"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4 pt-3">
                                    <h3 class="fw-bold brand-font text-white mb-1">
                                        <?php echo htmlspecialchars($art['judul']); ?>
                                    </h3>
                                    <p class="text-muted small mb-3"><i
                                            class="fa-regular fa-calendar me-1"></i><?php echo date('d F Y', strtotime($art['tanggal'])); ?>
                                    </p>
                                    <hr style="border-color:var(--border);">
                                    <div style="line-height:1.9;white-space:pre-line;color:#D0D5DE;">
                                        <?php echo htmlspecialchars($art['isi']); ?>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-4 pt-0">
                                    <button class="btn btn-outline-custom" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- ======================================================
     SECTION #portofolio — Biodata + Proyek + Sertifikat
====================================================== -->
<section id="portofolio" class="py-5" style="border-top: 1px solid var(--border);">
    <div class="container py-3">
        <!-- Header Section -->
        <div class="d-flex align-items-center mb-5">
            <div class="rounded-3 me-3 d-flex align-items-center justify-content-center"
                style="width:48px;height:48px;background:rgba(67,233,123,.12);border:1px solid rgba(67,233,123,.3);color:#43E97B;">
                <i class="fa-solid fa-briefcase fa-xl"></i>
            </div>
            <div>
                <h2 class="text-white m-0">Portofolio IT</h2>
                <p class="text-muted small m-0">Biodata, proyek-proyek IT, dan sertifikasi yang telah diraih.</p>
            </div>
        </div>

        <!-- Biodata Admin -->
        <div class="card card-custom p-4 mb-5">
            <div class="row align-items-center g-4">
                <div class="col-md-2 text-center">
                    <?php
                    $fotoPath = 'uploads/' . ($admin_info['foto_profil'] ?? 'default-avatar.png');
                    $useDefault = ($admin_info['foto_profil'] ?? '') === 'default-avatar.png';
                    ?>
                    <?php if (!$useDefault): ?>
                        <img src="<?php echo htmlspecialchars($fotoPath); ?>" class="rounded-circle border border-primary"
                            style="width:90px;height:90px;object-fit:cover;" alt="Foto Profil">
                    <?php else: ?>
                        <div class="rounded-circle border border-primary d-flex align-items-center justify-content-center mx-auto"
                            style="width:90px;height:90px;background:rgba(108,99,255,.1);color:var(--primary);">
                            <i class="fa-solid fa-user-graduate fa-2x"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-10">
                    <h4 class="text-white mb-1 brand-font">
                        <?php echo htmlspecialchars($admin_info['nama_lengkap'] ?? 'Muhammad Arif Rizky'); ?>
                    </h4>
                    <p class="text-muted mb-1 small">
                        <span class="badge-it me-2"><i class="fa-solid fa-id-badge me-1"></i>NIM:
                            <?php echo htmlspecialchars($admin_info['nim'] ?? '2388010017'); ?></span>
                        <span class="badge-anime"><i class="fa-solid fa-graduation-cap me-1"></i>Teknik
                            Informatika</span>
                    </p>
                    <p class="text-muted mt-2 mb-0" style="line-height:1.7;">
                        <?php echo htmlspecialchars($admin_info['bio_it'] ?? 'Informatics Student & Full-Stack Web Developer.'); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Grid Proyek IT -->
        <h4 class="text-white mb-4 brand-font"><i class="fa-solid fa-code-branch text-primary me-2"></i>Proyek IT</h4>
        <?php if (!empty($projects)): ?>
            <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
                <?php foreach ((array) $projects as $proj): ?>
                    <div class="col">
                        <div class="card card-custom">
                            <?php
                            $pImg = 'uploads/' . ($proj['gambar_proyek'] ?? 'default-project.png');
                            $noImg = ($proj['gambar_proyek'] ?? '') === 'default-project.png';
                            ?>
                            <?php if (!$noImg): ?>
                                <img src="<?php echo htmlspecialchars($pImg); ?>" class="card-img-top"
                                    style="height:160px;object-fit:cover;"
                                    alt="<?php echo htmlspecialchars($proj['judul_proyek']); ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center"
                                    style="height:160px;background:rgba(108,99,255,.05);">
                                    <i class="fa-solid fa-image text-muted fa-3x"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body p-4">
                                <h5 class="text-white mb-2"><?php echo htmlspecialchars($proj['judul_proyek']); ?></h5>
                                <p class="text-muted small mb-3" style="line-height:1.6;">
                                    <?php echo htmlspecialchars(get_synopsis($proj['deskripsi'], 100)); ?>
                                </p>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach (explode(',', $proj['tech_stack']) as $tech): ?>
                                        <span
                                            style="font-size:.68rem;background:rgba(108,99,255,.1);color:var(--primary);border:1px solid rgba(108,99,255,.25);border-radius:20px;padding:.25rem .6rem;"
                                            class="fw-semibold"><?php echo htmlspecialchars(trim($tech)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center text-muted py-4 mb-5"><i class="fa-solid fa-folder-open fa-2x mb-2 d-block"></i>Belum
                ada proyek IT.</div>
        <?php endif; ?>

        <!-- Grid Sertifikat -->
        <h4 class="text-white mb-4 brand-font"><i class="fa-solid fa-certificate text-warning me-2"></i>Sertifikat</h4>
        <?php if (!empty($certificates)): ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ((array) $certificates as $cert): ?>
                    <?php
                    $cImg = 'uploads/' . ($cert['gambar_sertifikat'] ?? 'default-cert.png');
                    $hasCImg = !empty($cert['gambar_sertifikat']) && $cert['gambar_sertifikat'] !== 'default-cert.png';
                    ?>
                    <div class="col">
                        <div class="card card-custom" style="cursor:pointer;" data-bs-toggle="modal"
                            data-bs-target="#modalCERT<?php echo $cert['id']; ?>">
                            <?php if ($hasCImg): ?>
                                <img src="<?php echo htmlspecialchars($cImg); ?>" class="card-img-top"
                                    style="height:160px;object-fit:cover;"
                                    alt="<?php echo htmlspecialchars($cert['nama_sertifikat']); ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center"
                                    style="height:160px;background:linear-gradient(135deg,rgba(255,193,7,.12),rgba(255,193,7,.04));">
                                    <i class="fa-solid fa-award fa-3x" style="color:#ffc107;opacity:.6;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body p-4">
                                <h6 class="text-white mb-2 fw-bold"><?php echo htmlspecialchars($cert['nama_sertifikat']); ?>
                                </h6>
                                <p class="mb-2" style="font-size:.8rem;color:#BFC5D0;">
                                    <i class="fa-solid fa-building me-1"
                                        style="color:#ffc107;"></i><?php echo htmlspecialchars($cert['penerbit']); ?>
                                </p>
                                <span
                                    style="font-size:.72rem;background:rgba(255,193,7,.1);color:#ffc107;border:1px solid rgba(255,193,7,.25);border-radius:20px;padding:.2rem .65rem;"
                                    class="fw-semibold">
                                    <i class="fa-solid fa-eye me-1"></i>Lihat Detail
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Detail Sertifikat -->
                    <div class="modal fade" id="modalCERT<?php echo $cert['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content"
                                style="background:#111124;border:1px solid rgba(255,193,7,.2);border-radius:20px;">
                                <?php if ($hasCImg): ?>
                                    <img src="<?php echo htmlspecialchars($cImg); ?>"
                                        style="height:260px;object-fit:contain;object-position:center;border-radius:20px 20px 0 0;width:100%;background:#0a0a14;padding:1rem;"
                                        alt="<?php echo htmlspecialchars($cert['nama_sertifikat']); ?>">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center"
                                        style="height:160px;background:rgba(255,193,7,.05);border-radius:20px 20px 0 0;">
                                        <i class="fa-solid fa-award fa-4x" style="color:#ffc107;opacity:.5;"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="modal-header border-0 p-4 pb-0">
                                    <span
                                        style="font-size:.75rem;background:rgba(255,193,7,.12);color:#ffc107;border:1px solid rgba(255,193,7,.3);border-radius:30px;padding:.3rem .75rem;"
                                        class="fw-bold">
                                        <i class="fa-solid fa-certificate me-1"></i>Sertifikat
                                    </span>
                                    <button type="button" class="btn-close btn-close-white ms-auto"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4 pt-3">
                                    <h4 class="fw-bold brand-font text-white mb-3">
                                        <?php echo htmlspecialchars($cert['nama_sertifikat']); ?>
                                    </h4>
                                    <hr style="border-color:rgba(255,193,7,.15);">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-3"
                                                style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);">
                                                <p class="text-muted small mb-1 fw-semibold text-uppercase"
                                                    style="font-size:.68rem;letter-spacing:.05em;">Penerbit</p>
                                                <p class="text-white mb-0 fw-bold"><i
                                                        class="fa-solid fa-building text-warning me-2"></i><?php echo htmlspecialchars($cert['penerbit']); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-3"
                                                style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);">
                                                <p class="text-muted small mb-1 fw-semibold text-uppercase"
                                                    style="font-size:.68rem;letter-spacing:.05em;">Status</p>
                                                <p class="mb-0 fw-bold" style="color:#43E97B;"><i
                                                        class="fa-solid fa-circle-check me-2"></i>Terverifikasi</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-4 pt-0">
                                    <button class="btn btn-outline-custom" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center text-muted py-4"><i class="fa-solid fa-folder-open fa-2x mb-2 d-block"></i>Belum ada
                sertifikat.</div>
        <?php endif; ?>

        <!-- Form Kirim Sertifikat (Public) -->
        <div class="mt-5">
            <h5 class="text-white mb-3 brand-font"><i class="fa-solid fa-upload text-primary me-2"></i>Kirim Sertifikat
                Baru</h5>
            <div class="card card-custom p-4">
                <?php echo $public_msg ?? ''; ?>
                <form action="<?php echo $path_prefix; ?>index.php#portofolio" method="POST"
                    enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Nama Sertifikat</label>
                            <input type="text" name="nama_sertifikat" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted small">Upload Gambar Sertifikat</label>
                            <input type="file" name="gambar_sertifikat" class="form-control form-control-custom"
                                accept="image/*">
                        </div>
                        <div class="col-md-12 text-end mt-4">
                            <button type="submit" name="submit_public_cert" class="btn btn-gradient px-4 py-2">
                                <i class="fa-solid fa-paper-plane me-2"></i>Kirim Sertifikat
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

<!-- ======================================================
     SECTION #peringkat — Peringkat Anime Populer
====================================================== -->
<section id="peringkat" class="py-5" style="border-top: 1px solid var(--border); background: rgba(255,255,255,.01);">
    <div class="container py-3">
        <div class="d-flex align-items-center mb-5">
            <div class="rounded-3 me-3 d-flex align-items-center justify-content-center"
                style="width:48px;height:48px;background:rgba(255,193,7,.12);border:1px solid rgba(255,193,7,.3);color:#ffc107;">
                <i class="fa-solid fa-trophy fa-xl"></i>
            </div>
            <div>
                <h2 class="text-white m-0">Peringkat Anime Populer</h2>
                <p class="text-muted small m-0">Top anime berdasarkan skor rating tertinggi dari komunitas TechNime.</p>
            </div>
        </div>

        <?php if (!empty($rankings)): ?>
            <div class="card card-custom shadow-lg">
                <div class="table-responsive">
                    <table class="table table-custom w-100 m-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:8%;">Posisi</th>
                                <th>Judul Anime</th>
                                <th style="width:25%;">Skor Rating</th>
                                <th style="width:25%;">Genre</th>
                                <th class="d-none d-md-table-cell" style="width:30%;">Sinopsis</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $posCounter = 1;
                            foreach ((array) $rankings as $rank):
                                // Badge dan icon berdasarkan posisi
                                if ($posCounter === 1) {
                                    $badgeClass = 'bg-warning text-dark';
                                    $icon = '<i class="fa-solid fa-crown me-1 text-dark"></i>';
                                } elseif ($posCounter === 2) {
                                    $badgeClass = 'bg-light text-dark';
                                    $icon = '<i class="fa-solid fa-medal me-1 text-secondary"></i>';
                                } elseif ($posCounter === 3) {
                                    $badgeClass = 'bg-danger text-white';
                                    $icon = '<i class="fa-solid fa-award me-1"></i>';
                                } else {
                                    $badgeClass = 'bg-dark border border-secondary text-white';
                                    $icon = '';
                                }
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge <?php echo $badgeClass; ?> px-2 py-2 rounded-3"
                                            style="font-size:.9rem;font-weight:700;"><?php echo $icon . $posCounter; ?></span>
                                    </td>
                                    <td>
                                        <strong
                                            class="text-white d-block"><?php echo htmlspecialchars($rank['judul_anime']); ?></strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span
                                                class="text-white fw-bold"><?php echo number_format((float) $rank['skor_rating'], 2); ?></span>
                                            <div class="progress flex-grow-1 bg-dark" style="height:6px;border-radius:3px;">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width:<?php echo min(100, (float) $rank['skor_rating'] * 10); ?>%; background: var(--gradient);"
                                                    aria-valuenow="<?php echo $rank['skor_rating']; ?>" aria-valuemin="0"
                                                    aria-valuemax="10">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-muted small"><?php echo htmlspecialchars($rank['genre']); ?></span>
                                    </td>
                                    <td class="d-none d-md-table-cell"><span
                                            class="text-muted small"><?php echo htmlspecialchars(get_synopsis($rank['sinopsis'], 80)); ?></span>
                                    </td>
                                </tr>
                                <?php $posCounter++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted"><i class="fa-solid fa-inbox fa-3x mb-3 d-block"></i>Belum ada data
                peringkat anime.</div>
        <?php endif; ?>
    </div>
</section>

<?php include_once 'includes/footer.php'; ?>