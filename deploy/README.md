# Deploy Otomatis: Push dari VS Code → VPS

Alurnya sama seperti MyStar:

```
VS Code: commit + push ke main
        │
        ▼
GitHub Actions (.github/workflows/deploy.yml)
  1. Tests   → php artisan test (kalau gagal, deploy DIBATALKAN)
  2. Deploy  → SSH ke VPS → git pull → bash deploy/deploy.sh
        │
        ▼
VPS: composer install, migrate, cache → situs ter-update
```

Pull request hanya menjalankan test, **tidak** deploy. Deploy hanya terjadi saat ada push/merge ke `main`, atau saat dijalankan manual dari tab **Actions → Test & Deploy → Run workflow**.

---

## A. Setup VPS (sekali saja)

Contoh untuk Ubuntu 22.04/24.04. Jalankan sebagai root (atau dengan `sudo`). Ganti placeholder `<...>`.

### 1. Paket yang dibutuhkan

```bash
apt update
```

```bash
apt install -y nginx git unzip mariadb-server php8.3-fpm php8.3-cli php8.3-mysql php8.3-intl php8.3-mbstring php8.3-xml php8.3-zip php8.3-curl php8.3-bcmath
```

Composer: ikuti https://getcomposer.org/download/ lalu pindahkan ke `/usr/local/bin/composer`.

> Kalau server MyStar sudah punya Nginx, PHP, dan MySQL, langkah ini cukup dicek saja (`php -v`, `php -m | grep -E 'intl|pdo_mysql'`). Jangan install ulang di server produksi tanpa backup.

### 2. Database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'portfolio'@'localhost' IDENTIFIED BY '<PASSWORD_KUAT>';
GRANT ALL PRIVILEGES ON portfolio.* TO 'portfolio'@'localhost';
FLUSH PRIVILEGES;
```

### 3. User khusus deploy

Jangan pakai root untuk deploy otomatis.

```bash
adduser --disabled-password --gecos "" deploy
usermod -aG www-data deploy
mkdir -p /var/www/portfolio && chown deploy:www-data /var/www/portfolio
```

### 4. Clone aplikasi (sebagai user `deploy`)

```bash
su - deploy
git clone https://github.com/FeryFatchanR/portoOnline.git /var/www/portfolio
cd /var/www/portfolio
git checkout main
```

> Kalau repo dibuat **private**, buat deploy key: `ssh-keygen -t ed25519 -f ~/.ssh/github_deploy -N ""`, tambahkan isi `~/.ssh/github_deploy.pub` di GitHub → repo → Settings → Deploy keys (read-only), lalu clone pakai `git@github.com:FeryFatchanR/portoOnline.git`.

### 5. Konfigurasi `.env` & instalasi pertama

```bash
cp .env.example .env
nano .env
```

Isi minimal: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://<DOMAIN_ANDA>`, `DB_DATABASE=portfolio`, `DB_USERNAME=portfolio`, `DB_PASSWORD=<PASSWORD_KUAT>`.

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan app:create-admin
bash deploy/deploy.sh
```

Permission folder yang ditulis aplikasi (kembali sebagai root):

```bash
chown -R deploy:www-data /var/www/portfolio/storage /var/www/portfolio/bootstrap/cache
chmod -R ug+rwX /var/www/portfolio/storage /var/www/portfolio/bootstrap/cache
```

### 6. Nginx + HTTPS

Salin `deploy/nginx.conf.example` ke `/etc/nginx/sites-available/portfolio`, sesuaikan domain dan versi PHP-FPM, lalu:

```bash
ln -s /etc/nginx/sites-available/portfolio /etc/nginx/sites-enabled/
nginx -t && systemctl reload nginx
```

HTTPS gratis dengan Let's Encrypt:

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d <DOMAIN_ANDA> -d www.<DOMAIN_ANDA>
```

> Arahkan DNS domain (record A) ke IP VPS sebelum menjalankan certbot.

### 7. SSH key untuk GitHub Actions

Di **komputer Anda** (bukan VPS), buat key khusus deploy:

```bash
ssh-keygen -t ed25519 -C "github-actions-portfolio" -f ~/.ssh/portfolio_deploy -N ""
```

Pasang public key-nya ke user `deploy` di VPS:

```bash
ssh-copy-id -i ~/.ssh/portfolio_deploy.pub deploy@<IP_VPS>
```

Tes: `ssh -i ~/.ssh/portfolio_deploy deploy@<IP_VPS> "cd /var/www/portfolio && git status"` harus berhasil tanpa password.

---

## B. Setup GitHub (sekali saja)

Buka GitHub → repo **portoOnline** → **Settings → Secrets and variables → Actions → New repository secret**, lalu tambahkan:

| Secret | Isi |
|---|---|
| `VPS_HOST` | IP atau domain VPS |
| `VPS_PORT` | Port SSH, biasanya `22` |
| `VPS_USER` | `deploy` |
| `VPS_SSH_KEY` | Isi file **private key** `~/.ssh/portfolio_deploy` (seluruhnya, termasuk baris BEGIN/END) |
| `DEPLOY_PATH` | `/var/www/portfolio` |

Opsional tapi disarankan: **Settings → Environments → New environment `production`**. Di sana Anda bisa mewajibkan persetujuan manual sebelum deploy, atau membatasi deploy hanya dari branch `main`.

> Jangan pernah commit private key atau file `.env` ke repo.

---

## C. Pemakaian sehari-hari di VS Code

1. Edit kode.
2. Panel **Source Control** (Ctrl+Shift+G) → tulis pesan commit → **Commit**.
3. Klik **Sync Changes / Push** ke branch `main`.
4. Pantau progresnya di GitHub → tab **Actions**. Hijau = sudah live.

> Konten portfolio (skill, pengalaman, dll.) **tidak perlu** di-deploy; cukup diubah lewat `https://<DOMAIN_ANDA>/admin`. Deploy hanya diperlukan saat mengubah kode/tampilan.

---

## D. Jika deploy gagal / rollback

- **Test gagal** → deploy tidak jalan, situs tetap versi lama. Perbaiki lalu push lagi.
- **Deploy gagal di tengah** → situs otomatis keluar dari maintenance mode. Lihat log di tab Actions.
- **Rollback kode** (sebagai user `deploy` di VPS):
  ```bash
  cd /var/www/portfolio && git log --oneline -5
  ```
  ```bash
  git checkout <COMMIT_SEBELUMNYA> && bash deploy/deploy.sh
  ```
  Setelah diperbaiki di `main`, kembalikan dengan `git checkout main`. Lebih rapi lagi: `git revert` di VS Code lalu push.
- **Backup database** sebelum perubahan besar:
  ```bash
  mysqldump -u portfolio -p portfolio > ~/backup-portfolio-$(date +%F).sql
  ```
  Rollback kode tidak otomatis membatalkan perubahan skema/data. Restore dari backup jika perlu.
