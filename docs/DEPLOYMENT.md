# 📘 Panduan Deployment & Worker Queue MariLMS (v1.4.0 - Single-Database Tenancy)

Dokumen ini berisi panduan lengkap langkah demi langkah untuk melakukan deploy aplikasi **MariLMS v1.4.0** pada 4 jenis lingkungan (*environment*), serta konfigurasi pemrosesan tugas latar belakang (**Laravel Queue Worker / `php artisan queue:work`**).

---

## 📑 Daftar Isi
1. [Manajemen Background Queue Worker (`php artisan queue:work`)](#-1-manajemen-background-queue-worker-php-artisan-queuework)
   - [A. aaPanel (Plugin Supervisor Manager)](#a-aapanel-plugin-supervisor-manager---rekomendasi-vps)
   - [B. VPS Linux Murni (Supervisor CLI)](#b-vps-linux-murni-supervisor-cli)
   - [C. VPS Linux (Systemd Service)](#c-vps-linux-systemd-service)
   - [D. Shared Hosting (Cron Job Fallback)](#d-shared-hosting-cpanel--directadmin-cron-job)
   - [E. Local Development (Windows / Laragon / macOS)](#e-local-development-windows--laragon--macos)
2. [Deployment: Local Development](#-2-local-development-laragon--xampp)
3. [Deployment: VPS dengan aaPanel](#-3-vps-dengan-aapanel)
4. [Deployment: VPS Linux Murni (Ubuntu / Debian + Nginx)](#-4-vps-linux-murni-ubuntu--debian--nginx)
5. [Deployment: Shared Hosting (cPanel / DirectAdmin)](#-5-shared-hosting-cpanel--directadmin)
6. [Perintah Pemeliharaan & Optimasi Produksi](#-6-perintah-pemeliharaan--optimasi-produksi)

---

## ⚙️ 1. Manajemen Background Queue Worker (`php artisan queue:work`)

Aplikasi **MariLMS** menggunakan Laravel Queue untuk menangani tugas-tugas berat di latar belakang agar antarmuka web tetap responsif tanpa *lag* / *timeout*:
- Generating kuis berbantuan AI (LLM OpenRouter).
- Pengiriman notifikasi WhatsApp & Email otomatis.
- Pemrosesan pembuatan laporan kuis & ekspor data.

---

### A. aaPanel (Plugin Supervisor Manager - Rekomendasi VPS)

Jika Anda menggunakan VPS aaPanel, cara paling mudah dan stabil adalah menggunakan plugin **Supervisor Manager**:

1. Buka **aaPanel Dashboard > App Store**.
2. Cari **Supervisor Manager**, klik **Install**.
3. Buka **Supervisor Manager**, klik tombol **Add Daemon**.
4. Isi formulir konfigurasi berikut:
   - **Name:** `marilms-worker`
   - **Run User:** `www`
   - **Path:** `/www/wwwroot/marilms.maripartner.com`
   - **Command:** `php artisan queue:work --sleep=3 --tries=3 --max-time=3600`
   - **Processes:** `2` (sesuaikan dengan core CPU server)
5. Klik **Save**.
6. Status daemon akan berubah menjadi **Running** (Hijau). Supervisor akan otomatis merestart worker jika crash atau server di-reboot.

---

### B. VPS Linux Murni (Supervisor CLI)

Untuk VPS Ubuntu/Debian tanpa control panel, gunakan paket `supervisor`:

1. **Install Supervisor:**
   ```bash
   sudo apt update
   sudo apt install -y supervisor
   ```

2. **Buat File Konfigurasi Worker:**
   ```bash
   sudo nano /etc/supervisor/conf.d/marilms-worker.conf
   ```
   Isi dengan konfigurasi berikut:
   ```ini
   [program:marilms-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /var/www/marilms/artisan queue:work --sleep=3 --tries=3 --max-time=3600
   autostart=true
   autorestart=true
   stopasgroup=true
   killasgroup=true
   user=www-data
   numprocs=2
   redirect_stderr=true
   stdout_logfile=/var/www/marilms/storage/logs/worker.log
   stopwaitsecs=3600
   ```

3. **Aktifkan & Jalankan Service:**
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start marilms-worker:*
   ```

4. **Perintah Cek Status:**
   ```bash
   sudo supervisorctl status
   ```

---

### C. VPS Linux (Systemd Service)

Alternatif tanpa Supervisor, menggunakan native **Systemd Service**:

1. **Buat File Service:**
   ```bash
   sudo nano /etc/systemd/system/marilms-worker.service
   ```
   Isi berkas:
   ```ini
   [Unit]
   Description=MariLMS Queue Worker
   After=network.target

   [Service]
   User=www-data
   Group=www-data
   Restart=always
   ExecStart=/usr/bin/php /var/www/marilms/artisan queue:work --sleep=3 --tries=3 --max-time=3600

   [Install]
   WantedBy=multi-user.target
   ```

2. **Aktifkan & Jalankan Service:**
   ```bash
   sudo systemctl daemon-reload
   sudo systemctl enable marilms-worker
   sudo systemctl start marilms-worker
   ```

---

### D. Shared Hosting (cPanel / DirectAdmin - Cron Job)

Di Shared Hosting tanpa akses terminal daemon permanen, gunakan **Cron Job** cPanel:

1. Buka **cPanel > Cron Jobs**.
2. Set waktu ke **Every Minute** (`* * * * *`).
3. Masukkan perintah berikut (sesuaikan path):
   ```bash
   /usr/local/bin/php /home/username/marilms_app/artisan queue:work --stop-when-empty >> /dev/null 2>&1
   ```
   *Opsi `--stop-when-empty` akan memproses semua antrean sampai habis lalu keluar dengan rapi setiap menit tanpa menumpuk proses memory.*

---

### E. Local Development (Windows / Laragon / macOS)

Untuk pengembangan lokal:

#### Cara 1: Terminal Manual
```bash
php artisan queue:work
```

#### Cara 2: Pail / Concurrently (Otomatis saat `npm run dev`)
Jalankan script dev terintegrasi di `package.json` / `composer.json`:
```bash
npm run dev
```

---

## 💻 2. Local Development (Laragon / XAMPP)

### Prasyarat
- PHP `>= 8.2` (Ekstensi wajib: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `json`, `tokenizer`, `xml`, `bcmath`)
- Composer `>= 2.x` & Node.js `>= 18.x`
- MySQL / MariaDB

### Langkah Deployment
```bash
# 1. Clone & Masuk Folder Proyek
git clone https://github.com/hndko/app_marilms_laravel12.git
cd app_marilms_laravel12

# 2. Install Dependensi
composer install
npm install

# 3. Salin .env & Generate Key
cp .env.example .env
php artisan key:generate

# 4. Atur Koneksi Database di .env:
# DB_DATABASE=app_marilms
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Migration & Seeding
php artisan migrate:fresh --seed

# 6. Jalankan Server Dev
npm run dev
```

---

## ⚡ 3. VPS dengan aaPanel

### Prasyarat di aaPanel
- Nginx / Apache
- PHP 8.2 / 8.3 (Ekstensi: `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`, `curl`, `xml`)
- MySQL 8.0 / MariaDB 10.5+

### Langkah-Langkah Deployment:

1. **Buat Site & Database di aaPanel:**
   - **aaPanel > Website > Add Site**.
   - Domain: `marilms.maripartner.com`
   - Database: Buat MySQL Database baru (misal: `sql_marilms_maripartner_com`).
   - Root Path: `/www/wwwroot/marilms.maripartner.com`

2. **Clone & Setup via SSH Terminal aaPanel:**
   ```bash
   cd /www/wwwroot/marilms.maripartner.com
   git clone https://github.com/hndko/app_marilms_laravel12.git .

   # Install dependensi
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build

   # Environment config
   cp .env.example .env
   php artisan key:generate
   ```

3. **Edit `.env` di VPS:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://marilms.maripartner.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sql_marilms_maripartner_com
   DB_USERNAME=sql_marilms_maripartner_com
   DB_PASSWORD=password_database_aapanel
   ```

4. **Jalankan Migrasi Database & Seeding:**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   ```

5. **Set Permission Folder & Site Directory aaPanel (PENTING):**
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www:www /www/wwwroot/marilms.maripartner.com
   ```
   - Masuk ke **aaPanel > Website > Site Config > Site Directory**:
     - **Running Directory:** Ubah dari `/` menjadi `/public`.
   - Masuk ke tab **URL Rewrite**:
     - Pilih preset **Laravel** (atau paste `try_files $uri $uri/ /index.php?$query_string;`).

6. **Setup Worker & Cache:**
   - Setup Queue Worker di **Supervisor Manager aaPanel** (lihat bagian 1.A).
   - Recache aplikasi:
     ```bash
     php artisan config:cache
     php artisan route:cache
     php artisan view:cache
     ```

---

## 🖥️ 4. VPS Linux Murni (Ubuntu / Debian + Nginx)

### 1. Install Packages
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-cli \
php8.2-common php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip unzip git supervisor
```

### 2. Buat Database MySQL
```bash
sudo mysql -u root
```
```sql
CREATE DATABASE marilms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'marilms_user'@'localhost' IDENTIFIED BY 'PasswordKuat123!';
GRANT ALL PRIVILEGES ON marilms_db.* TO 'marilms_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Clone & Setup Application
```bash
cd /var/www
sudo git clone https://github.com/hndko/app_marilms_laravel12.git marilms
cd marilms
sudo composer install --no-dev --optimize-autoloader
sudo cp .env.example .env
sudo php artisan key:generate
```
Edit `.env` sesuaikan database, lalu jalankan:
```bash
sudo php artisan migrate --force
sudo php artisan db:seed --force
sudo php artisan storage:link
sudo chown -R www-data:www-data /var/www/marilms
sudo chmod -R 775 /var/www/marilms/storage /var/www/marilms/bootstrap/cache
```

### 4. Konfigurasi Nginx Server Block (`/etc/nginx/sites-available/marilms`)
```nginx
server {
    listen 80;
    server_name marilms.com www.marilms.com;
    root /var/www/marilms/public;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
```bash
sudo ln -s /etc/nginx/sites-available/marilms /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 🌐 5. Shared Hosting (cPanel / DirectAdmin)

1. **Build Aset Lokal:**
   - Di lokal jalankan `npm run build`.
   - Compress folder proyek menjadi `marilms.zip` (tanpa `node_modules` & `.git`).

2. **Upload ke cPanel File Manager:**
   - Upload & extract `marilms.zip` ke luar `public_html` (misal: `/home/username/marilms_app`).

3. **Pindahkan Folder `public`:**
   - Pindahkan seluruh isi dari `/home/username/marilms_app/public` ke `/home/username/public_html/`.

4. **Edit `/home/username/public_html/index.php`:**
   Ubah path ke vendor & bootstrap:
   ```php
   require __DIR__.'/../marilms_app/vendor/autoload.php';
   $app = require_once __DIR__.'/../marilms_app/bootstrap/app.php';
   ```

5. **Koneksi Database & Migration via cPanel:**
   - Buat MySQL DB & User di **cPanel > MySQL Databases**.
   - Edit `/home/username/marilms_app/.env` sesuaikan kredensial DB.
   - Buka cPanel Terminal / phpMyAdmin untuk mengimpor atau jalankan:
     ```bash
     cd /home/username/marilms_app
     php artisan migrate --force
     php artisan db:seed --force
     ```

6. **Setup Cron Job Queue Worker di cPanel:**
   Tambahkan Cron Job di cPanel setiap 1 menit (lihat bagian 1.D).

---

## 🛠️ 6. Perintah Pemeliharaan & Optimasi Produksi

| Kebutuhan | Perintah |
| :--- | :--- |
| **Clear & Recache Config** | `php artisan config:clear && php artisan config:cache` |
| **Clear & Recache Routes** | `php artisan route:clear && php artisan route:cache` |
| **Clear & Recache Views** | `php artisan view:clear && php artisan view:cache` |
| **Restart Queue Worker** | `php artisan queue:restart` *(Wajib setelah update kode!)* |
| **Reset Total Database** | `php artisan migrate:fresh --seed` |
| **Cek Log Errors** | `tail -f storage/logs/laravel.log` |
