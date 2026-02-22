@php
    $tracenova = config('services.tracenova', []);
    $enabled = filter_var($tracenova['enabled'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $baseUrl = $tracenova['base_url'] ?? '';
    $appId = $tracenova['app_id'] ?? '';
    $apiKey = $tracenova['api_key'] ?? '';
    $scriptUrl = $tracenova['script_url'] ?? '';
    $userId = auth()->check() ? (string) auth()->id() : null;
    $metadata = array_filter([
        'app' => config('app.name'),
        'app_id' => $appId,
        'tenant_type' => auth()->check() ? auth()->user()->tenant_type : null,
    ]);
@endphp
@if ($enabled && $baseUrl)
    {{-- TraceNova session API: start, heartbeat, end --}}
    <script>
    (function() {
        var base = @json($baseUrl);
        var apiKey = @json($apiKey);
        var userId = @json($userId);
        var metadata = @json($metadata);
        var api = base + '/api/v1/sessions';
        var sessionId = sessionStorage.getItem('tracenova_session_id');
        if (!sessionId) {
            sessionId = 'tn-' + (typeof crypto !== 'undefined' && crypto.randomUUID ? crypto.randomUUID() : Date.now() + '-' + Math.random().toString(36).slice(2));
            sessionStorage.setItem('tracenova_session_id', sessionId);
        }
        var headers = { 'Content-Type': 'application/json' };
        if (apiKey) headers['Authorization'] = 'Bearer ' + apiKey;

        var foregroundSec = 0, backgroundSec = 0;
        var lastForeground = 0, lastBackground = 0;
        var lastTick = Date.now() / 1000;
        var isVisible = true;

        function tick() {
            var now = Date.now() / 1000;
            var elapsed = now - lastTick;
            lastTick = now;
            if (isVisible) foregroundSec += elapsed; else backgroundSec += elapsed;
        }

        document.addEventListener('visibilitychange', function() {
            tick();
            isVisible = document.visibilityState === 'visible';
        });

        function post(url, body, keepalive) {
            var payload = JSON.stringify(body);
            fetch(url, { method: 'POST', headers: headers, body: payload, keepalive: !!keepalive }).catch(function() {});
        }

        post(api + '/start', {
            session_id: sessionId,
            user_id: userId || null,
            metadata: metadata
        });

        setInterval(function() {
            tick();
            var f = Math.round(foregroundSec - lastForeground);
            var b = Math.round(backgroundSec - lastBackground);
            lastForeground = foregroundSec;
            lastBackground = backgroundSec;
            if (f > 0 || b > 0) {
                post(api + '/heartbeat', {
                    session_id: sessionId,
                    foreground_seconds: f,
                    background_seconds: b
                });
            }
        }, 60000);

        function endSession() {
            tick();
            post(api + '/end', {
                session_id: sessionId,
                duration_seconds: Math.round(foregroundSec + backgroundSec),
                foreground_seconds: Math.round(foregroundSec),
                background_seconds: Math.round(backgroundSec)
            }, true);
        }

        window.addEventListener('pagehide', endSession);
        window.addEventListener('beforeunload', endSession);
    })();
    </script>
@endif
