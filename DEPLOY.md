# Deploy Capital Wave ke VPS (Ubuntu 24.04 + Nginx)

Panduan deploy Laravel 12 lewat **Git** ke server kosong (baru ada Nginx).
Domain: **capitalwavee.com** · IP: **202.155.13.29** · DB: import `deposit.sql`.

> Jalankan semua perintah sebagai user dengan sudo. Ganti nilai `CHANGE_ME`.

---

## 0. Push kode ke Git dulu (dari lokal / Windows)
Server akan `git clone` dari remote (GitHub/GitLab). Dari mesin lokal:

```bash
git add .
git commit -m "Prepare deploy"
git push origin main          # atau branch yang kamu pakai
```
> Kalau masih di branch `feat/bayarpro-payment-gateway`, merge dulu ke `main`
> atau nanti clone pakai branch itu. **Pastikan `.env` TIDAK ikut ter-commit.**

---

## 1. Install paket di server
```bash
sudo apt update && sudo apt upgrade -y

# PHP 8.3 + ekstensi Laravel
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring \
  php8.3-xml php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl \
  unzip git curl

# MySQL
sudo apt install -y mysql-server

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# (opsional, untuk build aset Vite) Node.js 20
# curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
# sudo apt install -y nodejs
```

---

## 2. Buat database & import `deposit.sql`
```bash
sudo mysql
```
Di prompt MySQL:
```sql
CREATE DATABASE capitalwave CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'capitalwave'@'127.0.0.1' IDENTIFIED BY 'CHANGE_ME_STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON capitalwave.* TO 'capitalwave'@'127.0.0.1';
FLUSH PRIVILEGES;
EXIT;
```
Upload `deposit.sql` ke server (dari lokal), lalu import:
```bash
# dari lokal:  scp "C:\Users\User\Downloads\DownloadBebas\deposit.sql" root@202.155.13.29:/tmp/deposit.sql
mysql -u capitalwave -p capitalwave < /tmp/deposit.sql
```
> Dump ini tidak berisi `CREATE DATABASE`, jadi kita buat DB `capitalwave` sendiri
> lalu import (sudah termasuk tabel `migrations`, `sessions`, `cache`, produk, vip, dll).

---

## 3. Clone project
```bash
sudo mkdir -p /var/www/capitalwave
sudo chown -R $USER:$USER /var/www/capitalwave
git clone <URL_REPO_GIT> /var/www/capitalwave
cd /var/www/capitalwave
git checkout main            # atau branch yang dipakai
composer install --no-dev --optimize-autoloader
# npm ci && npm run build    # opsional (aset Vite)
```

---

## 4. Konfigurasi `.env`
```bash
cp deploy/.env.production.example .env
nano .env                    # isi DB_PASSWORD, BANKPAY_*, dll
php artisan key:generate
```
Poin penting `.env`:
- `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://capitalwavee.com`
- `DB_*` sesuai step 2
- `BANKPAY_MEMBER_ID` / `BANKPAY_KEY` (dari dashboard BankPay)
- Notifikasi server BankPay:
  - deposit  → `https://capitalwavee.com/api/bankpay/deposit-notify`
  - withdraw → `https://capitalwavee.com/api/bankpay/payout-notify`
  Keduanya dikirim otomatis sebagai `pay_notifyurl`/`notifyurl` di tiap request,
  jadi cukup pastikan **URL-nya bisa diakses publik** (tidak diblokir WAF).
- Saluran deposit: `DEPOSIT_CHANNEL_BANKPAY`, `DEPOSIT_CHANNEL_QRIS_STATIS`,
  `DEPOSIT_DEFAULT_CHANNEL`. Saluran QRIS statis butuh `QRIS_STATIS` +
  `LISTENER_TOKEN`; withdraw selalu lewat BankPay.

---

## 5. Permission, storage link, cache
```bash
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

sudo chown -R www-data:www-data /var/www/capitalwave/storage /var/www/capitalwave/bootstrap/cache
sudo find /var/www/capitalwave -type d -exec chmod 755 {} \;
sudo find /var/www/capitalwave -type f -exec chmod 644 {} \;
sudo chmod -R ug+rw /var/www/capitalwave/storage /var/www/capitalwave/bootstrap/cache
```

---

## 6. Nginx
```bash
sudo cp deploy/nginx-capitalwavee.conf /etc/nginx/sites-available/capitalwavee.com
sudo ln -s /etc/nginx/sites-available/capitalwavee.com /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default     # matikan default kalau perlu
sudo nginx -t && sudo systemctl reload nginx
```
> Cek versi socket PHP-FPM di file conf (`php8.3-fpm.sock`). Cek dengan:
> `ls /run/php/`

Arahkan DNS domain **capitalwavee.com** (A record) ke **202.155.13.29** dulu.

---

## 7. SSL (HTTPS) — Certbot
```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d capitalwavee.com -d www.capitalwavee.com
```
Certbot otomatis menambah blok `listen 443` + auto-renew.

---

## 8. Cron scheduler (WAJIB — profit VIP settle tiap menit)
```bash
sudo crontab -e -u www-data
```
Tambahkan baris:
```
* * * * * cd /var/www/capitalwave && php artisan schedule:run >> /dev/null 2>&1
```
> Ini menjalankan `investments:settle-vip-profits` (dan schedule lain) tiap menit.

---

## 9. (Opsional) Queue worker
`.env` default `QUEUE_CONNECTION=sync` → tidak perlu worker.
Kalau nanti pindah ke `database`, pasang worker via systemd/supervisor:
```
php artisan queue:work --sleep=3 --tries=3
```

---

## 10. Update rilis berikutnya
Cukup jalankan skrip:
```bash
cd /var/www/capitalwave
bash deploy/deploy.sh main        # ganti 'main' dgn branch kamu
sudo systemctl reload php8.3-fpm
```

---

## Checklist cepat
- [ ] DNS capitalwavee.com → 202.155.13.29
- [ ] PHP 8.3-fpm + ekstensi, Composer, MySQL, Nginx terpasang
- [ ] DB `capitalwave` dibuat + `deposit.sql` diimport
- [ ] `.env` diisi (APP_KEY, DB, BankPay) + `APP_DEBUG=false`
- [ ] storage:link + cache + permission www-data
- [ ] Nginx aktif + `nginx -t` OK
- [ ] SSL certbot terpasang
- [ ] Cron `schedule:run` tiap menit aktif
- [ ] Notify BankPay `/api/bankpay/deposit-notify` & `/api/bankpay/payout-notify` bisa diakses publik
- [ ] `php artisan migrate` dijalankan (kolom `deposits.payment_channel`)
- [ ] Ganti `public/logo.png` dengan logo Capital Wave
- [ ] Test: buka https://capitalwavee.com/login → login user dari DB
