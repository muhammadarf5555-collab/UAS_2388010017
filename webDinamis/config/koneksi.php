<?php
/**
 * Koneksi Database & Business Logic Helper
 * TechNime Blog - UAS - 2388010017
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menangkap konfigurasi database lewat environment variables (Docker-friendly)
$db_host = getenv('DATABASE_HOST') ?: 'localhost';
$db_name = getenv('DATABASE_NAME') ?: 'uasadmin_2388010017';
$db_user = getenv('DATABASE_USER') ?: 'uas_user';
$db_pass = getenv('DATABASE_PASSWORD') ?: 'arif1234567891123';

$conn = null;
$is_db_connected = false;

try {
    // Mencoba melakukan koneksi menggunakan PDO
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $is_db_connected = true;
} catch (PDOException $e) {
    // Fallback sistem jika database MySQL offline (Mode Demo Sandbox)
    $is_db_connected = false;
    $db_connection_error = $e->getMessage();
}

// Inisialisasi Sandbox Fallback Session Data
if (!$is_db_connected) {
    // 1. Fallback Admin Info
    if (!isset($_SESSION['dummy_admin'])) {
        $_SESSION['dummy_admin'] = [
            'username' => 'admin',
            'nama_lengkap' => 'Muhammad Arif Rizky',
            'nim' => '2388010017',
            'bio_it' => 'Informatics Student at HIMAFOR & Full-Stack Web Developer. Passionate about learning new tech, AI integrations, and analyzing top-tier anime.',
            'foto_profil' => 'default-avatar.png'
        ];
    }

    // 2. Fallback Artikel
    if (!isset($_SESSION['dummy_articles'])) {
        $_SESSION['dummy_articles'] = [
            [
                'id' => 1,
                'judul' => 'Revolusi Kecerdasan Buatan (AI) di Tahun 2026',
                'kategori' => 'Informatika',
                'isi' => 'Perkembangan kecerdasan buatan (AI) telah mencapai puncaknya di tahun 2026. Mulai dari sistem otomasi yang cerdas, integrasi AI di perangkat mobile secara native, hingga kemampuannya dalam melakukan penalaran logis tingkat lanjut. Teknologi ini tidak hanya membantu para programmer menulis kode lebih efisien dengan AI coding assistants, tetapi juga mengubah lanskap industri kesehatan dan finansial secara masif.',
                'tanggal' => '2026-06-01',
                'gambar_fitur' => 'default-post.png'
            ],
            [
                'id' => 2,
                'judul' => 'Review Anime Solo Leveling Season 2: Ekspektasi Fans Terbayar!',
                'kategori' => 'Anime',
                'isi' => 'Solo Leveling Season 2 resmi dirilis dan langsung memecahkan rekor penonton. Kualitas animasi dari A-1 Pictures terbukti mengalami peningkatan yang signifikan dibandingkan season sebelumnya. Pertarungan epik Sung Jin-Woo melawan para Monarch digambarkan dengan sangat dinamis, diiringi oleh scoring musik yang megah. Season ini berhasil menjawab ekspektasi tinggi para pembaca manhwa aslinya.',
                'tanggal' => '2026-06-02',
                'gambar_fitur' => 'default-post.png'
            ],
            [
                'id' => 3,
                'judul' => 'Masa Depan AI: Large Language Model dengan Penalaran Logis Tingkat Tinggi',
                'kategori' => 'Informatika',
                'isi' => 'Kemajuan teknologi AI kini mengarah pada penanaman logika penalaran formal ke dalam Large Language Models (LLM). Dengan teknik seperti Chain-of-Thought yang ditingkatkan secara terstruktur, AI tidak hanya memprediksi kata berikutnya melainkan melakukan proses analisis mendalam sebelum memberikan jawaban. Ini membuka peluang besar bagi otomatisasi riset ilmiah dan pengembangan perangkat lunak yang kompleks.',
                'tanggal' => '2026-06-03',
                'gambar_fitur' => 'default-post.png'
            ],
            [
                'id' => 4,
                'judul' => 'Daftar Anime Terpopuler yang Wajib Ditonton Musim Ini',
                'kategori' => 'Anime',
                'isi' => 'Musim ini dipenuhi oleh berbagai judul anime menarik dari genre action, fantasy, hingga slice of life. Di barisan terdepan, kelanjutan cerita fantasi dunia sihir yang mendalam mendominasi rating di berbagai platform. Disusul oleh adaptasi manga komedi romantis yang hangat dan penuh tawa. Bagi Anda pecinta plot twist yang intens, anime psychological thriller terbaru dari studio kawakan juga sangat direkomendasikan.',
                'tanggal' => '2026-06-04',
                'gambar_fitur' => 'default-post.png'
            ]
        ];
    }

    // 3. Fallback Proyek IT
    if (!isset($_SESSION['dummy_projects'])) {
        $_SESSION['dummy_projects'] = [
            [
                'id' => 1,
                'judul_proyek' => 'TechNime Portal App',
                'deskripsi' => 'Aplikasi portal berita interaktif yang memadukan dunia IT & pop kultur Anime menggunakan arsitektur MVC.',
                'tech_stack' => 'PHP Native, MySQL, Bootstrap 5',
                'gambar_proyek' => 'default-project.png'
            ],
            [
                'id' => 2,
                'judul_proyek' => 'Docker Automated Deployer',
                'deskripsi' => 'Sistem deployment otomatis berbasis kontainer menggunakan Docker Compose dan CI/CD Runner.',
                'tech_stack' => 'Docker, Bash Scripting, Linux',
                'gambar_proyek' => 'default-project.png'
            ],
            [
                'id' => 3,
                'judul_proyek' => 'Smart Library Chatbot',
                'deskripsi' => 'Chatbot AI interaktif terintegrasi LLM untuk sistem peminjaman buku perpustakaan kampus.',
                'tech_stack' => 'Python, FastAPI, OpenAI API',
                'gambar_proyek' => 'default-project.png'
            ]
        ];
    }

    // 4. Fallback Sertifikat
    if (!isset($_SESSION['dummy_certs'])) {
        $_SESSION['dummy_certs'] = [
            [
                'id' => 1,
                'nama_sertifikat' => 'Google IT Automation with Python Professional',
                'penerbit' => 'Google / Coursera',
                'gambar_sertifikat' => 'default-cert.png'
            ],
            [
                'id' => 2,
                'nama_sertifikat' => 'AWS Certified Cloud Practitioner',
                'penerbit' => 'Amazon Web Services (AWS)',
                'gambar_sertifikat' => 'default-cert.png'
            ],
            [
                'id' => 3,
                'nama_sertifikat' => 'Menjadi Back-End Developer Pemula',
                'penerbit' => 'Dicoding Indonesia',
                'gambar_sertifikat' => 'default-cert.png'
            ]
        ];
    }

    // 5. Fallback Peringkat Anime
    if (!isset($_SESSION['dummy_rankings'])) {
        $_SESSION['dummy_rankings'] = [
            [
                'id' => 1,
                'judul_anime' => 'Solo Leveling Season 2: Arise from the Shadow',
                'genre' => 'Action, Fantasy, System',
                'skor_rating' => 9.25,
                'posisi_rank' => 1,
                'sinopsis' => 'Kisah kembalinya Hunter Sung Jin-Woo yang bertarung melawan para Monarch demi menyelamatkan umat manusia.',
                'gambar_anime' => 'default-anime.png'
            ],
            [
                'id' => 2,
                'judul_anime' => 'Demon Slayer: Infinity Castle',
                'genre' => 'Action, Historical, Shounen',
                'skor_rating' => 9.10,
                'posisi_rank' => 2,
                'sinopsis' => 'Kelanjutan perjuangan Tanjiro dan para Hashira memasuki kastil tak terbatas Muzan Kibutsuji.',
                'gambar_anime' => 'default-anime.png'
            ],
            [
                'id' => 3,
                'judul_anime' => 'Jujutsu Kaisen Season 3: Culling Game',
                'genre' => 'Action, Supernatural, Drama',
                'skor_rating' => 8.95,
                'posisi_rank' => 3,
                'sinopsis' => 'Permainan mematikan yang diinisiasi oleh Kenjaku untuk menyatukan umat manusia dengan Tengen.',
                'gambar_anime' => 'default-anime.png'
            ],
            [
                'id' => 4,
                'judul_anime' => 'Frieren: Beyond Journey\'s End Part 2',
                'genre' => 'Adventure, Drama, Fantasy',
                'skor_rating' => 8.88,
                'posisi_rank' => 4,
                'sinopsis' => 'Perjalanan elf penyihir bernama Frieren dalam memahami hati manusia setelah kepergian pahlawan Himmel.',
                'gambar_anime' => 'default-anime.png'
            ],
            [
                'id' => 5,
                'judul_anime' => 'Chainsaw Man Movie: Reze Arc',
                'genre' => 'Action, Dark Fantasy, Gore',
                'skor_rating' => 8.75,
                'posisi_rank' => 5,
                'sinopsis' => 'Pertemuan Denji dengan seorang gadis misterius bernama Reze yang ternyata adalah hybrid bomb devil.',
                'gambar_anime' => 'default-anime.png'
            ]
        ];
    }
}

// --------------------------------------------------------
// BUSINESS LOGIC HELPERS: ARTIKEL
// --------------------------------------------------------

function get_all_articles($kategori = null) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            if ($kategori) {
                $stmt = $conn->prepare("SELECT * FROM artikel WHERE kategori = :kategori ORDER BY tanggal DESC, id DESC");
                $stmt->execute(['kategori' => $kategori]);
            } else {
                $stmt = $conn->query("SELECT * FROM artikel ORDER BY tanggal DESC, id DESC");
            }
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return get_dummy_articles($kategori);
        }
    }
    return get_dummy_articles($kategori);
}

function get_dummy_articles($kategori = null) {
    $articles = $_SESSION['dummy_articles'] ?? [];
    usort($articles, function($a, $b) {
        return strtotime($b['tanggal']) - strtotime($a['tanggal']);
    });
    if ($kategori) {
        return array_filter($articles, function($item) use ($kategori) {
            return strcasecmp($item['kategori'], $kategori) === 0;
        });
    }
    return $articles;
}

function add_article($judul, $kategori, $isi, $tanggal, $gambar = 'default-post.png') {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->prepare("INSERT INTO artikel (judul, kategori, isi, tanggal, gambar_fitur) VALUES (:judul, :kategori, :isi, :tanggal, :gambar)");
            return $stmt->execute([
                'judul' => $judul,
                'kategori' => $kategori,
                'isi' => $isi,
                'tanggal' => $tanggal,
                'gambar' => $gambar
            ]);
        } catch (PDOException $e) {
            return add_dummy_article($judul, $kategori, $isi, $tanggal, $gambar);
        }
    }
    return add_dummy_article($judul, $kategori, $isi, $tanggal, $gambar);
}

function add_dummy_article($judul, $kategori, $isi, $tanggal, $gambar) {
    $new_id = time();
    $_SESSION['dummy_articles'][] = [
        'id' => $new_id,
        'judul' => $judul,
        'kategori' => $kategori,
        'isi' => $isi,
        'tanggal' => $tanggal,
        'gambar_fitur' => $gambar
    ];
    return true;
}

function delete_article($id) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->prepare("DELETE FROM artikel WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return delete_dummy_article($id);
        }
    }
    return delete_dummy_article($id);
}

function delete_dummy_article($id) {
    $_SESSION['dummy_articles'] = array_filter($_SESSION['dummy_articles'] ?? [], function($item) use ($id) {
        return (int)$item['id'] !== (int)$id;
    });
    return true;
}

// --------------------------------------------------------
// BUSINESS LOGIC HELPERS: PROYEK IT
// --------------------------------------------------------

function get_all_projects() {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            return $conn->query("SELECT * FROM proyek_it ORDER BY id DESC")->fetchAll();
        } catch (PDOException $e) {
            return $_SESSION['dummy_projects'] ?? [];
        }
    }
    return $_SESSION['dummy_projects'] ?? [];
}

function add_project($judul, $deskripsi, $tech, $gambar = 'default-project.png') {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->prepare("INSERT INTO proyek_it (judul_proyek, deskripsi, tech_stack, gambar_proyek) VALUES (:judul, :deskripsi, :tech, :gambar)");
            return $stmt->execute([
                'judul' => $judul,
                'deskripsi' => $deskripsi,
                'tech' => $tech,
                'gambar' => $gambar
            ]);
        } catch (PDOException $e) {
            return add_dummy_project($judul, $deskripsi, $tech, $gambar);
        }
    }
    return add_dummy_project($judul, $deskripsi, $tech, $gambar);
}

function add_dummy_project($judul, $deskripsi, $tech, $gambar) {
    $_SESSION['dummy_projects'][] = [
        'id' => time(),
        'judul_proyek' => $judul,
        'deskripsi' => $deskripsi,
        'tech_stack' => $tech,
        'gambar_proyek' => $gambar
    ];
    return true;
}

function delete_project($id) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->prepare("DELETE FROM proyek_it WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return delete_dummy_project($id);
        }
    }
    return delete_dummy_project($id);
}

function delete_dummy_project($id) {
    $_SESSION['dummy_projects'] = array_filter($_SESSION['dummy_projects'] ?? [], function($item) use ($id) {
        return (int)$item['id'] !== (int)$id;
    });
    return true;
}

// --------------------------------------------------------
// BUSINESS LOGIC HELPERS: SERTIFIKAT
// --------------------------------------------------------

function get_all_certificates() {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            return $conn->query("SELECT * FROM sertifikat ORDER BY id DESC")->fetchAll();
        } catch (PDOException $e) {
            return $_SESSION['dummy_certs'] ?? [];
        }
    }
    return $_SESSION['dummy_certs'] ?? [];
}

function add_certificate($nama, $penerbit, $gambar = 'default-cert.png') {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->prepare("INSERT INTO sertifikat (nama_sertifikat, penerbit, gambar_sertifikat) VALUES (:nama, :penerbit, :gambar)");
            return $stmt->execute([
                'nama' => $nama,
                'penerbit' => $penerbit,
                'gambar' => $gambar
            ]);
        } catch (PDOException $e) {
            return add_dummy_certificate($nama, $penerbit, $gambar);
        }
    }
    return add_dummy_certificate($nama, $penerbit, $gambar);
}

function add_dummy_certificate($nama, $penerbit, $gambar) {
    $_SESSION['dummy_certs'][] = [
        'id' => time(),
        'nama_sertifikat' => $nama,
        'penerbit' => $penerbit,
        'gambar_sertifikat' => $gambar
    ];
    return true;
}

function delete_certificate($id) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->prepare("DELETE FROM sertifikat WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return delete_dummy_certificate($id);
        }
    }
    return delete_dummy_certificate($id);
}

function delete_dummy_certificate($id) {
    $_SESSION['dummy_certs'] = array_filter($_SESSION['dummy_certs'] ?? [], function($item) use ($id) {
        return (int)$item['id'] !== (int)$id;
    });
    return true;
}

// --------------------------------------------------------
// BUSINESS LOGIC HELPERS: PERINGKAT ANIME
// --------------------------------------------------------

function get_all_rankings() {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            // Wajib diurutkan berdasarkan skor rating tertinggi
            return $conn->query("SELECT * FROM peringkat_anime ORDER BY skor_rating DESC, id ASC")->fetchAll();
        } catch (PDOException $e) {
            return get_dummy_rankings();
        }
    }
    return get_dummy_rankings();
}

function get_dummy_rankings() {
    $ranks = $_SESSION['dummy_rankings'] ?? [];
    usort($ranks, function($a, $b) {
        return ($b['skor_rating'] > $a['skor_rating']) ? 1 : -1;
    });
    return $ranks;
}

function add_ranking($judul, $genre, $skor, $rank, $sinopsis, $gambar = 'default-anime.png') {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->prepare("INSERT INTO peringkat_anime (judul_anime, genre, skor_rating, posisi_rank, sinopsis, gambar_anime) VALUES (:judul, :genre, :skor, :rank, :sinopsis, :gambar)");
            return $stmt->execute([
                'judul' => $judul,
                'genre' => $genre,
                'skor' => $skor,
                'rank' => $rank,
                'sinopsis' => $sinopsis,
                'gambar' => $gambar
            ]);
        } catch (PDOException $e) {
            return add_dummy_ranking($judul, $genre, $skor, $rank, $sinopsis, $gambar);
        }
    }
    return add_dummy_ranking($judul, $genre, $skor, $rank, $sinopsis, $gambar);
}

function add_dummy_ranking($judul, $genre, $skor, $rank, $sinopsis, $gambar) {
    $_SESSION['dummy_rankings'][] = [
        'id' => time(),
        'judul_anime' => $judul,
        'genre' => $genre,
        'skor_rating' => (float)$skor,
        'posisi_rank' => (int)$rank,
        'sinopsis' => $sinopsis,
        'gambar_anime' => $gambar
    ];
    return true;
}

function delete_ranking($id) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->prepare("DELETE FROM peringkat_anime WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return delete_dummy_ranking($id);
        }
    }
    return delete_dummy_ranking($id);
}

function delete_dummy_ranking($id) {
    $_SESSION['dummy_rankings'] = array_filter($_SESSION['dummy_rankings'] ?? [], function($item) use ($id) {
        return (int)$item['id'] !== (int)$id;
    });
    return true;
}

// --------------------------------------------------------
// BUSINESS LOGIC HELPERS: UPDATE / EDIT
// --------------------------------------------------------

function get_article_by_id($id) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->prepare("SELECT * FROM artikel WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {}
    }
    foreach ($_SESSION['dummy_articles'] ?? [] as $a) {
        if ((int)$a['id'] === (int)$id) return $a;
    }
    return null;
}

function update_article($id, $judul, $kategori, $isi, $gambar = null) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            if ($gambar) {
                $stmt = $conn->prepare("UPDATE artikel SET judul=:judul, kategori=:kategori, isi=:isi, gambar_fitur=:gambar WHERE id=:id");
                return $stmt->execute(['judul'=>$judul,'kategori'=>$kategori,'isi'=>$isi,'gambar'=>$gambar,'id'=>$id]);
            } else {
                $stmt = $conn->prepare("UPDATE artikel SET judul=:judul, kategori=:kategori, isi=:isi WHERE id=:id");
                return $stmt->execute(['judul'=>$judul,'kategori'=>$kategori,'isi'=>$isi,'id'=>$id]);
            }
        } catch (PDOException $e) {}
    }
    foreach ($_SESSION['dummy_articles'] ?? [] as &$a) {
        if ((int)$a['id'] === (int)$id) {
            $a['judul'] = $judul; $a['kategori'] = $kategori; $a['isi'] = $isi;
            if ($gambar) $a['gambar_fitur'] = $gambar;
            return true;
        }
    }
    return true;
}

function get_project_by_id($id) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->prepare("SELECT * FROM proyek_it WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {}
    }
    foreach ($_SESSION['dummy_projects'] ?? [] as $p) {
        if ((int)$p['id'] === (int)$id) return $p;
    }
    return null;
}

function update_project($id, $judul, $deskripsi, $tech, $gambar = null) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            if ($gambar) {
                $stmt = $conn->prepare("UPDATE proyek_it SET judul_proyek=:judul, deskripsi=:deskripsi, tech_stack=:tech, gambar_proyek=:gambar WHERE id=:id");
                return $stmt->execute(['judul'=>$judul,'deskripsi'=>$deskripsi,'tech'=>$tech,'gambar'=>$gambar,'id'=>$id]);
            } else {
                $stmt = $conn->prepare("UPDATE proyek_it SET judul_proyek=:judul, deskripsi=:deskripsi, tech_stack=:tech WHERE id=:id");
                return $stmt->execute(['judul'=>$judul,'deskripsi'=>$deskripsi,'tech'=>$tech,'id'=>$id]);
            }
        } catch (PDOException $e) {}
    }
    foreach ($_SESSION['dummy_projects'] ?? [] as &$p) {
        if ((int)$p['id'] === (int)$id) {
            $p['judul_proyek'] = $judul; $p['deskripsi'] = $deskripsi; $p['tech_stack'] = $tech;
            if ($gambar) $p['gambar_proyek'] = $gambar;
            return true;
        }
    }
    return true;
}

function get_certificate_by_id($id) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->prepare("SELECT * FROM sertifikat WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {}
    }
    foreach ($_SESSION['dummy_certs'] ?? [] as $c) {
        if ((int)$c['id'] === (int)$id) return $c;
    }
    return null;
}

function update_certificate($id, $nama, $penerbit, $gambar = null) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            if ($gambar) {
                $stmt = $conn->prepare("UPDATE sertifikat SET nama_sertifikat=:nama, penerbit=:penerbit, gambar_sertifikat=:gambar WHERE id=:id");
                return $stmt->execute(['nama'=>$nama,'penerbit'=>$penerbit,'gambar'=>$gambar,'id'=>$id]);
            } else {
                $stmt = $conn->prepare("UPDATE sertifikat SET nama_sertifikat=:nama, penerbit=:penerbit WHERE id=:id");
                return $stmt->execute(['nama'=>$nama,'penerbit'=>$penerbit,'id'=>$id]);
            }
        } catch (PDOException $e) {}
    }
    foreach ($_SESSION['dummy_certs'] ?? [] as &$c) {
        if ((int)$c['id'] === (int)$id) {
            $c['nama_sertifikat'] = $nama; $c['penerbit'] = $penerbit;
            if ($gambar) $c['gambar_sertifikat'] = $gambar;
            return true;
        }
    }
    return true;
}

function get_ranking_by_id($id) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->prepare("SELECT * FROM peringkat_anime WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {}
    }
    foreach ($_SESSION['dummy_rankings'] ?? [] as $r) {
        if ((int)$r['id'] === (int)$id) return $r;
    }
    return null;
}

function update_ranking($id, $judul, $genre, $skor, $rank, $sinopsis, $gambar = null) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            if ($gambar) {
                $stmt = $conn->prepare("UPDATE peringkat_anime SET judul_anime=:judul, genre=:genre, skor_rating=:skor, posisi_rank=:rank, sinopsis=:sinopsis, gambar_anime=:gambar WHERE id=:id");
                return $stmt->execute(['judul'=>$judul,'genre'=>$genre,'skor'=>$skor,'rank'=>$rank,'sinopsis'=>$sinopsis,'gambar'=>$gambar,'id'=>$id]);
            } else {
                $stmt = $conn->prepare("UPDATE peringkat_anime SET judul_anime=:judul, genre=:genre, skor_rating=:skor, posisi_rank=:rank, sinopsis=:sinopsis WHERE id=:id");
                return $stmt->execute(['judul'=>$judul,'genre'=>$genre,'skor'=>$skor,'rank'=>$rank,'sinopsis'=>$sinopsis,'id'=>$id]);
            }
        } catch (PDOException $e) {}
    }
    foreach ($_SESSION['dummy_rankings'] ?? [] as &$r) {
        if ((int)$r['id'] === (int)$id) {
            $r['judul_anime'] = $judul; $r['genre'] = $genre;
            $r['skor_rating'] = (float)$skor; $r['posisi_rank'] = (int)$rank;
            $r['sinopsis'] = $sinopsis;
            if ($gambar) $r['gambar_anime'] = $gambar;
            return true;
        }
    }
    return true;
}

// --------------------------------------------------------
// BUSINESS LOGIC HELPERS: ADMIN AUTH & GENERAL
// --------------------------------------------------------

function get_admin_info() {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->query("SELECT * FROM admin LIMIT 1");
            $admin = $stmt->fetch();
            if ($admin) return $admin;
        } catch (PDOException $e) {}
    }
    return $_SESSION['dummy_admin'] ?? [
        'username' => 'admin',
        'nama_lengkap' => 'Muhammad Arif Rizky',
        'nim' => '2388010017',
        'bio_it' => 'Informatics Student at HIMAFOR & Full-Stack Web Developer. Passionate about learning new tech, AI integrations, and analyzing top-tier anime.',
        'foto_profil' => 'default-avatar.png'
    ];
}

function authenticate_admin($username, $password) {
    global $conn, $is_db_connected;
    if ($is_db_connected) {
        try {
            $stmt = $conn->prepare("SELECT * FROM admin WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $admin = $stmt->fetch();
            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_nama'] = $admin['nama_lengkap'];
                return true;
            }
        } catch (PDOException $e) {}
    }
    
    // Fallback static credentials
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = 'admin';
        $_SESSION['admin_nama'] = 'Muhammad Arif Rizky';
        return true;
    }
    return false;
}

function check_admin_login() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php");
        exit;
    }
}

function get_synopsis($text, $limit = 120) {
    if (strlen($text) > $limit) {
        $substring = substr($text, 0, $limit);
        $last_space = strrpos($substring, ' ');
        if ($last_space !== false) {
            return substr($substring, 0, $last_space) . '...';
        }
        return $substring . '...';
    }
    return $text;
}
