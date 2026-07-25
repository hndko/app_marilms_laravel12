# 📘 Panduan Deployment MariLMS (Laravel 12 - Single-Database Tenancy)

Dokumen ini berisi panduan lengkap langkah demi langkah untuk melakukan deploy aplikasi **MariLMS v1.4.0** pada 4 jenis lingkungan (*environment*):
1. **Local Development** (Laragon / XAMPP / Native)
2. **VPS dengan aaPanel**
3. **VPS Murni** (Ubuntu / Debian + Nginx / Apache)
4. **Shared Hosting** (cPanel / DirectAdmin)

---

## 💻 1. Local Development (Laragon / XAMPP)

### Prasyarat
- PHP `>= 8.2` (Ekstensi wajib: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `json`, `tokenizer`, `xml`, `bcmath`)
- Composer `>= 2.x`
- Node.js `>= 18.x` & NPM
- MySQL / MariaDB

### Langkah Deployment
1. **Clone Repositori & Masuk Folder Proyek:**
   ```bash
   git clone https://github.com/hndko/app_marilms_laravel12.git
   cd app_marilms_laravel12
   ```

2. **Install Dependensi Composer & NPM:**
   ```bash
   composer install
   npm install
   ```

3. **Salin & Konfigurasi Berkas `.env`:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Atur koneksi database pada `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=app_marilms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Jalankan Migrasi & Seeding:**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Jalankan Aplikasi:**
   ```bash
   # Opsi A: Menggunakan Concurrently (Server + Vite sekaligus)
   npm run dev

   # Opsi B: Menggunakan Laragon (Virtual Host)
   # Akses via browser: http://app_marilms_laravel12.test
   ```

---

## ⚡ 2. VPS dengan aaPanel

aaPanel adalah salah satu Control Panel VPS paling populer untuk pengelolaan Nginx/Apache & MySQL.

### Prasyarat di aaPanel
- Nginx / Apache terinstall
- PHP 8.2 / 8.3 dengan ekstensi: `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`, `curl`, `xml`
- MySQL 8.0 / MariaDB 10.5+
- Node.js via NVM (di App Store aaPanel)

### Langkah Deployment

1. **Buat Website & Database di aaPanel:**
   - Buka **aaPanel > Website > Add Site**.
   - **Domain:** `marilms.maripartner.com` (atau domain Anda).
   - **Database:** Buat MySQL Database baru (misal: `sql_marilms`). Catat username & password.
   - **Document Root:** `/www/wwwroot/marilms.maripartner.com`

2. **Clone Proyek ke Document Root:**
   Buka **Terminal aaPanel** atau SSH ke VPS:
   ```bash
   cd /www/wwwroot/marilms.maripartner.com
   git clone https://github.com/hndko/app_marilms_laravel12.git .
   ```

3. **Install Dependensi:**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   ```

4. **Konfigurasi Berkas `.env`:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Sesuaikan `.env`:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://marilms.maripartner.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sql_marilms
   DB_USERNAME=sql_marilms
   DB_PASSWORD=password_db_anda
   ```

5. **Jalankan Migrasi Database & Seeding:**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

6. **Atur Permission Folder & Storage Link:**
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www:www /www/wwwroot/marilms.maripartner.com
   php artisan storage:link
   ```

7. **Konfigurasi Nginx Site di aaPanel (PENTING):**
   Buka **aaPanel > Website > Conf > Site Directory**:
   - **Running Directory:** Pilih `/public` (BUKAN root direktori).
   - Simpan (*Save*).

   Buka tab **URL Rewrite**:
   - Pilih preset **Laravel**, atau tempel konfigurasi berikut:
   ```nginx
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   ```

8. **Optimasi Cache Laravel:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## 🖥️ 3. VPS Murni (Ubuntu / Debian + Nginx)

Untuk deployment tanpa Control Panel (CLI murni).

### 1. Install Dependency Sistem
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-cli \
php8.2-common php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl \
php8.2-zip unzip git curl
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

