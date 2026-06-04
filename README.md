# TechNime Blog & CV Statis - UAS Cloud Native & CI/CD
Aplikasi Web Multi-Kontainer dengan GitHub Actions CI/CD & AWS Deployment

---

## 👤 Identitas Mahasiswa
* **Nama:** Muhammad Arif Rizky
* **NIM:** 2388010017
* **Mata Kuliah:** Web Programming (UAS)
* **Instansi:** Universitas Swasta

---

## 🏛️ Topologi Arsitektur Cloud Native

Berikut adalah visualisasi arsitektur sistem dari mesin lokal hingga server produksi AWS EC2:

```mermaid
graph TD
    subgraph Local_Development [Komputer Lokal / Developer]
        Code[Source Code]
        Git[Git Commit & Push]
        Code --> Git
    end

    subgraph GitHub_Remote [GitHub Repository]
        Repo[Repository UAS_2388010017]
        Workflows{GitHub Actions Workflows}
        
        Git -->|Push to main| Repo
        Repo --> Workflows
        
        subgraph Pipelines [Isolated Pipelines - Paths Filter]
            PD[ci-cd-dinamis.yml]
            PS[ci-cd-statis.yml]
        end
        
        Workflows -->|webDinamis/**| PD
        Workflows -->|webStatis/**| PS
    end

    subgraph Docker_Registry [Docker Hub]
        ImgD[muharifrizky0225pohv/uasdinamis:latest]
        ImgS[muharifrizky0225pohv/uasstatis:latest]
    end

    PD -->|1. Build & Push| ImgD
    PS -->|1. Build & Push| ImgS

    subgraph Production_Server [AWS EC2 Instance]
        subgraph Docker_Compose [Docker Compose Orchestration]
            Nginx[web-statis: Nginx Alpine]
            PHP[web-dinamis: PHP 8.2 Apache]
            DB[(db: MariaDB 10.11)]
            
            Nginx -.->|Internal Network| PHP
            PHP -->|PDO Connection| DB
        end
    end

    PD -->|2. SSH Trigger| Production_Server
    PS -->|2. SSH Trigger| Production_Server
    
    ImgD -->|Docker Pull| PHP
    ImgS -->|Docker Pull| Nginx
    
    subgraph Clients [Public Access]
        User[Browser Pengguna]
        User -->|Port 80| Nginx
        User -->|Port 8080| PHP
    end
```

---

## 🌐 Rincian Port Mapping & Jaringan Internal

Sistem ini dikonfigurasi menggunakan **Docker Bridge Network** (`technime_net`) untuk memungkinkan komunikasi antar-kontainer secara aman menggunakan DNS internal:

| Kontainer | Service Name | Port Eksternal (Host) | Port Internal (Kontainer) | Peran |
| :--- | :--- | :--- | :--- | :--- |
| **web-statis** | `web-statis` | `80` | `80` | Menyajikan Web CV UTS (Nginx Alpine) |
| **web-dinamis** | `web-dinamis` | `8080` | `80` | Menyajikan TechNime Blog (PHP Apache) |
| **db** | `db` | `3306` | `3306` | Penyimpanan data (MariaDB 10.11) |

> [!TIP]
> Kontainer `web-dinamis` terhubung ke database `db` menggunakan DNS internal **`db`** (bukan IP hardcoded), dikonfigurasi melalui environment variable `DATABASE_HOST=db`.

---

## 🔑 Konfigurasi Environment Variables (`.env`)

Seluruh kredensial database disimpan secara aman menggunakan file `.env` di server/lokal, dan dibaca secara dinamis oleh `docker-compose.yml`:

```env
# KREDENSIAL DATABASE (MariaDB)
DATABASE_HOST=db
DATABASE_NAME=uasadmin_2388010017
DATABASE_USER=uas_user
DATABASE_PASSWORD=arif1234567891123

# KREDENSIAL ROOT DATABASE (Untuk kontainer MariaDB)
MYSQL_ROOT_PASSWORD=rootpassword
```

---

