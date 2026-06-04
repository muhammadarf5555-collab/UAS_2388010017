<?php
/**
 * Halaman Edit Data - TechNime Admin
 * UAS Web Programming - 2388010017
 * Mendukung edit: artikel, proyek, sertifikat, ranking
 */
include_once '../config/koneksi.php';
check_admin_login();

$path_prefix = '../';
$page_title  = 'Edit Data';

$type   = $_GET['type'] ?? '';
$id     = (int)($_GET['id'] ?? 0);
$validTypes = ['artikel', 'proyek', 'sertifikat', 'ranking'];

if (!in_array($type, $validTypes) || $id <= 0) {
    header("Location: kelola.php");
    exit;
}

$uploadDir = dirname(__DIR__) . '/uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);

function handle_upload_edit($inputName, $allowedTypes = ['image/jpeg','image/png','image/gif','image/webp']) {
    global $uploadDir;
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) return null;
    $file = $_FILES[$inputName];
    if (!in_array($file['type'], $allowedTypes)) return ['error' => 'Tipe file tidak diizinkan!'];
    if ($file['size'] > 5 * 1024 * 1024) return ['error' => 'Ukuran file maksimal 5MB!'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueName = time() . '_' . mt_rand(1000,9999) . '.' . strtolower($ext);
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $uniqueName)) return ['filename' => $uniqueName];
    return ['error' => 'Gagal menyimpan file!'];
}

// Ambil data yang akan diedit
$data = null;
switch ($type) {
    case 'artikel':    $data = get_article_by_id($id); break;
    case 'proyek':     $data = get_project_by_id($id); break;
    case 'sertifikat': $data = get_certificate_by_id($id); break;
    case 'ranking':    $data = get_ranking_by_id($id); break;
}

if (!$data) {
    header("Location: kelola.php");
    exit;
}

$error_msg   = '';
$success_msg = '';

// Proses form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($type) {
        case 'artikel':
            $judul    = trim($_POST['judul'] ?? '');
            $kategori = trim($_POST['kategori'] ?? '');
            $isi      = trim($_POST['isi'] ?? '');
            if ($judul === '' || $kategori === '' || $isi === '') {
                $error_msg = 'Semua kolom wajib diisi!';
            } else {
                $gambar = null;
                $upload = handle_upload_edit('gambar_fitur');
                if ($upload && isset($upload['error'])) { $error_msg = $upload['error']; break; }
                if ($upload) $gambar = $upload['filename'];
                if (update_article($id, $judul, $kategori, $isi, $gambar)) {
                    header("Location: kelola.php?tab=artikel&status=updated"); exit;
                }
                $error_msg = 'Gagal mengupdate artikel!';
            }
            break;

        case 'proyek':
            $judul  = trim($_POST['judul_proyek'] ?? '');
            $desk   = trim($_POST['deskripsi'] ?? '');
            $tech   = trim($_POST['tech_stack'] ?? '');
            if ($judul === '' || $desk === '' || $tech === '') {
                $error_msg = 'Semua kolom wajib diisi!';
            } else {
                $gambar = null;
                $upload = handle_upload_edit('gambar_proyek');
                if ($upload && isset($upload['error'])) { $error_msg = $upload['error']; break; }
                if ($upload) $gambar = $upload['filename'];
                if (update_project($id, $judul, $desk, $tech, $gambar)) {
                    header("Location: kelola.php?tab=proyek&status=updated"); exit;
                }
                $error_msg = 'Gagal mengupdate proyek!';
            }
            break;

        case 'sertifikat':
            $nama     = trim($_POST['nama_sertifikat'] ?? '');
            $penerbit = trim($_POST['penerbit'] ?? '');
            if ($nama === '' || $penerbit === '') {
                $error_msg = 'Semua kolom wajib diisi!';
            } else {
                $gambar = null;
                $upload = handle_upload_edit('gambar_sertifikat');
                if ($upload && isset($upload['error'])) { $error_msg = $upload['error']; break; }
                if ($upload) $gambar = $upload['filename'];
                if (update_certificate($id, $nama, $penerbit, $gambar)) {
                    header("Location: kelola.php?tab=sertifikat&status=updated"); exit;
                }
                $error_msg = 'Gagal mengupdate sertifikat!';
            }
            break;

        case 'ranking':
            $judul   = trim($_POST['judul_anime'] ?? '');
            $genre   = trim($_POST['genre'] ?? '');
            $skor    = floatval($_POST['skor_rating'] ?? 0);
            $rank    = intval($_POST['posisi_rank'] ?? 0);
            $sinopsis= trim($_POST['sinopsis'] ?? '');
            if ($judul === '' || $genre === '' || $skor <= 0 || $rank <= 0 || $sinopsis === '') {
                $error_msg = 'Semua kolom wajib diisi dengan benar!';
            } else {
                $gambar = null;
                $upload = handle_upload_edit('gambar_anime');
                if ($upload && isset($upload['error'])) { $error_msg = $upload['error']; break; }
                if ($upload) $gambar = $upload['filename'];
                if (update_ranking($id, $judul, $genre, $skor, $rank, $sinopsis, $gambar)) {
                    header("Location: kelola.php?tab=ranking&status=updated"); exit;
                }
                $error_msg = 'Gagal mengupdate peringkat anime!';
            }
            break;
    }
    // Re-fetch data setelah gagal agar form tidak kosong
    switch ($type) {
        case 'artikel':    $data = get_article_by_id($id); break;
        case 'proyek':     $data = get_project_by_id($id); break;
        case 'sertifikat': $data = get_certificate_by_id($id); break;
        case 'ranking':    $data = get_ranking_by_id($id); break;
    }
}