### 3. Clone & Setup Proyek
```bash
cd /var/www
sudo git clone https://github.com/hndko/app_marilms_laravel12.git marilms
cd marilms
sudo composer install --no-dev --optimize-autoloader
sudo cp .env.example .env
sudo php artisan key:generate
```

Edit `.env` dengan kredensial DB yang dibuat di atas. Kemudian jalankan:
```bash
sudo php artisan migrate --force
sudo php artisan db:seed --force
sudo php artisan storage:link
sudo chown -R www-data:www-data /var/www/marilms
sudo chmod -R 775 /var/www/marilms/storage /var/www/marilms/bootstrap/cache
```

### 4. Konfigurasi Nginx Server Block
Buat berkas konfig: `/etc/nginx/sites-available/marilms`:
```nginx
server {
    listen 80;
    server_name marilms.com www.marilms.com;
    root /var/www/marilms/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

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
Aktifkan site & reload Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/marilms /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 🌐 4. Shared Hosting (cPanel / DirectAdmin)

Shared hosting umum tidak memiliki akses root dan biasanya hanya mendukung Apache dengan `public_html`.

### Langkah-Langkah Deployment:

1. **Persiapan Berkas Lokal:**
   - Di komputer lokal, jalankan `npm run build` terlebih dahulu untuk menghasilkan aset frontend terkompilasi di `public/build`.
   - Compress folder proyek menjadi berkas `marilms.zip` (kecuali folder `node_modules` dan `.git`).

2. **Upload & Extract di cPanel File Manager:**
   - Upload `marilms.zip` ke direktori luar `public_html` (misal: `/home/username/marilms_app`).
   - Extract berkas zip tersebut di sana.

3. **Pindahkan Folder `public` ke `public_html`:**
   - Masuk ke `/home/username/marilms_app/public`.
   - Pindahkan seluruh isi folder `public` ke `/home/username/public_html/`.

4. **Edit `public_html/index.php`:**
   Ubah path `vendor/autoload.php` dan `bootstrap/app.php` mengarah ke folder app di luar `public_html`:
   ```php
   // Ubah dari:
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';

   // Menjadi:
   require __DIR__.'/../marilms_app/vendor/autoload.php';
   $app = require_once __DIR__.'/../marilms_app/bootstrap/app.php';
   ```

5. **Buat Database MySQL di cPanel:**
   - Buka **cPanel > MySQL Databases**.
   - Buat database & user baru, lalu **Grant All Privileges**.
   - Buka **phpMyAdmin**, import file dump SQL atau jalankan migrasi via SSH cPanel Terminal:
     ```bash
     cd /home/username/marilms_app
     php artisan migrate --force
     php artisan db:seed --force
     ```

6. **Konfigurasi `.env` di Shared Hosting:**
   Edit `/home/username/marilms_app/.env`:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domainanda.com

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_DATABASE=username_marilms
   DB_USERNAME=username_marilmsuser
   DB_PASSWORD=password_db
   ```

7. **Pastikan ModRewrite `.htaccess` di `public_html` Aktif:**
   Pastikan file `.htaccess` ada di `public_html`:
   ```apache
   <IfModule mod_rewrite.c>
       <IfModule mod_negotiation.c>
           Options -MultiViews -Indexes
       </IfModule>

       RewriteEngine On

       # Handle Authorization Header
       RewriteCond %{HTTP:Authorization} .
       RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

       # Redirect Trailing Slashes...
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteCond %{REQUEST_URI} (.+)/$
       RewriteRule ^ %1 [L,R=301]

       # Send Requests To Front Controller...
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteRule ^ index.php [L]
   </IfModule>
   ```

---

## 🛠️ Ringkasan Command Pemeliharaan & Troubleshooting

| Kebutuhan | Perintah |
| :--- | :--- |
| **Clear & Recache All Config** | `php artisan config:clear && php artisan config:cache` |
| **Clear & Recache Routes** | `php artisan route:clear && php artisan route:cache` |
| **Clear & Recache Views** | `php artisan view:clear && php artisan view:cache` |
| **Reset Database (Hati-hati!)** | `php artisan migrate:fresh --seed` |
| **Cek Log System** | `tail -f storage/logs/laravel.log` |
