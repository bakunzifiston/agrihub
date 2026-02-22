# TraceNova integration (AgriHub)

AgriHub sends **session data** to your TraceNova app using the session API.

## API calls (automatic)

When `TRACENOVA_ENABLED=true` and `TRACENOVA_BASE_URL` is set, the tracking script in the layout:

1. **On page load** – `POST /api/v1/sessions/start`
   - Body: `{ "session_id": "uuid", "user_id": "optional", "metadata": { "app": "AgriHub", "app_id": "...", "tenant_type": "farmer" } }`
   - `session_id` is a UUID stored in `sessionStorage` (same tab/session).
   - `user_id` is the logged-in user ID when available.

2. **Every 60 seconds** – `POST /api/v1/sessions/heartbeat`
   - Body: `{ "session_id": "...", "foreground_seconds": 120, "background_seconds": 30 }`
   - Time is split using `document.visibilityState` (visible = foreground, hidden = background).

3. **On leave/close** – `POST /api/v1/sessions/end`
   - Body: `{ "session_id": "...", "duration_seconds": 150, "foreground_seconds": 120, "background_seconds": 30 }`
   - Sent on `pagehide` and `beforeunload` with `keepalive` so it completes when the tab closes.

## Configure AgriHub

In `.env`:

```env
TRACENOVA_ENABLED=true
TRACENOVA_BASE_URL=https://tracenova.sandbox.rw
TRACENOVA_APP_ID=1
```

Optional (if TraceNova requires auth for the API):

```env
TRACENOVA_API_KEY=your-api-key
```

The script sends `Authorization: Bearer TRACENOVA_API_KEY` on every request when set.

Then run:

```bash
php artisan config:clear
```

## Where it runs

- **Layout:** `resources/views/layouts/app.blade.php` includes `layouts.partials.tracenova-tracking`.
- **Partial:** `resources/views/layouts/partials/tracenova-tracking.blade.php` contains the inline script that calls the three endpoints.

## Disable tracking

```env
TRACENOVA_ENABLED=false
```

Or remove the TraceNova vars; the partial outputs nothing when disabled.
