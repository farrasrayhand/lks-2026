<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>API - Kaltim Smart Platform</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; }
        nav { background: #1e293b; padding: 0 24px; display: flex; align-items: center; height: 56px; }
        nav a { color: #38bdf8; text-decoration: none; font-weight: 700; font-size: 1rem; }
        nav a:hover { text-decoration: underline; }
        .container { max-width: 960px; margin: 0 auto; padding: 40px 20px; }
        h1 { font-size: 1.8rem; color: #38bdf8; margin-bottom: 4px; }
        .sub { color: #94a3b8; margin-bottom: 30px; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .card h2 { font-size: 1.1rem; color: #38bdf8; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #334155; }
        .endpoint { display: flex; align-items: center; gap: 10px; padding: 5px 0; font-family: "SF Mono", "Fira Code", monospace; font-size: 0.85rem; }
        .method { font-weight: 700; font-size: 0.7rem; padding: 2px 8px; border-radius: 3px; min-width: 48px; text-align: center; }
        .get { background: #064e3b; color: #34d399; }
        .post { background: #1e3a5f; color: #60a5fa; }
        .put { background: #78350f; color: #fbbf24; }
        .tag { font-size: 0.65rem; margin-left: 4px; padding: 1px 4px; border-radius: 2px; }
        .tag-auth { color: #f87171; }
        .tag-admin { color: #fbbf24; }
        .accounts { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .account { background: #0f172a; border: 1px solid #334155; border-radius: 6px; padding: 12px; }
        .account .role { font-weight: 600; color: #38bdf8; }
        .account code { color: #94a3b8; font-size: 0.85rem; }
        .footer { text-align: center; margin-top: 20px; color: #64748b; font-size: 0.8rem; }
        .footer a { color: #38bdf8; text-decoration: none; }
        .desc { font-size: 0.8rem; color: #64748b; margin-left: 12px; }
    </style>
</head>
<body>
<nav><a href="/">Kaltim Smart Platform</a></nav>
<div class="container">
    <h1>API Documentation</h1>
    <p class="sub">Base URL: <code style="background:#1e293b;padding:2px 6px;border-radius:3px;">{{ config('app.url') }}/api</code></p>

    <div class="card">
        <h2>Response Format</h2>
        <pre style="background:#0f172a;padding:12px;border-radius:6px;font-size:0.82rem;">{
  "success": true|false,
  "message": "...",
  "data": { ... }
}</pre>
    </div>

    <div class="card">
        <h2>Autentikasi</h2>
        <div class="endpoint"><span class="method post">POST</span> /auth/register <span class="desc">Registrasi warga baru</span></div>
        <div class="endpoint"><span class="method post">POST</span> /auth/login <span class="desc">Login, dapatkan JWT token</span></div>
        <div class="endpoint"><span class="method post">POST</span> /auth/logout <span class="tag tag-auth">Auth</span></div>
        <div class="endpoint"><span class="method get">GET</span> /auth/profile <span class="tag tag-auth">Auth</span></div>
    </div>

    <div class="card">
        <h2>Layanan Publik</h2>
        <div class="endpoint"><span class="method get">GET</span> /services</div>
        <div class="endpoint"><span class="method post">POST</span> /services/request <span class="tag tag-auth">Auth</span></div>
        <div class="endpoint"><span class="method get">GET</span> /services/request/{id} <span class="tag tag-auth">Auth</span></div>
        <div class="endpoint"><span class="method put">PUT</span> /services/request/{id}/status <span class="tag tag-auth">Auth</span> <span class="tag tag-admin">Admin</span></div>
        <div class="endpoint"><span class="method get">GET</span> /services/requests <span class="tag tag-auth">Auth</span></div>
    </div>

    <div class="card">
        <h2>Laporan Warga</h2>
        <div class="endpoint"><span class="method post">POST</span> /reports <span class="tag tag-auth">Auth</span></div>
        <div class="endpoint"><span class="method get">GET</span> /reports <span class="tag tag-auth">Auth</span></div>
        <div class="endpoint"><span class="method get">GET</span> /reports/{id} <span class="tag tag-auth">Auth</span></div>
        <div class="endpoint"><span class="method put">PUT</span> /reports/{id} <span class="tag tag-auth">Auth</span></div>
    </div>

    <div class="card">
        <h2>Notifikasi</h2>
        <div class="endpoint"><span class="method get">GET</span> /notifications <span class="tag tag-auth">Auth</span></div>
    </div>

    <div class="card">
        <h2>Dashboard Admin</h2>
        <div class="endpoint"><span class="method get">GET</span> /dashboard/stats <span class="tag tag-auth">Auth</span> <span class="tag tag-admin">Admin</span></div>
        <div class="endpoint"><span class="method get">GET</span> /dashboard/reports/summary <span class="tag tag-auth">Auth</span> <span class="tag tag-admin">Admin</span></div>
    </div>

    <div class="card">
        <h2>Akun Demo</h2>
        <div class="accounts">
            <div class="account"><div class="role">Admin</div><code>admin@kaltim.go.id</code> / <code>password</code></div>
            <div class="account"><div class="role">Citizen</div><code>budi@email.com</code> / <code>password</code></div>
        </div>
    </div>

    <div class="footer">
        <a href="/">Home</a> &bull; Kaltim Smart Platform &bull; LKS Cloud Computing 2026
    </div>
</div>
</body>
</html>