## ⚡ Alur CI/CD: Paths Filter & Zero-Touch Deployment

Workflow GitHub Actions dibagi menjadi 2 file terpisah untuk menghemat resource runner dan mencegah eksekusi berulang jika salah satu aplikasi tidak diubah:

1. **`ci-cd-dinamis.yml`**
   - **Pemicu:** Perubahan di folder `webDinamis/**` atau `.github/workflows/ci-cd-dinamis.yml`.
   - **Tugas:** Menjalankan PHP Syntax Linting -> Build Image -> Push ke `muharifrizky0225pohv/uasdinamis:latest` -> SSH login ke AWS EC2 -> Jalankan `docker compose pull web-dinamis` & `docker compose up -d web-dinamis`.
   
2. **`ci-cd-statis.yml`**
   - **Pemicu:** Perubahan di folder `webStatis/**` atau `.github/workflows/ci-cd-statis.yml`.
   - **Tugas:** Build Image -> Push ke `muharifrizky0225pohv/uasstatis:latest` -> SSH login ke AWS EC2 -> Jalankan `docker compose pull web-statis` & `docker compose up -d web-statis`.

---

## 🛠️ Panduan Menjalankan Secara Lokal

1. Clone repositori ini:
   ```bash
   git clone https://github.com/muhammadarf5555-collab/UAS_2388010017.git
   cd UAS_2388010017
   ```
2. Salin template `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
3. Jalankan docker compose:
   ```bash
   docker compose up -d
   ```
4. Akses di browser Anda:
   - Web CV Statis: `http://localhost`
   - Web Dinamis Blog: `http://localhost:8080`

---

## 🚀 Panduan Setup Deployment AWS (Untuk Nilai 100)

Agar fitur **Zero-Touch Deployment** berfungsi secara otomatis setiap kali Anda melakukan `git push`, lakukan langkah berikut:

### 1. Konfigurasi GitHub Repository Secrets
Buka repositori GitHub Anda, masuk ke **Settings > Secrets and variables > Actions**, lalu tambahkan 5 Secrets berikut:
* `DOCKER_USERNAME` : Username Docker Hub Anda (`muharifrizky0225pohv`)
* `DOCKER_PASSWORD` : Password Docker Hub atau Access Token Anda
* `SSH_HOST` : Alamat IP Publik instansi AWS EC2 Anda (misal: `54.255.xx.xx`)
* `SSH_USERNAME` : Username login SSH server (biasanya `ubuntu` untuk Ubuntu)
* `SSH_KEY` : Isi seluruh file privat key `.pem` Anda (mulai dari `-----BEGIN RSA PRIVATE KEY-----` sampai akhir)

### 2. Setup Awal di Server AWS EC2 Anda
Masuk ke EC2 Anda melalui SSH lokal, lalu lakukan setup clone pertama kali:
```bash
# 1. Pastikan Docker & Docker Compose terinstal di EC2
sudo apt update
sudo apt install docker.io docker-compose-plugin -y
sudo usermod -aG docker $USER
# Logout dan login kembali SSH agar permission docker aktif

# 2. Clone repositori ke home directory
cd ~
git clone https://github.com/muhammadarf5555-collab/UAS_2388010017.git
cd UAS_2388010017

# 3. Salin file environment dan sesuaikan jika ada perubahan
cp .env.example .env

# 4. Jalankan sistem pertama kali
docker compose up -d
```

### 3. Pengujian Live Update (Zero-Touch Deployment)
1. Ubah tulisan atau kode pada file `webStatis/index.html` atau `webDinamis/index.php` secara lokal.
2. Commit dan push ke GitHub:
   ```bash
   git add .
   git commit -m "Uji coba Live Update Zero Downtime"
   git push origin main
   ```
3. Buka tab **Actions** di GitHub untuk melihat pipeline berjalan otomatis.
4. Setelah pipeline centang hijau, buka IP Publik AWS di browser Anda, dan lihat perubahan kode Anda langsung ter-update secara instan tanpa perlu menyentuh terminal server lagi!
