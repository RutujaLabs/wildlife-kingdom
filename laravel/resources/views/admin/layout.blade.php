<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wildlife Kingdom Admin')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Outfit:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,500&display=swap" rel="stylesheet">
    <style>
        :root {
            --forest: #0B2F22;
            --forest-soft: #14412f;
            --gold: #E8A15B;
            --gold-deep: #C9822F;
            --orange: #F29A2E;
            --cream: #f6f3ed;
            --ink: #1f2b2a;
            --muted: #6c6a69;
            --line: #eee6d8;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Outfit', sans-serif; background: var(--cream); color: var(--ink); }
        a { color: inherit; }

        /* ---------- Shell ---------- */
        .admin-shell { display: flex; min-height: 100vh; }
        .admin-content { flex: 1; min-width: 0; display: flex; flex-direction: column; }

        /* ---------- Sidebar ---------- */
        .admin-sidebar { width: 272px; flex-shrink: 0; position: sticky; top: 0; height: 100vh; overflow-y: auto; display: flex; flex-direction: column; color: #fff; padding: 22px 16px 0; background:
            radial-gradient(circle at 0% 0%, rgba(60,130,90,.35), transparent 45%),
            radial-gradient(circle at 100% 100%, rgba(60,130,90,.25), transparent 50%),
            linear-gradient(180deg, #0d3a2a 0%, #0a2b1f 100%); transition: margin-left .25s; }
        .brand { text-align: center; margin-bottom: 18px; }
        .brand a { display: block; }
        .brand img { width: 100%; max-width: 200px; height: auto; display: inline-block; }
        .side-nav { display: flex; flex-direction: column; gap: 4px; }
        .side-nav a { display: flex; align-items: center; gap: 14px; color: rgba(255,255,255,.92); text-decoration: none; padding: 12px 16px; border-radius: 12px; font-weight: 500; font-size: .98rem; transition: background .2s; }
        .side-nav a svg { width: 22px; height: 22px; flex-shrink: 0; fill: none; stroke: currentColor; stroke-width: 1.9; stroke-linecap: round; stroke-linejoin: round; }
        .side-nav a:hover { background: rgba(255,255,255,.09); }
        .side-nav a.active { background: linear-gradient(90deg, #F5A524, #EE8A2B); color: #0B2F22; font-weight: 600; box-shadow: 0 6px 16px rgba(238,138,43,.35); }
        .side-divider { height: 1px; background: rgba(255,255,255,.16); margin: 16px 10px 10px; }
        .side-logout { margin: 0; }
        .side-logout button { width: 100%; display: flex; align-items: center; gap: 14px; background: none; border: 0; color: rgba(255,255,255,.92); font: inherit; font-weight: 500; font-size: .98rem; padding: 12px 16px; border-radius: 12px; cursor: pointer; text-align: left; }
        .side-logout button:hover { background: rgba(255,255,255,.09); }
        .side-logout svg { width: 22px; height: 22px; fill: none; stroke: currentColor; stroke-width: 1.9; stroke-linecap: round; stroke-linejoin: round; }
        .side-footer { margin-top: auto; position: relative; height: 210px; margin-left: -16px; margin-right: -16px; overflow: hidden; }
        .side-footer svg.scene { position: absolute; left: 0; bottom: 0; width: 100%; height: 100%; }
        .side-quote { position: absolute; left: 0; right: 0; top: 34px; text-align: center; font-family: 'Caveat', cursive; font-size: 1.7rem; line-height: 1.05; color: #f3ecd8; transform: rotate(-6deg); text-shadow: 0 2px 8px rgba(0,0,0,.35); }
        .side-quote svg { display: block; margin: 4px auto 0; width: 20px; height: 20px; fill: #5fb97a; }

        /* ---------- Topbar ---------- */
        .topbar { position: sticky; top: 0; z-index: 40; display: flex; align-items: center; gap: 18px; background: #fff; padding: 12px 28px; border-bottom: 1px solid var(--line); }
        .icon-btn { width: 40px; height: 40px; border-radius: 10px; border: 0; background: transparent; display: grid; place-items: center; cursor: pointer; color: #2d3a38; position: relative; }
        .icon-btn:hover { background: #f3efe6; }
        .icon-btn svg { width: 22px; height: 22px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .topbar-search { position: relative; flex: 1; max-width: 520px; margin: 0; }
        .topbar-search svg { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; fill: none; stroke: #6f6b64; stroke-width: 2; stroke-linecap: round; }
        .topbar-search input { width: 100%; padding: 11px 16px 11px 44px; border-radius: 999px; border: 0; background: #f1ede4; font: inherit; font-size: .92rem; }
        .topbar-search input:focus { outline: 2px solid rgba(20,108,67,.35); background: #fff; }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }
        .bell .dot { position: absolute; top: 8px; right: 9px; width: 9px; height: 9px; border-radius: 50%; background: #e53935; border: 2px solid #fff; }

        .user-menu { position: relative; }
        .user-menu summary { list-style: none; cursor: pointer; display: flex; align-items: center; gap: 10px; padding: 4px 10px 4px 4px; border-radius: 999px; font-weight: 500; font-size: .92rem; color: #2d3a38; user-select: none; }
        .user-menu summary:hover { background: #f3efe6; }
        .user-menu summary::-webkit-details-marker { display: none; }
        .user-menu .avatar { width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #14633f, #0B2F22); color: #F5C77E; display: grid; place-items: center; font-weight: 700; }
        .user-menu .chev { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round; stroke-linejoin: round; transition: transform .2s; }
        .user-menu[open] .chev { transform: rotate(180deg); }
        .user-dropdown { position: absolute; right: 0; top: calc(100% + 10px); width: 230px; background: #fff; border-radius: 14px; padding: 8px; box-shadow: 0 16px 40px rgba(20,33,26,.18); border: 1px solid var(--line); z-index: 50; }
        .user-dropdown .who { padding: 12px 12px 14px; border-bottom: 1px solid var(--line); margin-bottom: 6px; }
        .user-dropdown .who strong { display: block; color: var(--forest); }
        .user-dropdown .who span { color: var(--muted); font-size: .85rem; }
        .user-dropdown .logout-btn { width: 100%; text-align: left; background: none; border: 0; font: inherit; font-weight: 500; color: #a12626; padding: 10px 12px; border-radius: 10px; cursor: pointer; }
        .user-dropdown .logout-btn:hover { background: #fff0f0; }

        /* ---------- Page area ---------- */
        .page-content { padding: 26px 28px 48px; }
        .page-title { margin: 0 0 22px; font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; color: var(--forest); }

        /* ---------- Shared components (other admin pages) ---------- */
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

        /* ---------- Collapsed / mobile sidebar ---------- */
        body.sidebar-collapsed .admin-sidebar { margin-left: -272px; }
        @media (max-width: 900px) {
            .admin-sidebar { position: fixed; z-index: 100; left: 0; top: 0; margin-left: -290px; width: 272px; }
            body.sidebar-collapsed .admin-sidebar { margin-left: -290px; }
            body.sidebar-open .admin-sidebar { margin-left: 0; box-shadow: 0 0 0 100vmax rgba(0,0,0,.45); }
            .topbar { padding: 10px 14px; gap: 10px; }
            .page-content { padding: 18px 14px 40px; }
            .user-menu .name { display: none; }
            .page-title { font-size: 1.6rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
@php
    $navLink = function ($name, $fallback = '#') {
        return \Illuminate\Support\Facades\Route::has($name) ? route($name) : $fallback;
    };
@endphp
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="brand">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/wildlife-kingdom-header(1).png') }}" alt="Wildlife Kingdom">
            </a>
        </div>

        <nav class="side-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.animals.index') }}" class="{{ request()->routeIs('admin.animals.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="4" r="2"/><circle cx="18" cy="8" r="2"/><circle cx="20" cy="16" r="2"/><path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"/></svg>
                Animals
            </a>
            <a href="{{ route('admin.habitats.index') }}" class="{{ request()->routeIs('admin.habitats.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                Habitats
            </a>
            <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                Events
            </a>
            <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/></svg>
                Gallery
            </a>
            <a href="{{ $navLink('admin.pages.index') }}" class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><path d="M14 2v6h6"/><path d="M16 13H8M16 17H8M10 9H8"/></svg>
                Pages &amp; Content
            </a>
            <a href="{{ $navLink('admin.seo.index') }}" class="{{ request()->routeIs('admin.seo.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                SEO Settings
            </a>
            <a href="{{ $navLink('admin.api.index') }}" class="{{ request()->routeIs('admin.api.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="m16 18 6-6-6-6"/><path d="m8 6-6 6 6 6"/></svg>
                API Settings
            </a>
            <a href="{{ $navLink('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                Website Settings
            </a>
        </nav>

        <div class="side-divider"></div>

        <form action="{{ route('admin.logout') }}" method="POST" class="side-logout">
            @csrf
            <button type="submit">
                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>
                Logout
            </button>
        </form>

        <div class="side-footer">
            <svg class="scene" viewBox="0 0 272 210" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
                <path d="M0 150 Q60 120 120 145 T272 130 V210 H0Z" fill="#0c3324" opacity=".9"/>
                <path d="M0 175 Q70 150 140 172 T272 160 V210 H0Z" fill="#0a2a1e"/>
                <g fill="#0a2a1e" opacity=".95">
                    <rect x="38" y="120" width="4" height="55"/>
                    <path d="M10 124 Q40 96 70 124 Q40 112 10 124Z"/>
                    <rect x="214" y="110" width="4" height="62"/>
                    <path d="M184 114 Q216 84 250 114 Q216 100 184 114Z"/>
                </g>
                <g fill="#134733" opacity=".9">
                    <ellipse cx="120" cy="182" rx="22" ry="9"/>
                    <path d="M108 176 q-2-18 6-30 l3 2 q-6 12-4 26Z"/>
                    <path d="M112 146 l-8-10 M114 146 l-2-12 M116 146 l6-10" stroke="#134733" stroke-width="2" fill="none"/>
                    <circle cx="112" cy="146" r="4"/>
                </g>
            </svg>
            <div class="side-quote">
                Save Wildlife<br>Save Tomorrow
                <svg viewBox="0 0 24 24"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/></svg>
            </div>
        </div>
    </aside>

    <div class="admin-content">
        <header class="topbar">
            <button type="button" class="icon-btn" id="sidebarToggle" aria-label="Toggle menu">
                <svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h12M3 18h18"/></svg>
            </button>

            <form class="topbar-search" action="{{ route('admin.animals.index') }}" method="GET" role="search">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" name="q" placeholder="Search animals, habitats, events..." autocomplete="off">
            </form>

            <div class="topbar-right">
                <button type="button" class="icon-btn bell" aria-label="Notifications">
                    <svg viewBox="0 0 24 24"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    <span class="dot"></span>
                </button>

                <details class="user-menu" id="userMenu">
                    <summary>
                        <span class="avatar">R</span>
                        <span class="name">Rutuja Bhapkar</span>
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
        </header>

        @yield('hero')

        <main class="page-content">
            @hasSection('hero')
            @else
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            @endif

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
</div>

<script>
    (function () {
        var body = document.body;
        var toggle = document.getElementById('sidebarToggle');
        if (toggle) {
            toggle.addEventListener('click', function () {
                if (window.innerWidth <= 900) { body.classList.toggle('sidebar-open'); }
                else { body.classList.toggle('sidebar-collapsed'); }
            });
        }
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 900 && body.classList.contains('sidebar-open')
                && !e.target.closest('.admin-sidebar') && !e.target.closest('#sidebarToggle')) {
                body.classList.remove('sidebar-open');
            }
        });

        var menu = document.getElementById('userMenu');
        if (menu) {
            document.addEventListener('click', function (e) { if (!menu.contains(e.target)) menu.removeAttribute('open'); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') menu.removeAttribute('open'); });
        }
    })();
</script>
</body>
</html>