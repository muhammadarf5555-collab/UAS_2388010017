<?php
/**
 * Halaman Login Admin - TechNime Blog
 * UAS Web Programming - 2388010017
 */
include_once '../config/koneksi.php';

$path_prefix = '../';
$page_title  = 'Login Admin';

// Jika admin sudah login, redirect ke dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error_msg = '';

// Proses form login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error_msg = 'Semua kolom input wajib diisi!';
    } else {
        if (authenticate_admin($username, $password)) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error_msg = 'Username atau Password salah!';
        }
    }
}

include_once '../includes/header.php';
?>

<div class="container flex-grow-1 d-flex align-items-center justify-content-center py-5">
    <div class="w-100" style="max-width: 420px;">

        <!-- Logo Header -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:70px;height:70px;background:rgba(108,99,255,.1);border:2px solid rgba(108,99,255,.3);color:var(--primary);">
                <i class="fa-solid fa-user-shield fa-2x"></i>
            </div>
            <h3 class="text-white brand-font m-0">Admin Portal</h3>
            <p class="text-muted small mt-1">Masuk untuk mengelola konten TechNime Blog</p>
        </div>

        <!-- Card Login -->
        <div class="card card-custom p-4 shadow-lg">
            <div class="card-body p-2">

                <?php if ($error_msg): ?>
                <div class="alert p-3 mb-4 text-center small d-flex align-items-center justify-content-center" style="border-radius:10px;background:rgba(220,38,38,.12);border:1px solid rgba(220,38,38,.2);color:#f87171;">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <div><?php echo htmlspecialchars($error_msg); ?></div>
                </div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label text-muted small fw-semibold text-uppercase">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent" style="border:1px solid var(--border);border-top-left-radius:10px;border-bottom-left-radius:10px;color:var(--muted);border-right:0;">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input type="text" class="form-control form-control-custom" id="username" name="username" placeholder="Masukkan username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required style="border-top-left-radius:0!important;border-bottom-left-radius:0!important;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label text-muted small fw-semibold text-uppercase">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent" style="border:1px solid var(--border);border-top-left-radius:10px;border-bottom-left-radius:10px;color:var(--muted);border-right:0;">
                                <i class="fa-solid fa-key"></i>
                            </span>
                            <input type="password" class="form-control form-control-custom" id="password" name="password" placeholder="Masukkan password" required style="border-top-left-radius:0!important;border-bottom-left-radius:0!important;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gradient w-100 py-2">
                        Masuk Ke Dashboard <i class="fa-solid fa-right-to-bracket ms-1"></i>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="../index.php" class="text-muted small text-decoration-none" style="transition:color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color=''">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <div class="p-3" style="border-radius:12px;border:1px solid var(--border);background:rgba(255,255,255,.01);">
                <p class="text-muted small m-0"><i class="fa-solid fa-info-circle text-primary me-1"></i> <strong>Kredensial Default:</strong></p>
                <code class="text-secondary small">Username: admin | Password: admin123</code>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
