#!/usr/bin/env bash
# Dijalankan DI VPS dari folder aplikasi, setelah kode terbaru di-pull.
# Dipanggil otomatis oleh GitHub Actions (.github/workflows/deploy.yml),
# atau manual: cd /var/www/portfolio && git pull --ff-only && bash deploy/deploy.sh
set -euo pipefail

cd "$(dirname "$0")/.."

echo "==> Maintenance mode"
php artisan down --retry=15 || true
# Pastikan situs kembali online walaupun ada langkah yang gagal.
trap 'php artisan up' EXIT

echo "==> Composer install"
composer install --no-dev --optimize-autoloader --no-interaction --no-progress

echo "==> Migrasi database"
php artisan migrate --force

echo "==> Aset & cache"
php artisan filament:assets
php artisan optimize:clear
php artisan optimize
php artisan filament:optimize

echo "==> Deploy selesai: $(git rev-parse --short HEAD)"
