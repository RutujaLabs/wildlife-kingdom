<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wildlife Kingdom Admin')</title>
    <style>
        body { margin: 0; font-family: 'Outfit', sans-serif; background: #f4efe9; color: #1f2b2a; }
        .admin-shell { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 260px; background: #0B2F22; color: #fff; padding: 28px 20px; }
        .admin-sidebar .brand { font-size: 1.4rem; font-weight: 700; margin-bottom: 28px; }
        .admin-sidebar nav a { display: block; color: rgba(255,255,255,.9); text-decoration: none; margin: 10px 0; padding: 12px 14px; border-radius: 10px; }
        .admin-sidebar nav a:hover, .admin-sidebar nav a.active { background: rgba(255,255,255,.08); }
        .admin-main { flex: 1; padding: 30px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .panel { background: #fff; border-radius: 16px; padding: 22px; box-shadow: 0 10px 30px rgba(20,33,26,.08); }
        .btn { display:inline-flex; align-items:center; justify-content:center; padding: 10px 16px; border-radius:999px; text-decoration:none; border:1px solid transparent; font-weight:600; }
        .btn-primary { background:#0B2F22; color:#fff; }
        .btn-secondary { background:#E8A15B; color:#000; }
        .btn-danger { background:#a12626; color:#fff; }
        .btn-outline { border-color:#0B2F22; color:#0B2F22; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 18px; }
        .stat-box { background: linear-gradient(135deg,#f5f0e8,#fff); border:1px solid #ebdec2; border-radius: 14px; padding: 20px; }
        .stat-box .label { color:#6c6a69; font-size: .8rem; text-transform: uppercase; letter-spacing: .12em; }
        .stat-box .value { font-size: 2rem; font-weight: 700; margin-top: 8px; }
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse: collapse; }
        th, td { padding: 12px 10px; border-bottom: 1px solid #eee; text-align:left; }
        th { background:#f9f7f2; }
        form .field { margin-bottom: 18px; }
        label { display:block; margin-bottom:8px; font-weight:600; }
        input, textarea, select { width:100%; padding: 10px 12px; border: 1px solid #d9d1c2; border-radius: 10px; font: inherit; }
        textarea { min-height:120px; }
        .grid-two { display:grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap: 18px; }
        .alert { padding: 12px 14px; border-radius:10px; margin-bottom:20px; }
        .alert-success { background:#ebf9ef; color:#1d4c2d; border: 1px solid #c6ebd0; }
        .alert-error { background:#fff0f0; color:#7f1d1d; border: 1px solid #f3c0c0; }
        .empty { color:#6f6d6b; }
    </style>
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="brand">Wildlife Kingdom</div>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.animals.index') }}" class="{{ request()->routeIs('admin.animals.*') ? 'active' : '' }}">Animals</a>
            <a href="{{ route('admin.habitats.index') }}" class="{{ request()->routeIs('admin.habitats.*') ? 'active' : '' }}">Habitats</a>
            <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">Events</a>
            <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">Gallery</a>
            <form action="{{ route('admin.logout') }}" method="POST" style="margin-top:16px;">
                @csrf
                <button type="submit" class="btn btn-secondary" style="width:100%;">Logout</button>
            </form>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="topbar">
            <h1 style="margin:0;">@yield('page-title', 'Dashboard')</h1>
            <div style="color:#5a5857;">Administrator</div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>
