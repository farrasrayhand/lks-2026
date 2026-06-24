<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Health Check - Kaltim Smart Platform</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { width: 100%; max-width: 700px; padding: 20px; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 32px; }
        h1 { font-size: 1.5rem; color: #f8fafc; margin-bottom: 4px; }
        .sub { color: #64748b; font-size: 0.85rem; margin-bottom: 24px; }
        .status-row { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; border-radius: 8px; margin-bottom: 8px; }
        .status-row:nth-child(odd) { background: #0f172a; }
        .status-row .label { display: flex; align-items: center; gap: 10px; font-weight: 500; font-size: 0.95rem; }
        .status-row .label .icon { font-size: 1.2rem; }
        .dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
        .dot-ok { background: #22c55e; box-shadow: 0 0 8px #22c55e66; animation: pulse 2s infinite; }
        .dot-err { background: #ef4444; box-shadow: 0 0 8px #ef444466; }
        .tag { padding: 3px 10px; border-radius: 4px; font-size: 0.72rem; font-weight: 600; }
        .tag-ok { background: #064e3b; color: #34d399; }
        .tag-err { background: #450a0a; color: #fca5a5; }
        .tag-info { background: #1e3a5f; color: #60a5fa; }
        .overall { text-align: center; padding: 20px; margin-top: 20px; border-radius: 10px; }
        .overall-ok { background: #064e3b22; border: 1px solid #065f46; }
        .overall-err { background: #450a0a22; border: 1px solid #7f1d1d; }
        .overall h2 { font-size: 1.3rem; margin-bottom: 4px; }
        .overall-ok h2 { color: #34d399; }
        .overall-err h2 { color: #fca5a5; }
        .error-msg { font-size: 0.78rem; color: #fca5a5; margin-top: 2px; }
        .refresh { display: flex; align-items: center; gap: 6px; color: #64748b; font-size: 0.78rem; margin-top: 16px; text-align: center; }
        .refresh span { cursor: pointer; color: #60a5fa; text-decoration: underline; }
        .timestamp { color: #475569; font-size: 0.75rem; margin-top: 8px; text-align: center; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.5; } }
        .btn-back { display: inline-block; color: #60a5fa; text-decoration: none; font-size: 0.85rem; margin-top: 16px; }
        .btn-back:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Kaltim Smart Platform</h1>
            <p class="sub">System Health Status</p>

            @foreach($checks as $key => $check)
                @if($key === 'app' || $key === 'timestamp' || $key === 'api') @continue @endif
                <div class="status-row">
                    <div class="label">
                        @php
                            $icons = ['database' => '🗄️', 'cache' => '⚡', 'storage' => '📦'];
                            $labels = ['database' => 'Database (MySQL)', 'cache' => 'Cache (Redis)', 'storage' => 'Storage (S3/Local)'];
                        @endphp
                        <span class="icon">{{ $icons[$key] ?? '🔧' }}</span>
                        <span>{{ $labels[$key] ?? ucfirst($key) }}</span>
                        <span class="tag tag-info">{{ $check['driver'] ?? $check['disk'] ?? $check['connection'] ?? '' }}</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        @if($check['status'] === 'ok')
                            <span class="tag tag-ok">OK</span>
                            <span class="dot dot-ok"></span>
                        @else
                            <span class="tag tag-err">ERROR</span>
                            <span class="dot dot-err"></span>
                        @endif
                    </div>
                </div>
                @if($check['status'] !== 'ok' && isset($check['message']))
                    <div class="error-msg" style="padding:0 16px 8px;">{{ $check['message'] }}</div>
                @endif
            @endforeach

            @php $allOk = true; foreach(['database','cache','storage'] as $c) { if(($checks[$c]['status']??'') !== 'ok') $allOk = false; } @endphp
            <div class="overall {{ $allOk ? 'overall-ok' : 'overall-err' }}">
                <h2>{{ $allOk ? 'All Systems Operational' : 'Service Degradation Detected' }}</h2>
                <p style="color:#94a3b8;font-size:0.85rem;">
                    {{ $allOk ? 'Semua layanan berjalan normal' : 'Beberapa layanan mengalami gangguan' }}
                </p>
            </div>

            <div class="refresh">
                <span onclick="location.reload()">Refresh</span> &bull; Auto-refresh in <span id="countdown">30</span>s
            </div>
            <div class="timestamp">Last checked: {{ $checks['timestamp'] ?? now() }}</div>
            <div style="text-align:center;"><a href="/" class="btn-back">← Back to Home</a></div>
        </div>
    </div>
    <script>
        let t = 30;
        setInterval(() => {
            t--;
            document.getElementById('countdown').textContent = t;
            if (t <= 0) location.reload();
        }, 1000);
    </script>
</body>
</html>
