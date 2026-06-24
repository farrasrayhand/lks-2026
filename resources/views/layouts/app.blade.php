<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kaltim Smart Platform')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #f1f5f9; color: #1e293b; min-height: 100vh; }
        nav { background: #0f172a; color: #e2e8f0; padding: 0 24px; display: flex; align-items: center; height: 56px; justify-content: space-between; }
        nav .brand { font-weight: 700; font-size: 1rem; color: #38bdf8; text-decoration: none; }
        nav .links { display: flex; gap: 16px; align-items: center; }
        nav .links a, nav .links button { color: #e2e8f0; text-decoration: none; font-size: 0.85rem; background: none; border: none; cursor: pointer; padding: 6px 12px; border-radius: 4px; }
        nav .links a:hover, nav .links button:hover { background: #1e293b; }
        nav .role-badge { background: #1e293b; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; color: #38bdf8; border: 1px solid #334155; }
        .container { max-width: 1000px; margin: 0 auto; padding: 24px 20px; }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 0.88rem; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .card h2 { font-size: 1.1rem; margin-bottom: 12px; color: #1e293b; }
        .btn { display: inline-block; padding: 8px 16px; border-radius: 6px; font-size: 0.85rem; font-weight: 500; cursor: pointer; text-decoration: none; border: none; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-secondary { background: #e2e8f0; color: #475569; }
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 4px; color: #475569; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.15); }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        th, td { text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; }
        th { font-weight: 600; color: #475569; font-size: 0.8rem; text-transform: uppercase; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 0.72rem; font-weight: 600; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .badge-red { background: #fef2f2; color: #991b1b; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-gray { background: #f1f5f9; color: #475569; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; }
        .stat { text-align: center; padding: 16px; }
        .stat-num { font-size: 1.8rem; font-weight: 700; color: #2563eb; }
        .stat-label { font-size: 0.78rem; color: #64748b; margin-top: 2px; }
        .empty { text-align: center; padding: 40px; color: #94a3b8; }
        .flex { display: flex; gap: 10px; align-items: center; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; }
        .mb { margin-bottom: 16px; }
        .text-sm { font-size: 0.85rem; color: #64748b; }
        @media (max-width: 640px) {
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <nav>
        <a href="/" class="brand">Kaltim Smart Platform</a>
        <div class="links">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="/admin/dashboard">Dashboard</a>
                    <a href="/admin/services">Layanan</a>
                    <a href="/admin/reports">Laporan</a>
                    <span class="role-badge">Admin</span>
                @else
                    <a href="/citizen/dashboard">Dashboard</a>
                    <a href="/citizen/services">Layanan</a>
                    <a href="/citizen/reports">Laporan</a>
                    <span class="role-badge">Warga</span>
                @endif
                <form method="POST" action="/logout" style="display:inline">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a href="/login">Login</a>
                <a href="/register">Register</a>
                <a href="/api-info">API</a>
            @endauth
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
