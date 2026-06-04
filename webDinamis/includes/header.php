<?php
/**
 * Header Template - TechNime Blog
 * UAS - 2388010017
 */
// Menghubungkan ke core koneksi database
include_once dirname(__DIR__) . '/config/koneksi.php';

// Set default path prefix if not defined in pages
if (!isset($path_prefix)) {
    $path_prefix = './';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . " - TechNime Blog" : "TechNime Blog UAS - 2388010017"; ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Sleek Dark Theme Stylesheet -->
    <style>
        :root {
            --primary: #6C63FF;
            --secondary: #FF6584;
            --accent: #43E97B;
            --dark: #0A0A14;
            --card: #121226;
            --card-hover: #181832;
            --text: #F3F4F6;
            --muted: #C4C9D4;
            --border: rgba(108, 99, 255, 0.15);
            --gradient: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark);
            color: var(--text);
            overflow-x: hidden;
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Glowing background orbs */
        body::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(108, 99, 255, 0.08);
            filter: blur(100px);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            pointer-events: none;
            z-index: -1;
        }

        body::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 101, 132, 0.06);
            filter: blur(100px);
            border-radius: 50%;
            bottom: 10%;
            left: -100px;
            pointer-events: none;
            z-index: -1;
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
        }

        /* Navigation styling */
        .navbar-custom {
            background: rgba(10, 10, 20, 0.85) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border);
            padding: 0.95rem 1rem;
        }

        .navbar-brand {
            font-size: 1.45rem;
            letter-spacing: -0.5px;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            color: var(--muted) !important;
            font-size: 0.92rem;
            font-weight: 500;
            padding: 0.35rem 0.95rem !important;
            transition: color 0.25s, transform 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        /* UI Elements & Buttons */
        .btn-gradient {
            background: var(--gradient);
            color: #ffffff;
            border: none;
            font-weight: 600;
            padding: 0.55rem 1.25rem;
            border-radius: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 99, 255, 0.5);
            color: #ffffff;
        }

        .btn-outline-custom {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
            font-weight: 500;
            padding: 0.55rem 1.25rem;
            border-radius: 10px;
            transition: all 0.25s;
        }

        .btn-outline-custom:hover {
            border-color: var(--primary);
            background: rgba(108, 99, 255, 0.08);
            color: #ffffff;
        }

        /* Card components styling */
        .card-custom {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
            overflow: hidden;
            height: 100%;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            border-color: rgba(108, 99, 255, 0.35);
            box-shadow: 0 10px 25px rgba(108, 99, 255, 0.15);
            background: var(--card-hover);
        }

        /* Badges */
        .badge-it {
            background: rgba(108, 99, 255, 0.12);
            color: var(--primary);
            border: 1px solid rgba(108, 99, 255, 0.3);
            font-weight: 600;
            font-size: 0.72rem;
            padding: 0.35rem 0.75rem;
            border-radius: 30px;
            text-transform: uppercase;
        }

        .badge-anime {
            background: rgba(255, 101, 132, 0.12);
            color: var(--secondary);
            border: 1px solid rgba(255, 101, 132, 0.3);
            font-weight: 600;
            font-size: 0.72rem;
            padding: 0.35rem 0.75rem;
            border-radius: 30px;
            text-transform: uppercase;
        }

        /* Footer styling */
        .footer-custom {
            background: #06060c;
            border-top: 1px solid var(--border);
            padding: 1.8rem 0;
            margin-top: auto;
        }

        /* Form elements styling */
        .form-control-custom {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid var(--border) !important;
            color: var(--text) !important;
            border-radius: 10px !important;
            padding: 0.75rem 1rem !important;
            transition: all 0.25s !important;
        }

        .form-control-custom:focus {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 10px rgba(108, 99, 255, 0.25) !important;
            outline: none !important;
        }

        .form-select-custom {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid var(--border) !important;
            color: var(--text) !important;
            border-radius: 10px !important;
            padding: 0.75rem 1rem !important;
            transition: all 0.25s !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%239CA3AF' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
            background-position: right 1rem center !important;
            background-size: 16px 12px !important;
            background-repeat: no-repeat !important;
            appearance: none !important;
        }

        .form-select-custom:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 10px rgba(108, 99, 255, 0.25) !important;
        }

        /* FIX: Dropdown option styling for dark mode select elements */
        .form-select-custom option {
            background-color: #121226 !important;
            color: #ffffff !important;
            padding: 0.75rem 1rem !important;
        }

        /* Table styling for admin */
        .table-custom {
            background: var(--card);
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .table-custom th {
            background: rgba(108, 99, 255, 0.06);
            color: var(--text);
            font-weight: 600;
            border-bottom: 1px solid var(--border);
            padding: 1rem;
        }

        .table-custom td {
            color: #BFC5D0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            padding: 1rem;
            background: transparent;
            vertical-align: middle;
        }

        .table-custom tr:last-child td {
            border-bottom: none;
        }

        /* Global readability fixes for dark theme */
        .text-muted {
            color: #B8BEC9 !important;
        }
        p.text-muted, small.text-muted, span.text-muted {
            color: #BFC5D0 !important;
        }
        .modal-body .text-muted {
            color: #CBD0DA !important;
            font-size: 1rem;
        }
        .lead.text-muted {
            color: #C4C9D4 !important;
        }
        .form-label.text-muted {
            color: #D1D5DB !important;
        }
        /* Nav link muted color brighter */
        .nav-link {
            color: #B8BEC9 !important;
        }
        /* Card sub-text brighter */
        .card-custom p.text-muted,
        .card-custom small.text-muted {
            color: #C0C6D2 !important;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand brand-font d-flex align-items-center" href="<?php echo $path_prefix; ?>index.php#home">
                <i class="fa-solid fa-laptop-code me-2 text-primary"></i>
                <span>TechNime</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border: 1px solid var(--border);">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <!-- Navigasi Tunggal Smooth-Scroll dengan Anchor ID -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $path_prefix; ?>index.php#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $path_prefix; ?>index.php#informatika">Informatika</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $path_prefix; ?>index.php#anime">Anime</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $path_prefix; ?>index.php#portofolio">Portofolio IT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $path_prefix; ?>index.php#peringkat">Peringkat Anime</a>
                    </li>
                    
                    <!-- Dinamis: Menu Dashboard & Logout jika admin sudah login -->
                    <!-- Sesuai instruksi: Menu login admin ditiadakan dari navbar publik -->
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                            <a class="nav-link btn-outline-custom py-1 px-3 d-inline-block w-100 text-center" href="<?php echo $path_prefix; ?>admin/dashboard.php">
                                <i class="fa-solid fa-chart-line me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                            <a class="nav-link btn-gradient text-white py-1 px-3 d-inline-block w-100 text-center" href="<?php echo $path_prefix; ?>admin/logout.php">
                                <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- JavaScript Auto-Collapse Hamburger Menu untuk HP Android -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            var menuToggle = document.getElementById('navbarNav');
            
            // Periksa jika bootstrap object tersedia
            if (typeof bootstrap !== 'undefined' && menuToggle) {
                var bsCollapse = new bootstrap.Collapse(menuToggle, {toggle: false});
                
                navLinks.forEach(function(link) {
                    link.addEventListener('click', function() {
                        // Tutup otomatis jika menu hamburger sedang terbuka/show
                        if (menuToggle.classList.contains('show')) {
                            bsCollapse.hide();
                        }
                    });
                });
            }
        });
    </script>
