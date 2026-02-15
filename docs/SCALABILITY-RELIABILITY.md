# Scalability & Reliability Guide

This document describes how to keep AgriHub **scalable** (handling many users and tenants) and **reliable** (available and safe) as usage grows.

---

## 1. Scalability

### 1.1 Use Redis for cache, sessions, and queues (recommended for production)

- **Cache**: Reduces repeated DB queries (e.g. feature flags, permissions). Use Redis instead of database.
- **Sessions**: With many users, file/database sessions don’t scale well. Redis (or a dedicated session store) scales better.
- **Queues**: Offload emails, reports, and heavy work to background jobs. Redis is faster and more reliable than the database driver at scale.

**In production `.env`:**
```env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

Ensure Redis is installed and `REDIS_HOST`, `REDIS_PASSWORD`, `REDIS_PORT` are set. Run queue workers (see 2.3).

### 1.2 Database: indexes and query discipline

- **Indexes**: Migrations already add indexes on foreign keys and common filters. For new tables, index columns used in `where`, `orderBy`, and joins.
- **Avoid N+1**: Use `with()` to eager-load relations (e.g. `->with('customer', 'inventory.warehouse')`) so lists don’t run hundreds of queries.
- **Pagination**: Use `->paginate(15)` (or similar) on index pages instead of `->get()` so large tables don’t load at once.

### 1.3 Application server: scale out

- Run the app behind a **reverse proxy** (Nginx/Apache). Serve static assets and use the proxy for HTTPS and buffering.
- **Multiple app instances**: Run several PHP-FPM workers or Laravel Octane workers; put a load balancer in front so traffic is spread.
- **Stateless app**: Keep sessions in Redis (or database) so any instance can serve any user. Avoid storing user state only on one server.

### 1.4 Static assets and uploads

- **Build frontend assets** (`npm run build`) and, if possible, serve them via a **CDN** or separate asset host to reduce load on the app.
- **File uploads**: Use object storage (e.g. S3-compatible) for user uploads so the app server stays stateless and storage can scale independently.

### 1.5 Multi-tenancy

- All tenants share the same database and tables; tenant scope is enforced by `farmer_id`, `agribusiness_id`, `cooperative_id` (or similar). This is simple and works well for moderate scale.
- Keep tenant filters on every query that touches tenant-specific data. Your controllers already use `auth()->user()->...` for this.
- If you later outgrow a single DB, you can move to a “database per tenant” or “schema per tenant” model and adjust connections accordingly.

---

## 2. Reliability

### 2.1 Database backups

- **Automate daily backups** of the MySQL/MariaDB database (and any file storage that holds important data).
- Retain multiple backup sets (e.g. 7 daily, 4 weekly) and store them off the app server.
- Test restore periodically so you know backups work.

### 2.2 Monitoring and alerting

- **Uptime**: Use an external service to hit a health URL (e.g. `/up` or `/health`) every few minutes and alert on failures.
- **Logs**: In production use `LOG_CHANNEL=stack` with a log driver that aggregates (e.g. `daily` plus a log aggregator). Avoid `LOG_LEVEL=debug` in production.
- **Errors**: Laravel’s exception reporting (e.g. Sentry, Flare, or email) helps catch and fix errors quickly.

### 2.3 Queue workers and failed jobs

- When using queues, run at least one worker process:  
  `php artisan queue:work --tries=3`
- Use **Supervisor** (or similar) to keep the worker running and restart it if it crashes.
- In production, use **Redis** (or SQS, etc.) for the queue so jobs aren’t lost and workers can run on separate machines.
- Monitor the `failed_jobs` table and fix or retry failed jobs.

### 2.4 Maintenance and deployments

- Use **maintenance mode** during deployments:  
  `php artisan down` → deploy → `php artisan migrate` (if needed) → `php artisan up`
- Prefer **zero-downtime** deploys: e.g. deploy to a new app instance, then switch the load balancer, or use blue-green.
- Keep **PHP and Laravel** (and Redis, MySQL) on supported versions and security updates.

### 2.5 Security and stability

- **HTTPS**: Enforce TLS for the whole site.
- **Environment**: `APP_DEBUG=false` and `APP_ENV=production` in production. Never expose `.env` or debug output to the internet.
- **Rate limiting**: Use Laravel’s throttle middleware on login and sensitive routes to reduce abuse and DDoS impact.
- **Strong DB credentials**: Unique, strong passwords; limit DB user to the app database and required privileges only.

---

## 3. Quick checklist (production)

| Area              | Action |
|-------------------|--------|
| Cache             | `CACHE_STORE=redis` (with Redis installed) |
| Sessions          | `SESSION_DRIVER=redis` (or database if Redis not available) |
| Queues            | `QUEUE_CONNECTION=redis`, run `queue:work` via Supervisor |
| DB                | Indexes on FKs and filters; use `paginate()` and `with()` |
| Backups           | Automated daily DB (and storage) backups; test restore |
| Monitoring        | Health endpoint + uptime checks; error reporting (e.g. Sentry) |
| Logs              | `LOG_LEVEL=info` or `warning`; avoid `debug` in production |
| App               | `APP_DEBUG=false`, `APP_ENV=production`, HTTPS, rate limiting |

---

## 4. Health endpoints

- **`/up`** – Laravel’s built-in health route (see `health: '/up'` in `bootstrap/app.php`). Use it for “app is running” checks.
- **`/health`** – Custom route that checks database connectivity: returns **200** with `{"status":"ok"}` when the DB is reachable, and **503** with `{"status":"unhealthy","message":"Database unreachable"}` when the DB is not (so load balancers can distinguish app-up vs. temporary DB issues). No auth; keep it lightweight.
