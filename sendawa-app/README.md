# SENDAWA — Platform Review Anonim

> Tugas Kuliah: Komputasi Awan (IaaS & Virtualisasi)
> Stack: **Laravel 11** · **Livewire 4** · **Tailwind CSS**

Platform review anonim yang memungkinkan siapa saja mengirimkan review (teks + foto) tanpa perlu mendaftar atau login.

---

## ✨ Fitur

| Fitur | Detail |
|---|---|
| **100% Anonim** | Tidak ada sistem login/auth |
| **Real-time Search** | Cari review berdasarkan judul tanpa reload |
| **Upload Foto** | Gambar disimpan di `storage/app/public/images` |
| **Pagination** | 9 review per halaman |
| **Hapus Review** | Setiap review bisa dihapus beserta fotonya |
| **Dark Theme** | UI gelap modern dengan Tailwind CSS |

---

## 🚀 Menjalankan Lokal (Development)

### Prasyarat
- PHP >= 8.2 dengan ekstensi `pdo_mysql`
- Composer
- Node.js >= 18 & npm
- MySQL Server (XAMPP / Laragon / native)

### Langkah-langkah

```bash
# 1. Clone repo
git clone https://github.com/AceNatty05/SENDAWA.git
cd SENDAWA/sendawa-app

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Salin konfigurasi environment
cp .env.example .env

# 5. Generate APP_KEY
php artisan key:generate

# 6. Buat database MySQL bernama 'sendawa', lalu atur .env:
#    DB_DATABASE=sendawa
#    DB_USERNAME=root
#    DB_PASSWORD=

# 7. Jalankan migration
php artisan migrate

# 8. Buat symlink storage (agar gambar bisa diakses publik)
php artisan storage:link

# 9. Jalankan server (buka dua terminal)
npm run dev          # Terminal 1 — Vite (Tailwind + HMR)
php artisan serve    # Terminal 2 — Laravel dev server

# 10. Buka di browser
# http://localhost:8000
```

---

## ☁️ Deployment ke Oracle Cloud (Ubuntu VM)

```bash
# ---- Di server Oracle Cloud ----

# 1. Clone repo
git clone https://github.com/AceNatty05/SENDAWA.git /var/www/sendawa
cd /var/www/sendawa/sendawa-app

# 2. Install PHP dependencies (tanpa dev packages)
composer install --optimize-autoloader --no-dev

# 3. Build aset frontend
npm install
npm run build

# 4. Salin dan isi .env
cp .env.example .env
nano .env   # ← isi APP_URL, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 5. Generate key
php artisan key:generate

# 6. Jalankan migration
php artisan migrate --force

# 7. Buat symlink storage
php artisan storage:link

# 8. Set permission folder
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 9. Optimasi untuk produksi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 10. Konfigurasi Nginx (contoh):
# server {
#     listen 80;
#     server_name YOUR_IP_OR_DOMAIN;
#     root /var/www/sendawa/sendawa-app/public;
#     index index.php;
#
#     location / {
#         try_files $uri $uri/ /index.php?$query_string;
#     }
#
#     location ~ \.php$ {
#         fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
#         fastcgi_index index.php;
#         fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
#         include fastcgi_params;
#     }
# }
```

---

## 🗃️ Struktur Database

```sql
CREATE TABLE reviews (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title           VARCHAR(255) NOT NULL,
    review_content  TEXT NOT NULL,
    image_path      VARCHAR(255) NULL,
    created_at      TIMESTAMP NULL,
    updated_at      TIMESTAMP NULL
);
```

---

## 📁 Struktur File Penting

```
sendawa-app/
├── app/
│   ├── Livewire/
│   │   └── ReviewManager.php        ← Komponen Livewire utama
│   └── Models/
│       └── Review.php               ← Model Eloquent
├── database/
│   └── migrations/
│       └── ..._create_reviews_table.php
├── resources/
│   ├── css/app.css                  ← Tailwind CSS
│   └── views/
│       ├── layouts/app.blade.php    ← Layout utama
│       ├── livewire/
│       │   └── review-manager.blade.php
│       └── vendor/livewire/
│           └── tailwind.blade.php   ← Custom pagination
├── routes/web.php
└── .env.example
```

---

## 📝 Lisensi

MIT — Bebas digunakan untuk keperluan akademik.
