<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wildlife Kingdom')</title>

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
            <img src="{{ asset('assets/images/logo/wildlife-kingdom-header.png') }}" alt="Wildlife Kingdom">
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

        <a href="../../public_html/tickets.php" class="btn btn-gold header-cta">Book Tickets</a>

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
                <img src="{{ asset('assets/images/logo/wildlife-kingdom-header.png') }}" alt="Wildlife Kingdom">
            </a>
            <p class="footer-about">A sanctuary for wonder, conservation, and discovery. Meet incredible animals, explore living habitats and connect with the natural world — every visit supports the wildlife we protect.</p>
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
                <li><a href="../../public_html/tickets.php">Tickets</a></li>
                <li><a href="../../public_html/visit-us.php">Plan Your Visit</a></li>
                <li><a href="../../public_html/conservation.php">Conservation</a></li>
                <li><a href="../../public_html/contact.php">Contact</a></li>
            </ul>
        </div>

        <div class="footer-contact">
            <h4>Get in touch</h4>
            <p>123 Rainforest Road<br>Wildlife County</p>
            <p>hello@wildlifekingdom.com</p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} Wildlife Kingdom. All rights reserved.</p>
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
