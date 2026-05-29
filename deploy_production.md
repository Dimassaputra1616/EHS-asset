# 🚀 Panduan Deploy Aplikasi EHS-Asset ke Ubuntu Server (Production)

Panduan ini membahas langkah-demi-langkah instalasi, konfigurasi, pengamanan, dan optimasi aplikasi **HSE Asset Management (Laravel)** pada server **Ubuntu (22.04 LTS / 24.04 LTS)** dari nol hingga siap digunakan untuk production dengan performa tinggi dan aman.

---

## 📌 Prasyarat Sistem & Topologi
* **Server:** VPS / Dedicated Server (Ubuntu 22.04 / 24.04 LTS).
* **Domain:** Domain aktif yang sudah diarahkan A Record-nya ke IP Publik server (misal: `safety.perusahaan.com`).
* **Akses:** Akun dengan hak akses `sudo` (non-root direkomendasikan untuk keamanan).

---

## 🛠️ Langkah 1: Update Server & Setup Firewall (UFW)

Sebelum menginstal paket apa pun, perbarui repositori sistem dan aktifkan firewall internal untuk membatasi port yang terbuka.

```bash
# Update paket Ubuntu
sudo apt update && sudo apt upgrade -y

# Izinkan akses SSH, HTTP, dan HTTPS melalui UFW
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'

# Aktifkan Firewall
sudo ufw enable
```

---

## 🐘 Langkah 2: Instalasi PHP 8.3 & Ekstensi yang Dibutuhkan

Aplikasi Laravel membutuhkan PHP beserta beberapa ekstensi spesifik untuk pengolahan database, kompresi zip, manipulasi gambar, dan kalkulasi presisi tinggi.

```bash
# Tambahkan PPA PHP Ondřej Surý (selalu mendapatkan PHP versi terbaru dan stabil)
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Instal PHP 8.3, PHP-FPM, dan modul pendukung
sudo apt install -y php8.3 php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml \
php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl php8.3-cli unzip git

# Pastikan PHP-FPM berjalan aktif
sudo systemctl status php8.3-fpm
```

---

## 🗄️ Langkah 3: Instalasi & Konfigurasi MySQL Database Server

```bash
# Instal MySQL Server
sudo apt install mysql-server -y

# Jalankan skrip keamanan bawaan MySQL
sudo mysql_secure_installation
```
*(Ikuti petunjuk di layar: Aktifkan validasi password jika perlu, hapus anonymous user, matikan remote login root, dan hapus test database).*

### Membuat Database dan User untuk HSE Asset:
Masuk ke terminal MySQL:
```bash
sudo mysql -u root -p
```
Jalankan query SQL berikut di dalam shell MySQL untuk membuat database terpisah:
```sql
CREATE DATABASE hse_asset CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'hse_user'@'localhost' IDENTIFIED BY 'PasswordSangatKuat123!';
GRANT ALL PRIVILEGES ON hse_asset.* TO 'hse_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 💻 Langkah 4: Instalasi Composer & Node.js (Vite Compiler)

```bash
# Instal Composer secara global
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Instal Node.js (Versi 20 LTS direkomendasikan) melalui NodeSource
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

---

## 📂 Langkah 5: Cloning Proyek & Setup File Aplikasi

Kita akan menempatkan kode aplikasi di direktori standar web server: `/var/www/html/hse-asset`.

```bash
# Buat direktori dan atur hak milik sementara ke user login Anda
sudo mkdir -p /var/www/html/hse-asset
sudo chown -R $USER:$USER /var/www/html/hse-asset

# Clone repositori git Anda ke direktori tersebut
git clone https://github.com/USERNAME/REPO-NAME.git /var/www/html/hse-asset
# atau salin source code lokal Anda jika dideploy manual.

cd /var/www/html/hse-asset
```

### Konfigurasi Environment File (`.env`):
Salin berkas contoh `.env` dan sesuaikan nilainya:
```bash
cp .env.example .env
nano .env
```
Sesuaikan konfigurasi penting berikut untuk **Production**:
```ini
APP_NAME="HSE Asset Management"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://safety.perusahaan.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hse_asset
DB_USERNAME=hse_user
DB_PASSWORD=PasswordSangatKuat123!

# Gunakan driver session & cache yang direkomendasikan
BROADCAST_CONNECTION=log
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

---

## 📦 Langkah 6: Install Dependensi & Build Assets (Production Mode)

```bash
# Instal dependensi PHP (Tanpa dev tools untuk optimasi performa)
composer install --no-dev --optimize-autoloader

# Generate Application Key baru yang aman
php artisan key:generate

# Jalankan migrasi database secara aman
php artisan migrate --force

# Instal dependensi Javascript & Compile Assets menggunakan Vite
npm install
npm run build
```

---

## 🔐 Langkah 7: Pengaturan Hak Akses File (Permissions) & Storage Link

Aplikasi web server berjalan di bawah user `www-data`. Kita harus memberikan izin akses ke folder `storage` dan `bootstrap/cache` agar aplikasi dapat menulis log, session, file unggahan, dan cache.

```bash
# Buat link symlink storage ke public agar foto asset dan profil bisa diakses browser
php artisan storage:link

# Ubah kepemilikan direktori ke www-data (Nginx Web Server)
sudo chown -R www-data:www-data /var/www/html/hse-asset

# Atur hak akses folder agar aman
sudo find /var/www/html/hse-asset -type f -exec chmod 644 {} \;
sudo find /var/www/html/hse-asset -type d -exec chmod 755 {} \;

