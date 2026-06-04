-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2026 at 04:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uasadmin_2388010017`
--
CREATE DATABASE IF NOT EXISTS `uasadmin_2388010017`;
USE `uasadmin_2388010017`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nim` varchar(20) NOT NULL DEFAULT '2388010017',
  `bio_it` text DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT 'default-avatar.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`, `nim`, `bio_it`, `foto_profil`) VALUES
(1, 'admin', '$2y$10$D/j5M94T6j3fG2qQy1aHduqMv6y4g8kZc9jS/Fv8K7hFjG4lqXbU.', 'Muhammad Arif Rizky', '2388010017', 'Informatics Student at HIMAFOR & Full-Stack Web Developer. Passionate about learning new tech, AI integrations, and analyzing top-tier anime.', 'default-avatar.png')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- --------------------------------------------------------

--
-- Table structure for table `artikel`
--

CREATE TABLE IF NOT EXISTS `artikel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `isi` text NOT NULL,
  `tanggal` date NOT NULL,
  `gambar_fitur` varchar(255) DEFAULT 'default-post.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artikel`
--

INSERT INTO `artikel` (`id`, `judul`, `kategori`, `isi`, `tanggal`, `gambar_fitur`) VALUES
(1, 'Revolusi Kecerdasan Buatan (AI) di Tahun 2026', 'Informatika', 'Perkembangan kecerdasan buatan (AI) telah mencapai puncaknya di tahun 2026. Mulai dari sistem otomasi yang cerdas, integrasi AI di perangkat mobile secara native, hingga kemampuannya dalam melakukan penalaran logis tingkat lanjut. Teknologi ini tidak hanya membantu para programmer menulis kode lebih efisien dengan AI coding assistants, tetapi juga mengubah lanskap industri kesehatan dan finansial secara masif.', '2026-06-01', 'default-post.png'),
(2, 'Review Anime Solo Leveling Season 2: Ekspektasi Fans Terbayar!', 'Anime', 'Solo Leveling Season 2 resmi dirilis dan langsung memecahkan rekor penonton. Kualitas animasi dari A-1 Pictures terbukti mengalami peningkatan yang signifikan dibandingkan season sebelumnya. Pertarungan epik Sung Jin-Woo melawan para Monarch digambarkan dengan sangat dinamis, diiringi oleh scoring musik yang megah. Season ini berhasil menjawab ekspektasi tinggi para pembaca manhwa aslinya.', '2026-06-02', 'default-post.png'),
(3, 'Masa Depan AI: Large Language Model dengan Penalaran Logis Tingkat Tinggi', 'Informatika', 'Kemajuan teknologi AI kini mengarah pada penanaman logika penalaran formal ke dalam Large Language Models (LLM). Dengan teknik seperti Chain-of-Thought yang ditingkatkan secara terstruktur, AI tidak hanya memprediksi kata berikutnya melainkan melakukan proses analisis mendalam sebelum memberikan jawaban. Ini membuka peluang besar bagi otomatisasi riset ilmiah dan pengembangan perangkat lunak yang kompleks.', '2026-06-03', 'default-post.png'),
(4, 'Daftar Anime Terpopuler yang Wajib Ditonton Musim Ini', 'Anime', 'Musim ini dipenuhi oleh berbagai judul anime menarik dari genre action, fantasy, hingga slice of life. Di barisan terdepan, kelanjutan cerita fantasi dunia sihir yang mendalam mendominasi rating di berbagai platform. Disusul oleh adaptasi manga komedi romantis yang hangat dan penuh tawa. Bagi Anda pecinta plot twist yang intens, anime psychological thriller terbaru dari studio kawakan juga sangat direkomendasikan.', '2026-06-04', 'default-post.png')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------

--
-- Table structure for table `peringkat_anime`
--

