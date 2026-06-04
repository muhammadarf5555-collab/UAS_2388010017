<?php
/**
 * Panel Kelola Data Terpadu - TechNime Blog
 * UAS Web Programming - 2388010017
 *
 * Unified CRUD: Artikel, Proyek IT, Sertifikat, Peringkat Anime
 * Upload gambar aman ke folder uploads/ dengan nama file unik (time())
 */
include_once '../config/koneksi.php';
check_admin_login();

$path_prefix = '../';
$page_title  = 'Kelola Data';

$error_msg   = '';
$success_msg = '';

// Pastikan folder uploads/ ada
$uploadDir = dirname(__DIR__) . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0775, true);
}

// Helper: Upload file dengan validasi dan penamaan unik
function handle_upload($inputName, $allowedTypes = ['image/jpeg','image/png','image/gif','image/webp']) {
    global $uploadDir;
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        return null; // Tidak ada file yang di-upload
    }
    $file = $_FILES[$inputName];
    
    // Validasi tipe file
    if (!in_array($file['type'], $allowedTypes)) {
        return ['error' => 'Tipe file tidak diizinkan! Hanya JPG, PNG, GIF, WEBP.'];
    }
    // Validasi ukuran (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['error' => 'Ukuran file maksimal 5MB!'];
    }
    // Generate nama file unik menggunakan time()
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueName = time() . '_' . mt_rand(1000,9999) . '.' . strtolower($ext);
    $destination = $uploadDir . $uniqueName;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['filename' => $uniqueName];
    }
    return ['error' => 'Gagal menyimpan file ke server!'];
}

// ===================== PROSES DELETE =====================
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $type = $_GET['type'] ?? '';
    $id   = (int)($_GET['id'] ?? 0);
    $ok   = false;
    
    switch($type) {
        case 'artikel':    $ok = delete_article($id); break;
        case 'proyek':     $ok = delete_project($id); break;
        case 'sertifikat': $ok = delete_certificate($id); break;
        case 'ranking':    $ok = delete_ranking($id); break;
    }
    
    if ($ok) {
        header("Location: kelola.php?tab={$type}&status=deleted");
        exit;
    } else {
        $error_msg = 'Gagal menghapus data!';
    }
}

// Set success message dari query string status
if (isset($_GET['status']) && $_GET['status'] === 'deleted') {
    $success_msg = 'Data berhasil dihapus!';
}