# Berikan izin khusus tulis untuk storage dan cache
sudo chmod -R 775 /var/www/html/hse-asset/storage
sudo chmod -R 775 /var/www/html/hse-asset/bootstrap/cache
```

---

## 🌐 Langkah 8: Konfigurasi Nginx Server Block

Nginx bertindak sebagai web server terdepan (*reverse proxy*) yang melayani aset statis dan mengalirkan proses PHP ke PHP-FPM.

```bash
# Buat berkas konfigurasi baru untuk Nginx
sudo nano /etc/nginx/sites-available/hse-asset
```

Masukkan konfigurasi premium berikut (sesuaikan server name dengan domain/IP Anda):

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name safety.perusahaan.com;
    root /var/www/html/hse-asset/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Optimasi Cache untuk static assets (performa kencang!)
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|webp|svg|woff|woff2|ttf)$ {
        expires 365d;
        add_header Cache-Control "public, no-transform";
        access_log off;
    }
}
```

### Aktifkan Konfigurasi Nginx:
```bash
# Buat symlink untuk mengaktifkan situs
sudo ln -s /etc/nginx/sites-available/hse-asset /etc/nginx/sites-enabled/

# Hapus konfigurasi default Nginx (jika belum pernah dihapus)
sudo rm -f /etc/nginx/sites-enabled/default

# Uji sintaks konfigurasi Nginx
sudo nginx -t

# Restart Nginx untuk menerapkan perubahan
sudo systemctl restart nginx
```

---

## 🔒 Langkah 9: Pasang SSL HTTPS Gratis (Let's Encrypt)

Menggunakan HTTPS adalah kewajiban mutlak untuk aplikasi produksi agar semua data pengajuan asset dan login terenkripsi secara aman.

```bash
# Instal Certbot Nginx Plugin
sudo apt install certbot python3-certbot-nginx -y

# Request sertifikat SSL gratis untuk domain Anda
sudo certbot --nginx -d safety.perusahaan.com
```
*(Certbot akan meminta email Anda dan menanyakan apakah Anda ingin mengarahkan semua lalu lintas HTTP ke HTTPS secara otomatis. **Pilih Opsi 2 (Redirect)**).*

---

## ⚡ Langkah 10: Optimasi Performa Produksi Laravel

Laravel menyediakan perintah khusus untuk mengompilasi rute, konfigurasi, dan view menjadi file PHP statis yang sangat cepat dieksekusi.

```bash
cd /var/www/html/hse-asset

# Bersihkan cache lama
php artisan cache:clear

# Buat cache konfigurasi, rute, view, dan event untuk performa maksimal
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

> [!WARNING]
> Setelah Anda menjalankan `php artisan config:cache`, perubahan langsung pada file `.env` tidak akan terbaca sampai Anda membersihkannya kembali (`php artisan config:clear`).

---

## ⏰ Langkah 11: Setup Task Scheduler (Cron Job)

Laravel memiliki scheduler bawaan yang harus dieksekusi setiap satu menit untuk memeriksa tugas otomatis (seperti notifikasi jatuh tempo pengembalian APD).

```bash
# Buka cron editor untuk user www-data
sudo crontab -u www-data -e
```
Tambahkan baris berikut di baris paling bawah berkas:
```text
* * * * * cd /var/www/html/hse-asset && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🛡️ Langkah 12: Setup Queue Worker (Supervisor)

Jika sistem EHS menggunakan antrean tugas (*queues*) di belakang layar (seperti pengiriman email notifikasi APD atau proses ekspor laporan besar), Anda harus memasang **Supervisor** agar worker tetap berjalan terus-menerus.

```bash
# Instal Supervisor
sudo apt install supervisor -y

# Buat berkas konfigurasi worker Laravel
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

Tambahkan konfigurasi berikut:
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/hse-asset/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/html/hse-asset/storage/logs/worker.log
stopwaitsecs=3600
```

### Jalankan Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

---

## 🔄 Langkah 13: Skrip Deploy Otomatis (Optional tapi Sangat Membantu!)

Buat skrip deployment otomatis agar setiap kali Anda melakukan pembaruan di server lokal, Anda bisa memperbarui server produksi hanya dengan satu baris perintah.

```bash
# Buat file deploy.sh
nano /var/www/html/hse-asset/deploy.sh
```

Masukkan konten skrip berikut:
```bash
#!/bin/bash
set -e

echo "⚠️  Memulai Proses Deployment Produksi..."

# Masuk ke mode maintenance agar user tidak error saat update database
php artisan down --message="Aplikasi sedang dalam pembaruan rutin. Silakan coba beberapa saat lagi."

# Tarik perubahan terbaru dari Git
git pull origin main

# Instal dependensi PHP terbaru
composer install --no-dev --optimize-autoloader

# Jalankan migrasi database secara aman
php artisan migrate --force

# Bersihkan dan kompilasi ulang cache Laravel
php artisan config:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Compile assets frontend (Vite)
npm install
npm run build

# Keluar dari mode maintenance
php artisan up

echo "✅ Deployment Sukses & Server Kembali Online!"
```

Atur agar skrip bisa dijalankan (*executable*):
```bash
chmod +x /var/www/html/hse-asset/deploy.sh
```

Untuk melakukan deploy di kemudian hari, Anda cukup menjalankan:
```bash
./deploy.sh
```

---

## 🎉 Selesai!
Aplikasi **HSE Asset Management** Anda sekarang telah berjalan dengan **SSL HTTPS aktif**, **Nginx teroptimasi**, **scheduler otomatis**, dan **database terisolasi** secara aman di server Ubuntu Anda! 🚀