$typeLabels = [
    'artikel'    => ['icon'=>'fa-newspaper',    'label'=>'Artikel',          'color'=>'var(--primary)'],
    'proyek'     => ['icon'=>'fa-code-branch',   'label'=>'Proyek IT',        'color'=>'#43E97B'],
    'sertifikat' => ['icon'=>'fa-certificate',   'label'=>'Sertifikat',       'color'=>'#ffc107'],
    'ranking'    => ['icon'=>'fa-trophy',         'label'=>'Peringkat Anime',  'color'=>'#FF6584'],
];
$tl = $typeLabels[$type];

include_once '../includes/header.php';
?>

<div class="container py-4 py-md-5 flex-grow-1">

    <!-- TOP NAV -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
        <a href="kelola.php?tab=<?php echo $type; ?>" class="btn btn-outline-custom d-inline-flex align-items-center">
            <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Kelola <?php echo $tl['label']; ?>
        </a>
        <a href="../index.php" class="btn btn-gradient d-inline-flex align-items-center" target="_blank">
            <i class="fa-solid fa-eye me-2"></i> Lihat Situs Utama
        </a>
    </div>

    <!-- PAGE HEADER -->
    <div class="mb-4">
        <span class="badge mb-2 px-3 py-2" style="font-size:.75rem;border-radius:30px;background:rgba(108,99,255,.15);color:var(--primary);border:1px solid rgba(108,99,255,.3);">
            <i class="fa-solid fa-pen-to-square me-1"></i> Mode Edit
        </span>
        <h2 class="text-white m-0">
            <i class="fa-solid <?php echo $tl['icon']; ?> me-2" style="color:<?php echo $tl['color']; ?>"></i>
            Edit <?php echo $tl['label']; ?>
        </h2>
        <p class="text-muted m-0 small">Ubah data yang ada, lalu klik tombol simpan.</p>
    </div>

    <!-- ALERTS -->
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

    <!-- EDIT FORM -->
    <div class="card card-custom p-4" style="max-width:720px;margin:0 auto;">
        <form method="POST" enctype="multipart/form-data">

            <?php if ($type === 'artikel'): ?>
            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Judul Artikel</label>
                <input type="text" class="form-control form-control-custom" name="judul" value="<?php echo htmlspecialchars($data['judul']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Kategori</label>
                <select class="form-select form-select-custom" name="kategori" required>
                    <option value="Informatika" <?php echo ($data['kategori']==='Informatika')?'selected':''; ?>>Informatika (IT)</option>
                    <option value="Anime" <?php echo ($data['kategori']==='Anime')?'selected':''; ?>>Anime</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Isi Artikel</label>
                <textarea class="form-control form-control-custom" name="isi" rows="8" required><?php echo htmlspecialchars($data['isi']); ?></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label text-muted small fw-semibold">Ganti Gambar Fitur <span class="text-muted">(Kosongkan jika tidak ingin mengganti)</span></label>
                <?php if (($data['gambar_fitur'] ?? 'default-post.png') !== 'default-post.png'): ?>
                <div class="mb-2"><img src="../uploads/<?php echo htmlspecialchars($data['gambar_fitur']); ?>" style="height:80px;border-radius:8px;object-fit:cover;" alt="Current"></div>
                <?php endif; ?>
                <input type="file" class="form-control form-control-custom" name="gambar_fitur" accept="image/*">
            </div>

            <?php elseif ($type === 'proyek'): ?>
            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Judul Proyek</label>
                <input type="text" class="form-control form-control-custom" name="judul_proyek" value="<?php echo htmlspecialchars($data['judul_proyek']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Deskripsi</label>
                <textarea class="form-control form-control-custom" name="deskripsi" rows="5" required><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Tech Stack</label>
                <input type="text" class="form-control form-control-custom" name="tech_stack" value="<?php echo htmlspecialchars($data['tech_stack']); ?>" placeholder="PHP, MySQL, Docker" required>
            </div>
            <div class="mb-4">
                <label class="form-label text-muted small fw-semibold">Ganti Screenshot <span class="text-muted">(Opsional)</span></label>
                <?php if (($data['gambar_proyek'] ?? 'default-project.png') !== 'default-project.png'): ?>
                <div class="mb-2"><img src="../uploads/<?php echo htmlspecialchars($data['gambar_proyek']); ?>" style="height:80px;border-radius:8px;object-fit:cover;" alt="Current"></div>
                <?php endif; ?>
                <input type="file" class="form-control form-control-custom" name="gambar_proyek" accept="image/*">
            </div>

            <?php elseif ($type === 'sertifikat'): ?>
            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Nama Sertifikat</label>
                <input type="text" class="form-control form-control-custom" name="nama_sertifikat" value="<?php echo htmlspecialchars($data['nama_sertifikat']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Penerbit</label>
                <input type="text" class="form-control form-control-custom" name="penerbit" value="<?php echo htmlspecialchars($data['penerbit']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="form-label text-muted small fw-semibold">Ganti Gambar Sertifikat <span class="text-muted">(Opsional)</span></label>
                <?php if (($data['gambar_sertifikat'] ?? 'default-cert.png') !== 'default-cert.png'): ?>
                <div class="mb-2"><img src="../uploads/<?php echo htmlspecialchars($data['gambar_sertifikat']); ?>" style="height:80px;border-radius:8px;object-fit:cover;" alt="Current"></div>
                <?php endif; ?>
                <input type="file" class="form-control form-control-custom" name="gambar_sertifikat" accept="image/*">
            </div>

            <?php elseif ($type === 'ranking'): ?>
            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Judul Anime</label>
                <input type="text" class="form-control form-control-custom" name="judul_anime" value="<?php echo htmlspecialchars($data['judul_anime']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Genre</label>
                <input type="text" class="form-control form-control-custom" name="genre" value="<?php echo htmlspecialchars($data['genre']); ?>" placeholder="Action, Fantasy, Drama" required>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label text-muted small fw-semibold">Skor Rating</label>
                    <input type="number" step="0.01" min="0" max="9.99" class="form-control form-control-custom" name="skor_rating" value="<?php echo number_format((float)$data['skor_rating'],2); ?>" required>
                </div>
                <div class="col-6">
                    <label class="form-label text-muted small fw-semibold">Posisi Rank</label>
                    <input type="number" min="1" class="form-control form-control-custom" name="posisi_rank" value="<?php echo (int)$data['posisi_rank']; ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Sinopsis</label>
                <textarea class="form-control form-control-custom" name="sinopsis" rows="4" required><?php echo htmlspecialchars($data['sinopsis']); ?></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label text-muted small fw-semibold">Ganti Gambar Anime <span class="text-muted">(Opsional)</span></label>
                <?php if (($data['gambar_anime'] ?? 'default-anime.png') !== 'default-anime.png'): ?>
                <div class="mb-2"><img src="../uploads/<?php echo htmlspecialchars($data['gambar_anime']); ?>" style="height:80px;border-radius:8px;object-fit:cover;" alt="Current"></div>
                <?php endif; ?>
                <input type="file" class="form-control form-control-custom" name="gambar_anime" accept="image/*">
            </div>
            <?php endif; ?>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-gradient flex-grow-1 py-2">
                    <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan
                </button>
                <a href="kelola.php?tab=<?php echo $type; ?>" class="btn btn-outline-custom py-2">
                    <i class="fa-solid fa-xmark me-1"></i>Batal
                </a>
            </div>
        </form>
    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
