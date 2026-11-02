<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteSettings->get('seo_site_title', $siteSettings->get('site_name', 'Wildlife Kingdom')))</title>
    @hasSection('meta-description')
        <meta name="description" content="@yield('meta-description')">
    @elseif($siteSettings->get('seo_site_description'))
        <meta name="description" content="{{ $siteSettings->get('seo_site_description') }}">
    @endif
    @hasSection('canonical')
        <link rel="canonical" href="@yield('canonical')">
    @elseif($siteSettings->get('seo_canonical_base_url'))
        <link rel="canonical" href="{{ rtrim($siteSettings->get('seo_canonical_base_url'), '/') }}/{{ ltrim(request()->getPathInfo(), '/') }}">
    @endif
    @hasSection('robots')
        <meta name="robots" content="@yield('robots')">
    @elseif($siteSettings->get('seo_default_noindex') || $siteSettings->get('seo_default_nofollow'))
        <meta name="robots" content="{{ $siteSettings->get('seo_default_noindex') ? 'noindex' : 'index' }}, {{ $siteSettings->get('seo_default_nofollow') ? 'nofollow' : 'follow' }}">
    @endif
    @hasSection('social-title')
        <meta property="og:title" content="@yield('social-title')">
        <meta property="og:description" content="@yield('meta-description')">
        <meta property="og:type" content="article">
        <meta property="og:url" content="@yield('canonical')">
    @endif
    @hasSection('social-image')
        <meta property="og:image" content="@yield('social-image')">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="@yield('social-image')">
    @endif
    @stack('structured-data')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header-navbar.css') }}">
    @yield('styles')
    <style>
        :root { --wk-nav-green: #0B2F22; }
        .site-header,
        .site-header.site-header--transparent,
        .site-header.is-scrolled { background: var(--wk-nav-green) !important; }
        .site-header .header-cta,
        .site-header .header-cta.btn-gold { background: #E8A15B !important; color: #000 !important; }
        .site-header .logo img { height: 84px; width: auto; margin: -10px 0; display: block; }
        @media (max-width: 900px) {
            .site-header .logo img { height: 56px; margin: -3px 0; }
        }
    </style>
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('assets/images/logo/wildlife-kingdom-header.png') }}" alt="{{ $siteSettings->get('site_name', 'Wildlife Kingdom') }}">
        </a>

        <nav class="main-nav" id="mainNav">
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="../../public_html/about.php">About Us</a></li>
                <li><a href="{{ route('animals') }}">Animals</a></li>
                <li><a href="{{ route('habitats') }}">Habitats</a></li>
                <li><a href="{{ route('events') }}">Events</a></li>
                <li><a href="{{ route('gallery') }}">Gallery</a></li>
                <li><a href="../../public_html/contact.php">Contact Us</a></li>
            </ul>
        </nav>

        <a href="{{ route('front.tickets.index') }}" class="btn btn-gold header-cta">Book Tickets</a>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="mainNav">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

@yield('content')

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="{{ route('home') }}" class="logo" aria-label="Wildlife Kingdom home">
                <img src="{{ asset('assets/images/logo/wildlife-kingdom-header.png') }}" alt="{{ $siteSettings->get('site_name', 'Wildlife Kingdom') }}">
            </a>
            <p class="footer-about">{{ $siteSettings->get('footer_about', 'A sanctuary for wonder, conservation, and discovery. Meet incredible animals, explore living habitats and connect with the natural world — every visit supports the wildlife we protect.') }}</p>
            @if($siteSettings->get('site_tagline'))<p class="footer-about">{{ $siteSettings->get('site_tagline') }}</p>@endif
        </div>

        <div class="footer-links">
            <h4>Explore</h4>
            <ul>
                <li><a href="{{ route('animals') }}">Animals</a></li>
                <li><a href="{{ route('habitats') }}">Habitats</a></li>
                <li><a href="{{ route('events') }}">Events</a></li>
                <li><a href="{{ route('gallery') }}">Gallery</a></li>
            </ul>
        </div>

        <div class="footer-links">
            <h4>Visit</h4>
            <ul>
                <li><a href="{{ route('front.tickets.index') }}">Tickets</a></li>
                <li><a href="../../public_html/visit-us.php">Plan Your Visit</a></li>
                <li><a href="../../public_html/conservation.php">Conservation</a></li>
                <li><a href="../../public_html/contact.php">Contact</a></li>
            </ul>
        </div>

        <div class="footer-contact">
            <h4>Get in touch</h4>
            <p>{!! nl2br(e($siteSettings->get('contact_address', "123 Rainforest Road\nWildlife County"))) !!}</p>
            <p>{{ $siteSettings->get('contact_email', 'hello@wildlifekingdom.com') }}</p>
            @if($siteSettings->get('contact_phone'))<p>{{ $siteSettings->get('contact_phone') }}</p>@endif
            @if($siteSettings->get('opening_hours'))<p>{!! nl2br(e($siteSettings->get('opening_hours'))) !!}</p>@endif
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} {{ $siteSettings->get('site_name', 'Wildlife Kingdom') }}. All rights reserved.</p>
    </div>
</footer>

<script src="{{ asset('assets/js/main.js') }}"></script>
<script>
    (function () {
        var toggle = document.getElementById('navToggle');
        var nav = document.getElementById('mainNav');
        if (!toggle || !nav) return;
        toggle.addEventListener('click', function () {
            nav.classList.toggle('is-open');
            toggle.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', nav.classList.contains('is-open') ? 'true' : 'false');
        });
    })();
</script>
</body>
</html>
