#!/usr/bin/env bash
# ============================================================================
# deploy.sh — update rilis Capital Wave (jalankan di server)
#   cd /var/www/capitalwave && bash deploy/deploy.sh
# Untuk deploy PERTAMA kali, ikuti DEPLOY.md dulu (ini untuk update berikutnya).
# ============================================================================
set -euo pipefail

APP_DIR="/var/www/capitalwave"
BRANCH="${1:-main}"     # pakai: bash deploy/deploy.sh <branch>  (default: main)

cd "$APP_DIR"

echo ">> [1/7] Tarik kode terbaru ($BRANCH)"
git fetch --all
git reset --hard "origin/$BRANCH"

echo ">> [2/7] Composer (produksi)"
composer install --no-dev --optimize-autoloader --no-interaction

# echo ">> (opsional) Build aset Vite"
# npm ci && npm run build

echo ">> [3/7] Migrasi database (aman kalau tidak ada yang baru)"
php artisan migrate --force

echo ">> [4/7] Symlink storage"
php artisan storage:link || true

echo ">> [5/7] Cache config/route/view"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ">> [6/7] Restart worker antrian (kalau ada)"
php artisan queue:restart || true

echo ">> [7/7] Set permission storage & cache"
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rw storage bootstrap/cache

echo ">> Selesai. Reload PHP-FPM:"
echo "   sudo systemctl reload php8.3-fpm"
