<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wildlife Kingdom Admin')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --forest: #0B2F22;
            --forest-soft: #14412f;
            --gold: #E8A15B;
            --gold-deep: #C9822F;
            --cream: #f4efe9;
            --ink: #1f2b2a;
            --muted: #6c6a69;
            --line: #eee6d8;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Outfit', sans-serif; background: var(--cream); color: var(--ink); }
        a { color: inherit; }

        /* Layout */
        .admin-shell { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 260px; flex-shrink: 0; background: var(--forest); color: #fff; padding: 24px 18px; position: sticky; top: 0; height: 100vh; overflow-y: auto; display: flex; flex-direction: column; }
        .admin-sidebar .brand { text-align: center; margin-bottom: 20px; }
        .admin-sidebar .brand a { display: block; }
        .admin-sidebar .brand img { width: 100%; max-width: 190px; height: auto; display: inline-block; }
        .admin-sidebar nav { display: flex; flex-direction: column; flex: 1; }
        .admin-sidebar nav a { display: block; color: rgba(255,255,255,.85); text-decoration: none; margin: 4px 0; padding: 12px 16px; border-radius: 10px; font-weight: 500; border-left: 3px solid transparent; transition: background .2s, color .2s; }
        .admin-sidebar nav a:hover { background: rgba(255,255,255,.08); color: #fff; }
        .admin-sidebar nav a.active { background: rgba(232,161,91,.15); color: #fff; border-left-color: var(--gold); }
        .admin-sidebar nav form { margin-top: auto; padding-top: 20px; }
        .admin-main { flex: 1; min-width: 0; padding: 32px 40px 48px; }

        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 26px; gap: 16px; }
        .topbar h1 { margin: 0; font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; color: var(--forest); }
        .user-menu { position: relative; }
        .user-menu summary { list-style: none; cursor: pointer; display: flex; align-items: center; gap: 10px; background: #fff; padding: 6px 14px 6px 6px; border-radius: 999px; box-shadow: 0 4px 14px rgba(20,33,26,.07); font-weight: 500; color: #3d4a48; user-select: none; }
        .user-menu summary::-webkit-details-marker { display: none; }
        .user-menu summary:focus-visible { outline: 2px solid var(--forest); outline-offset: 2px; }
        .user-menu .avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--forest); color: var(--gold); display: grid; place-items: center; font-weight: 700; }
        .user-menu .chev { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; transition: transform .2s; }
        .user-menu[open] .chev { transform: rotate(180deg); }
        .user-dropdown { position: absolute; right: 0; top: calc(100% + 10px); width: 230px; background: #fff; border-radius: 14px; padding: 8px; box-shadow: 0 16px 40px rgba(20,33,26,.18); border: 1px solid var(--line); z-index: 50; }
        .user-dropdown .who { padding: 12px 12px 14px; border-bottom: 1px solid var(--line); margin-bottom: 6px; }
        .user-dropdown .who strong { display: block; color: var(--forest); }
        .user-dropdown .who span { color: var(--muted); font-size: .85rem; }
        .user-dropdown .logout-btn { width: 100%; text-align: left; background: none; border: 0; font: inherit; font-weight: 500; color: #a12626; padding: 10px 12px; border-radius: 10px; cursor: pointer; }
        .user-dropdown .logout-btn:hover { background: #fff0f0; }

        /* Shared components (used by other admin pages too) */
        .panel { background: #fff; border-radius: 18px; padding: 24px; box-shadow: 0 8px 28px rgba(20,33,26,.07); border: 1px solid rgba(235,222,194,.5); }
        .btn { display:inline-flex; align-items:center; justify-content:center; padding: 10px 18px; border-radius:999px; text-decoration:none; border:1px solid transparent; font-weight:600; font-family: inherit; font-size: .95rem; cursor: pointer; transition: transform .15s, box-shadow .15s, background .15s; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(20,33,26,.15); }
        .btn-primary { background: var(--forest); color:#fff; }
        .btn-secondary { background: var(--gold); color:#000; }
        .btn-danger { background:#a12626; color:#fff; }
        .btn-outline { border-color: var(--forest); color: var(--forest); background: transparent; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 18px; }
        .stat-box { background: linear-gradient(135deg,#f5f0e8,#fff); border:1px solid #ebdec2; border-radius: 14px; padding: 20px; }
        .stat-box .label { color: var(--muted); font-size: .8rem; text-transform: uppercase; letter-spacing: .12em; }
        .stat-box .value { font-size: 2rem; font-weight: 700; margin-top: 8px; }
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse: collapse; }
        th, td { padding: 12px 10px; border-bottom: 1px solid #eee; text-align:left; }
        th { background:#f9f7f2; }
        form .field { margin-bottom: 18px; }
        label { display:block; margin-bottom:8px; font-weight:600; }
        input, textarea, select { width:100%; padding: 10px 12px; border: 1px solid #d9d1c2; border-radius: 10px; font: inherit; }
        input:focus, textarea:focus, select:focus { outline: 2px solid rgba(232,161,91,.5); border-color: var(--gold); }
        textarea { min-height:120px; }
        .grid-two { display:grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap: 18px; }
        .alert { padding: 12px 14px; border-radius:10px; margin-bottom:20px; }
        .alert-success { background:#ebf9ef; color:#1d4c2d; border: 1px solid #c6ebd0; }
        .alert-error { background:#fff0f0; color:#7f1d1d; border: 1px solid #f3c0c0; }
        .empty { color:#6f6d6b; }

        /* Mobile */
        @media (max-width: 860px) {
            .admin-shell { flex-direction: column; }
            .admin-sidebar { width: 100%; height: auto; position: static; padding: 16px; }
            .admin-sidebar .brand img { max-width: 140px; }
            .admin-sidebar nav { flex-direction: row; flex-wrap: wrap; gap: 6px; }
            .admin-sidebar nav a { margin: 0; padding: 10px 14px; border-left: 0; }
            .admin-sidebar nav form { margin-top: 0; padding-top: 0; width: 100%; }
            .admin-main { padding: 20px 16px 40px; }
            .topbar h1 { font-size: 1.6rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="brand">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/wildlife-kingdom-header(1).png') }}" alt="Wildlife Kingdom">
            </a>
        </div>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.animals.index') }}" class="{{ request()->routeIs('admin.animals.*') ? 'active' : '' }}">Animals</a>
            <a href="{{ route('admin.habitats.index') }}" class="{{ request()->routeIs('admin.habitats.*') ? 'active' : '' }}">Habitats</a>
            <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">Events</a>
            <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">Gallery</a>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary" style="width:100%;">Logout</button>
            </form>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="topbar">
            <h1>@yield('page-title', 'Dashboard')</h1>
            <details class="user-menu" id="userMenu">
                <summary>
                    <span class="avatar">R</span>
                    Rutuja Bhapkar
                    <svg class="chev" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
                </summary>
                <div class="user-dropdown">
                    <div class="who">
                        <strong>Rutuja Bhapkar</strong>
                        <span>Administrator</span>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </details>
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
<script>
    (function () {
        var menu = document.getElementById('userMenu');
        if (!menu) return;
        document.addEventListener('click', function (e) { if (!menu.contains(e.target)) menu.removeAttribute('open'); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') menu.removeAttribute('open'); });
    })();
</script>
</body>
</html>