// ===================== PROSES TAMBAH DATA =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['form_type'] ?? '';
    
    switch ($formType) {
        // --- TAMBAH ARTIKEL ---
        case 'artikel':
            $judul    = trim($_POST['judul'] ?? '');
            $kategori = trim($_POST['kategori'] ?? '');
            $isi      = trim($_POST['isi'] ?? '');
            $tanggal  = date('Y-m-d');
            $gambar   = 'default-post.png';
            
            if ($judul === '' || $kategori === '' || $isi === '') {
                $error_msg = 'Semua kolom artikel wajib diisi!';
            } elseif (!in_array($kategori, ['Informatika', 'Anime'])) {
                $error_msg = 'Kategori tidak valid!';
            } else {
                $upload = handle_upload('gambar_fitur');
                if ($upload && isset($upload['error'])) {
                    $error_msg = $upload['error'];
                } else {
                    if ($upload) $gambar = $upload['filename'];
                    if (add_article($judul, $kategori, $isi, $tanggal, $gambar)) {
                        header("Location: kelola.php?tab=artikel&status=added");
                        exit;
                    } else {
                        $error_msg = 'Gagal menambahkan artikel!';
                    }
                }
            }
            break;
        
        // --- TAMBAH PROYEK IT ---
        case 'proyek':
            $judul = trim($_POST['judul_proyek'] ?? '');
            $desk  = trim($_POST['deskripsi'] ?? '');
            $tech  = trim($_POST['tech_stack'] ?? '');
            $gambar = 'default-project.png';
            
            if ($judul === '' || $desk === '' || $tech === '') {
                $error_msg = 'Semua kolom proyek IT wajib diisi!';
            } else {
                $upload = handle_upload('gambar_proyek');
                if ($upload && isset($upload['error'])) {
                    $error_msg = $upload['error'];
                } else {
                    if ($upload) $gambar = $upload['filename'];
                    if (add_project($judul, $desk, $tech, $gambar)) {
                        header("Location: kelola.php?tab=proyek&status=added");
                        exit;
                    } else {
                        $error_msg = 'Gagal menambahkan proyek!';
                    }
                }
            }
            break;
        
        // --- TAMBAH SERTIFIKAT ---
        case 'sertifikat':
            $nama     = trim($_POST['nama_sertifikat'] ?? '');
            $penerbit = trim($_POST['penerbit'] ?? '');
            $gambar   = 'default-cert.png';
            
            if ($nama === '' || $penerbit === '') {
                $error_msg = 'Semua kolom sertifikat wajib diisi!';
            } else {
                $upload = handle_upload('gambar_sertifikat');
                if ($upload && isset($upload['error'])) {
                    $error_msg = $upload['error'];
                } else {
                    if ($upload) $gambar = $upload['filename'];
                    if (add_certificate($nama, $penerbit, $gambar)) {
                        header("Location: kelola.php?tab=sertifikat&status=added");
                        exit;
                    } else {
                        $error_msg = 'Gagal menambahkan sertifikat!';
                    }
                }
            }
            break;
        
        // --- TAMBAH PERINGKAT ANIME ---
        case 'ranking':
            $judul   = trim($_POST['judul_anime'] ?? '');
            $genre   = trim($_POST['genre'] ?? '');
            $skor    = floatval($_POST['skor_rating'] ?? 0);
            $rank    = intval($_POST['posisi_rank'] ?? 0);
            $sinopsis= trim($_POST['sinopsis'] ?? '');
            $gambar  = 'default-anime.png';
            
            if ($judul === '' || $genre === '' || $skor <= 0 || $rank <= 0 || $sinopsis === '') {
                $error_msg = 'Semua kolom peringkat anime wajib diisi dengan benar!';
            } else {
                $upload = handle_upload('gambar_anime');
                if ($upload && isset($upload['error'])) {
                    $error_msg = $upload['error'];
                } else {
                    if ($upload) $gambar = $upload['filename'];
                    if (add_ranking($judul, $genre, $skor, $rank, $sinopsis, $gambar)) {
                        header("Location: kelola.php?tab=ranking&status=added");
                        exit;
                    } else {
                        $error_msg = 'Gagal menambahkan peringkat anime!';
                    }
                }
            }
            break;
    }
}

if (isset($_GET['status']) && $_GET['status'] === 'added') {
    $success_msg = 'Data baru berhasil ditambahkan!';
}

// Fetch semua data untuk tabel
$articles    = get_all_articles();
$projects    = get_all_projects();
$certificates= get_all_certificates();
$rankings    = get_all_rankings();

// Tentukan tab aktif
$activeTab = $_GET['tab'] ?? 'artikel';
$validTabs = ['artikel','proyek','sertifikat','ranking'];
if (!in_array($activeTab, $validTabs)) $activeTab = 'artikel';

include_once '../includes/header.php';
?>