CREATE TABLE IF NOT EXISTS `peringkat_anime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul_anime` varchar(150) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `skor_rating` decimal(3,2) NOT NULL,
  `posisi_rank` int(11) NOT NULL,
  `sinopsis` text NOT NULL,
  `gambar_anime` varchar(255) NOT NULL DEFAULT 'default-anime.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peringkat_anime`
--

INSERT INTO `peringkat_anime` (`id`, `judul_anime`, `genre`, `skor_rating`, `posisi_rank`, `sinopsis`, `gambar_anime`) VALUES
(1, 'Solo Leveling Season 2: Arise from the Shadow', 'Action, Fantasy, System', 9.25, 1, 'Kisah kembalinya Hunter Sung Jin-Woo yang bertarung melawan para Monarch demi menyelamatkan umat manusia.', 'default-anime.png'),
(2, 'Demon Slayer: Infinity Castle', 'Action, Historical, Shounen', 9.10, 2, 'Kelanjutan perjuangan Tanjiro dan para Hashira memasuki kastil tak terbatas Muzan Kibutsuji.', 'default-anime.png'),
(3, 'Jujutsu Kaisen Season 3: Culling Game', 'Action, Supernatural, Drama', 8.95, 3, 'Permainan mematikan yang diinisiasi oleh Kenjaku untuk menyatukan umat manusia dengan Tengen.', 'default-anime.png'),
(4, 'Frieren: Beyond Journey\'s End Part 2', 'Adventure, Drama, Fantasy', 8.88, 4, 'Perjalanan elf penyihir bernama Frieren dalam memahami hati manusia setelah kepergian pahlawan Himmel.', 'default-anime.png'),
(5, 'Chainsaw Man Movie: Reze Arc', 'Action, Dark Fantasy, Gore', 8.75, 5, 'Pertemuan Denji dengan seorang gadis misterius bernama Reze yang ternyata adalah hybrid bomb devil.', 'default-anime.png')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------

--
-- Table structure for table `proyek_it`
--

CREATE TABLE IF NOT EXISTS `proyek_it` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul_proyek` varchar(150) NOT NULL,
  `deskripsi` text NOT NULL,
  `tech_stack` varchar(100) NOT NULL,
  `gambar_proyek` varchar(255) NOT NULL DEFAULT 'default-project.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proyek_it`
--

INSERT INTO `proyek_it` (`id`, `judul_proyek`, `deskripsi`, `tech_stack`, `gambar_proyek`) VALUES
(1, 'TechNime Portal App', 'Aplikasi portal berita interaktif yang memadukan dunia IT & pop kultur Anime menggunakan arsitektur MVC.', 'PHP Native, MySQL, Bootstrap 5', 'default-project.png'),
(2, 'Docker Automated Deployer', 'Sistem deployment otomatis berbasis kontainer menggunakan Docker Compose dan CI/CD Runner.', 'Docker, Bash Scripting, Linux', 'default-project.png'),
(3, 'Smart Library Chatbot', 'Chatbot AI interaktif terintegrasi LLM untuk sistem peminjaman buku perpustakaan kampus.', 'Python, FastAPI, OpenAI API', 'default-project.png')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------

--
-- Table structure for table `sertifikat`
--

CREATE TABLE IF NOT EXISTS `sertifikat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_sertifikat` varchar(150) NOT NULL,
  `penerbit` varchar(100) NOT NULL,
  `gambar_sertifikat` varchar(255) NOT NULL DEFAULT 'default-cert.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sertifikat`
--

INSERT INTO `sertifikat` (`id`, `nama_sertifikat`, `penerbit`, `gambar_sertifikat`) VALUES
(1, 'Google IT Automation with Python Professional', 'Google / Coursera', 'default-cert.png'),
(2, 'AWS Certified Cloud Practitioner', 'Amazon Web Services (AWS)', 'default-cert.png'),
(3, 'Menjadi Back-End Developer Pemula', 'Dicoding Indonesia', 'default-cert.png')
ON DUPLICATE KEY UPDATE `id`=`id`;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
