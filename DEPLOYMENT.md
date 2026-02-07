# Deploying AgriHub (e.g. cPanel)

## Fix "Vite manifest not found" on cPanel

The app now has a **fallback**: if `public/build/manifest.json` is missing, it loads Tailwind and Alpine from a CDN so the site still works. For the best experience (your theme, Chart.js, etc.), build assets and upload them.

### Option A: Build locally and upload (recommended for cPanel)

1. On your **local machine** (where you have Node.js):
   ```bash
   cd /path/to/agrihub
   npm install
   npm run build
   ```
2. Upload the **entire `public/build`** folder to your server:
   - Local: `public/build/` (contains `manifest.json` and `assets/`)
   - Server: `public_html/build/` (or your `public` folder on cPanel)

3. Make sure the document root points to the `public` directory (Laravel’s entry point is `public/index.php`).

### Option B: Build on the server (if Node is available)

If your host provides Node.js (e.g. via SSH or “Setup Node.js” in cPanel):

```bash
cd /home/koraqinn/agribusiness.korawigire.rw
npm install
npm run build
```

No need to upload `public/build`; it will be created on the server.

---

## General cPanel checklist

- **Document root** → Laravel’s `public` folder (e.g. `public_html` = contents of `public/`).
- **.env** → Create from `.env.example`, set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://agribusiness.korawigire.rw`, and your database credentials.
- **Database** → Create MySQL DB and user in cPanel, run `php artisan migrate`.
- **Storage link** → `php artisan storage:link` (if you use file uploads).