<div class="container py-4 py-md-5 flex-grow-1">

    <!-- TOP NAVIGATION BUTTONS: responsive row layout -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
        <a href="dashboard.php" class="btn btn-outline-custom d-inline-flex align-items-center">
            <i class="fa-solid fa-arrow-left me-2"></i> BACK TO DASHBOARD
        </a>
        <a href="../index.php" class="btn btn-gradient d-inline-flex align-items-center" target="_blank">
            <i class="fa-solid fa-eye me-2"></i> LIHAT SITUS UTAMA
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-4">
        <span class="badge bg-secondary text-white mb-2 px-3 py-2" style="font-size:.75rem;border-radius:30px;">
            <i class="fa-solid fa-database me-1"></i> Unified CRUD Panel
        </span>
        <h2 class="text-white m-0">Kelola Semua Data</h2>
        <p class="text-muted m-0 small">Tambah, lihat, dan hapus data artikel, proyek IT, sertifikat, dan peringkat anime.</p>
    </div>

    <!-- Alert Notifications -->
    <?php if ($success_msg): ?>
    <div class="alert p-3 mb-4" style="border-radius:12px;background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.2);">
        <div class="d-flex align-items-center small" style="color:#34d399;">
            <i class="fa-solid fa-circle-check fa-lg me-2"></i>
            <div><strong>Sukses:</strong> <?php echo htmlspecialchars($success_msg); ?></div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
    <div class="alert p-3 mb-4" style="border-radius:12px;background:rgba(220,38,38,.12);border:1px solid rgba(220,38,38,.2);">
        <div class="d-flex align-items-center small" style="color:#f87171;">
            <i class="fa-solid fa-circle-exclamation fa-lg me-2"></i>
            <div><strong>Error:</strong> <?php echo htmlspecialchars($error_msg); ?></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- TABS NAVIGATION (Bootstrap 5 Nav Tabs) -->
    <ul class="nav nav-tabs border-0 mb-4 flex-nowrap overflow-auto" id="kelolaTab" role="tablist" style="gap:.5rem;">
        <li class="nav-item" role="presentation">
            <button class="nav-link px-3 py-2 text-nowrap <?php echo ($activeTab === 'artikel') ? 'active' : ''; ?>" id="tab-artikel" data-bs-toggle="tab" data-bs-target="#pane-artikel" type="button" role="tab" style="border-radius:10px;background:<?php echo ($activeTab === 'artikel') ? 'rgba(108,99,255,.15)' : 'transparent'; ?>;color:<?php echo ($activeTab === 'artikel') ? '#fff' : 'var(--muted)'; ?>;border:1px solid var(--border);font-weight:600;font-size:.85rem;">
                <i class="fa-solid fa-newspaper me-1"></i> Artikel
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-3 py-2 text-nowrap <?php echo ($activeTab === 'proyek') ? 'active' : ''; ?>" id="tab-proyek" data-bs-toggle="tab" data-bs-target="#pane-proyek" type="button" role="tab" style="border-radius:10px;background:<?php echo ($activeTab === 'proyek') ? 'rgba(108,99,255,.15)' : 'transparent'; ?>;color:<?php echo ($activeTab === 'proyek') ? '#fff' : 'var(--muted)'; ?>;border:1px solid var(--border);font-weight:600;font-size:.85rem;">
                <i class="fa-solid fa-code-branch me-1"></i> Proyek IT
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-3 py-2 text-nowrap <?php echo ($activeTab === 'sertifikat') ? 'active' : ''; ?>" id="tab-sertifikat" data-bs-toggle="tab" data-bs-target="#pane-sertifikat" type="button" role="tab" style="border-radius:10px;background:<?php echo ($activeTab === 'sertifikat') ? 'rgba(108,99,255,.15)' : 'transparent'; ?>;color:<?php echo ($activeTab === 'sertifikat') ? '#fff' : 'var(--muted)'; ?>;border:1px solid var(--border);font-weight:600;font-size:.85rem;">
                <i class="fa-solid fa-certificate me-1"></i> Sertifikat
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-3 py-2 text-nowrap <?php echo ($activeTab === 'ranking') ? 'active' : ''; ?>" id="tab-ranking" data-bs-toggle="tab" data-bs-target="#pane-ranking" type="button" role="tab" style="border-radius:10px;background:<?php echo ($activeTab === 'ranking') ? 'rgba(108,99,255,.15)' : 'transparent'; ?>;color:<?php echo ($activeTab === 'ranking') ? '#fff' : 'var(--muted)'; ?>;border:1px solid var(--border);font-weight:600;font-size:.85rem;">
                <i class="fa-solid fa-trophy me-1"></i> Peringkat Anime
            </button>
        </li>
    </ul>

    <!-- TAB CONTENT -->
    <div class="tab-content" id="kelolaTabContent">

        <!-- ======================== TAB: ARTIKEL ======================== -->
        <div class="tab-pane fade <?php echo ($activeTab === 'artikel') ? 'show active' : ''; ?>" id="pane-artikel" role="tabpanel">
            <div class="row g-4">
                <!-- Form Tambah -->
                <div class="col-lg-5">
                    <div class="card card-custom p-4">
                        <h5 class="text-white mb-3 brand-font"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Tambah Artikel</h5>
                        <form action="kelola.php?tab=artikel" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="form_type" value="artikel">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Judul</label>
                                <input type="text" class="form-control form-control-custom" name="judul" placeholder="Judul artikel" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Kategori</label>
                                <select class="form-select form-select-custom" name="kategori" required>
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    <option value="Informatika">Informatika (IT)</option>
                                    <option value="Anime">Anime</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Isi Artikel</label>
                                <textarea class="form-control form-control-custom" name="isi" rows="5" placeholder="Tulis isi artikel..." required></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-semibold">Gambar Fitur (Opsional)</label>
                                <input type="file" class="form-control form-control-custom" name="gambar_fitur" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-gradient w-100 py-2"><i class="fa-solid fa-paper-plane me-2"></i>Publish Artikel</button>
                        </form>
                    </div>
                </div>
                <!-- Tabel Data -->
                <div class="col-lg-7">
                    <div class="card card-custom p-4">
                        <h5 class="text-white mb-3 brand-font"><i class="fa-solid fa-table-list text-primary me-2"></i>Daftar Artikel (<?php echo count($articles); ?>)</h5>
                        <?php if (empty($articles)): ?>
                            <div class="text-center py-5 text-muted"><i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>Belum ada artikel.</div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom w-100">
                                <thead><tr><th>No</th><th>Judul</th><th>Kategori</th><th>Tanggal</th><th class="text-center">Aksi</th></tr></thead>
                                <tbody>
                                <?php $no=1; foreach((array)$articles as $a): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><strong class="text-white"><?php echo htmlspecialchars($a['judul']); ?></strong></td>
                                    <td><span class="<?php echo (strcasecmp($a['kategori'],'Informatika')===0)?'badge-it':'badge-anime'; ?>" style="font-size:.68rem;padding:.2rem .5rem;"><?php echo htmlspecialchars($a['kategori']); ?></span></td>
                                    <td class="small"><?php echo date('d-m-Y', strtotime($a['tanggal'])); ?></td>
                                    <td class="text-center">
                                        <a href="kelola.php?action=delete&type=artikel&id=<?php echo $a['id']; ?>&tab=artikel" class="btn btn-sm px-2 py-1" style="background:rgba(220,38,38,.08);color:#ef4444;border-radius:8px;" onclick="return confirm('Hapus artikel ini?')"><i class="fa-regular fa-trash-can"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================== TAB: PROYEK IT ======================== -->
        <div class="tab-pane fade <?php echo ($activeTab === 'proyek') ? 'show active' : ''; ?>" id="pane-proyek" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card card-custom p-4">
                        <h5 class="text-white mb-3 brand-font"><i class="fa-solid fa-code-branch text-primary me-2"></i>Tambah Proyek IT</h5>
                        <form action="kelola.php?tab=proyek" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="form_type" value="proyek">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Judul Proyek</label>
                                <input type="text" class="form-control form-control-custom" name="judul_proyek" placeholder="Nama proyek" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Deskripsi</label>
                                <textarea class="form-control form-control-custom" name="deskripsi" rows="4" placeholder="Deskripsi proyek..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Tech Stack</label>
                                <input type="text" class="form-control form-control-custom" name="tech_stack" placeholder="PHP, MySQL, Docker" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-semibold">Screenshot Proyek (Opsional)</label>
                                <input type="file" class="form-control form-control-custom" name="gambar_proyek" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-gradient w-100 py-2"><i class="fa-solid fa-plus me-2"></i>Tambah Proyek</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card card-custom p-4">
                        <h5 class="text-white mb-3 brand-font"><i class="fa-solid fa-table-list text-primary me-2"></i>Daftar Proyek IT (<?php echo count($projects); ?>)</h5>
                        <?php if (empty($projects)): ?>
                            <div class="text-center py-5 text-muted"><i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>Belum ada proyek.</div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom w-100">
                                <thead><tr><th>No</th><th>Judul</th><th>Tech Stack</th><th class="text-center">Aksi</th></tr></thead>
                                <tbody>
                                <?php $no=1; foreach((array)$projects as $p): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><strong class="text-white"><?php echo htmlspecialchars($p['judul_proyek']); ?></strong></td>
                                    <td class="small text-muted"><?php echo htmlspecialchars($p['tech_stack']); ?></td>
                                    <td class="text-center">
                                        <a href="kelola.php?action=delete&type=proyek&id=<?php echo $p['id']; ?>&tab=proyek" class="btn btn-sm px-2 py-1" style="background:rgba(220,38,38,.08);color:#ef4444;border-radius:8px;" onclick="return confirm('Hapus proyek ini?')"><i class="fa-regular fa-trash-can"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================== TAB: SERTIFIKAT ======================== -->
        <div class="tab-pane fade <?php echo ($activeTab === 'sertifikat') ? 'show active' : ''; ?>" id="pane-sertifikat" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card card-custom p-4">
                        <h5 class="text-white mb-3 brand-font"><i class="fa-solid fa-certificate text-warning me-2"></i>Tambah Sertifikat</h5>
                        <form action="kelola.php?tab=sertifikat" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="form_type" value="sertifikat">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Nama Sertifikat</label>
                                <input type="text" class="form-control form-control-custom" name="nama_sertifikat" placeholder="Nama sertifikasi" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Penerbit</label>
                                <input type="text" class="form-control form-control-custom" name="penerbit" placeholder="Google, AWS, Dicoding..." required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-semibold">Gambar Sertifikat (Opsional)</label>
                                <input type="file" class="form-control form-control-custom" name="gambar_sertifikat" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-gradient w-100 py-2"><i class="fa-solid fa-plus me-2"></i>Tambah Sertifikat</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card card-custom p-4">
                        <h5 class="text-white mb-3 brand-font"><i class="fa-solid fa-table-list text-warning me-2"></i>Daftar Sertifikat (<?php echo count($certificates); ?>)</h5>
                        <?php if (empty($certificates)): ?>
                            <div class="text-center py-5 text-muted"><i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>Belum ada sertifikat.</div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom w-100">
                                <thead><tr><th>No</th><th>Nama Sertifikat</th><th>Penerbit</th><th class="text-center">Aksi</th></tr></thead>
                                <tbody>
                                <?php $no=1; foreach((array)$certificates as $c): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><strong class="text-white"><?php echo htmlspecialchars($c['nama_sertifikat']); ?></strong></td>
                                    <td class="small text-muted"><?php echo htmlspecialchars($c['penerbit']); ?></td>
                                    <td class="text-center">
                                        <a href="kelola.php?action=delete&type=sertifikat&id=<?php echo $c['id']; ?>&tab=sertifikat" class="btn btn-sm px-2 py-1" style="background:rgba(220,38,38,.08);color:#ef4444;border-radius:8px;" onclick="return confirm('Hapus sertifikat ini?')"><i class="fa-regular fa-trash-can"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================== TAB: PERINGKAT ANIME ======================== -->
        <div class="tab-pane fade <?php echo ($activeTab === 'ranking') ? 'show active' : ''; ?>" id="pane-ranking" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card card-custom p-4">
                        <h5 class="text-white mb-3 brand-font"><i class="fa-solid fa-trophy text-warning me-2"></i>Tambah Peringkat Anime</h5>
                        <form action="kelola.php?tab=ranking" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="form_type" value="ranking">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Judul Anime</label>
                                <input type="text" class="form-control form-control-custom" name="judul_anime" placeholder="Judul anime" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Genre</label>
                                <input type="text" class="form-control form-control-custom" name="genre" placeholder="Action, Fantasy, Drama" required>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label text-muted small fw-semibold">Skor Rating</label>
                                    <input type="number" step="0.01" min="0" max="9.99" class="form-control form-control-custom" name="skor_rating" placeholder="9.25" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted small fw-semibold">Posisi Rank</label>
                                    <input type="number" min="1" class="form-control form-control-custom" name="posisi_rank" placeholder="1" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Sinopsis</label>
                                <textarea class="form-control form-control-custom" name="sinopsis" rows="3" placeholder="Sinopsis singkat anime..." required></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-semibold">Gambar Anime (Opsional)</label>
                                <input type="file" class="form-control form-control-custom" name="gambar_anime" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-gradient w-100 py-2"><i class="fa-solid fa-plus me-2"></i>Tambah Peringkat</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card card-custom p-4">
                        <h5 class="text-white mb-3 brand-font"><i class="fa-solid fa-table-list text-warning me-2"></i>Daftar Peringkat Anime (<?php echo count($rankings); ?>)</h5>
                        <?php if (empty($rankings)): ?>
                            <div class="text-center py-5 text-muted"><i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>Belum ada data peringkat.</div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom w-100">
                                <thead><tr><th>No</th><th>Judul Anime</th><th>Skor</th><th>Genre</th><th class="text-center">Aksi</th></tr></thead>
                                <tbody>
                                <?php $no=1; foreach((array)$rankings as $r): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><strong class="text-white"><?php echo htmlspecialchars($r['judul_anime']); ?></strong></td>
                                    <td><span class="text-warning fw-bold"><?php echo number_format((float)$r['skor_rating'],2); ?></span></td>
                                    <td class="small text-muted"><?php echo htmlspecialchars($r['genre']); ?></td>
                                    <td class="text-center">
                                        <a href="kelola.php?action=delete&type=ranking&id=<?php echo $r['id']; ?>&tab=ranking" class="btn btn-sm px-2 py-1" style="background:rgba(220,38,38,.08);color:#ef4444;border-radius:8px;" onclick="return confirm('Hapus data peringkat ini?')"><i class="fa-regular fa-trash-can"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /tab-content -->
</div>

<?php include_once '../includes/footer.php'; ?>
