# Portfolio — Muh. Fery Fatchan Rizqi

Website portfolio berbasis **Laravel 13** dengan **admin panel Filament 5**.

- `/` — halaman portfolio **publik** (bisa dilihat siapa saja, tanpa login).
- `/admin` — admin panel untuk mengelola semua konten. **Hanya** user dengan `is_admin = 1` yang bisa masuk; user lain mendapat 403.

Konten yang dapat dikelola: Profil & Kontak, Statistik, Skills, Pengalaman Kerja, Organisasi, Pendidikan, Sertifikasi. Tiap item bisa diurutkan (drag & drop di tabel) dan disembunyikan tanpa dihapus (toggle **Tampil**).

Desain halaman publik diambil dari versi statis lama yang disimpan di `legacy/index.html`.

## Kebutuhan server

- PHP 8.3+ dengan ekstensi: `intl`, `mbstring`, `pdo_mysql`, `zip`, `xml`, `curl`, `fileinfo`
- Composer 2
- MySQL 8 / MariaDB 10.6+
- Web server (Nginx/Apache) dengan **document root ke folder `public/`**

> GitHub Pages **tidak** bisa menjalankan aplikasi ini (hanya file statis). Gunakan VPS atau shared hosting yang mendukung PHP.

## Instalasi lokal (development)

```bash
composer install
cp .env.example .env
php artisan key:generate
# isi DB_* di .env (atau pakai SQLite: DB_CONNECTION=sqlite)
php artisan migrate --seed
php artisan app:create-admin
php artisan serve
```

Buka http://localhost:8000 (portfolio) dan http://localhost:8000/admin (admin).

## Deploy ke produksi

1. **Backup dulu** jika sudah ada data: `mysqldump -u <USER> -p <DB> > backup-$(date +%F).sql`
2. Ambil kode dan pasang dependency:
   ```bash
   git pull
   composer install --no-dev --optimize-autoloader
   ```
3. Instalasi pertama saja: `cp .env.example .env`, lalu set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://<DOMAIN_ANDA>`, dan `DB_*`. Kemudian `php artisan key:generate`.
4. Database:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force          # instalasi pertama: isi konten awal (dilewati otomatis jika sudah ada)
   php artisan app:create-admin         # buat akun admin (password min. 12 karakter)
   ```
5. Aset & cache:
   ```bash
   php artisan filament:assets
   php artisan storage:link
   php artisan optimize
   php artisan filament:optimize
   ```
6. Pastikan `storage/` dan `bootstrap/cache/` bisa ditulis oleh user web server.
7. Verifikasi: buka `/` (portfolio tampil), `/admin` (redirect ke login), login admin, ubah satu item, cek perubahan di `/`.

### Rollback

- Kode: `git checkout <COMMIT_SEBELUMNYA>` lalu ulangi langkah 2 & 5.
- Data: restore dari backup `mysql -u <USER> -p <DB> < backup-YYYY-MM-DD.sql`. Rollback kode tidak otomatis membatalkan perubahan data/skema.

## Manajemen admin

- Buat admin baru: `php artisan app:create-admin`
- Jadikan user yang sudah ada sebagai admin: `php artisan app:create-admin --email=<EMAIL>`
- `is_admin` sengaja tidak bisa diisi lewat form/mass assignment.

## Test

```bash
php artisan test
```
