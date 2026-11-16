<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Real Challenger Boys') - Real Challenger Boys</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta name="description" content="Real Challenger Boys (RCB) - Official cricket team website. Play with passion, win with honor.">
    <meta name="keywords" content="cricket, rcb, real challenger boys, sports, dhaka cricket">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- ==================== Topbar ==================== -->
    <div class="topbar">
        <div class="container topbar-inner">
            <div class="topbar-contact">
                <a href="tel:01674297522">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M2 3a1 1 0 011-1h3.28a1 1 0 01.95.68l1.52 4.55a1 1 0 01-.27 1.03l-2.1 2.1a16 16 0 006.95 6.95l2.1-2.1a1 1 0 011.03-.27l4.55 1.52a1 1 0 01.68.95V21a1 1 0 01-1 1H19c-9.39 0-17-7.61-17-17V4a1 1 0 011-1z"/></svg>
                    <span>01674297522</span>
                </a>
                <span class="topbar-divider"></span>
                <a href="mailto:info@rcb.com.bd">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18a2 2 0 002 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    <span>info@rcb.com.bd</span>
                </a>
            </div>

            <div class="topbar-name">Real Challenger Boys</div>

            <div class="topbar-right">
                <div class="lang-switch">
                    <a href="#" class="active">ENG</a>
                    <a href="#">BAN</a>
                </div>
                <div class="socials">
                    <a href="https://www.facebook.com/realchallengerboys" target="_blank" rel="noopener" class="social-fb" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2v-3h2v-2.3c0-2 1.2-3.1 3-3.1.9 0 1.8.2 1.8.2v2h-1c-1 0-1.3.6-1.3 1.2V12h2.6l-.4 3h-2.2v7A10 10 0 0 0 22 12z"/></svg>
                    </a>
                    <a href="https://x.com/rcb_2004" target="_blank" rel="noopener" class="social-tw" aria-label="Twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.3 4.3 0 0 0 1.88-2.37 8.59 8.59 0 0 1-2.72 1.04 4.28 4.28 0 0 0-7.29 3.9A12.14 12.14 0 0 1 3.16 4.9a4.28 4.28 0 0 0 1.33 5.7A4.24 4.24 0 0 1 2.8 10v.05a4.28 4.28 0 0 0 3.44 4.19 4.28 4.28 0 0 1-1.92.07 4.28 4.28 0 0 0 4 2.97A8.58 8.58 0 0 1 2 19.54 12.09 12.09 0 0 0 8.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18-.01-.36-.02-.54A8.36 8.36 0 0 0 22.46 6z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/rcb.com.bd" target="_blank" rel="noopener" class="social-ig" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2C4.2 2 2 4.2 2 7v10c0 2.8 2.2 5 5 5h10c2.8 0 5-2.2 5-5V7c0-2.8-2.2-5-5-5H7zm10 2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h10zm-5 3.3A4.7 4.7 0 1 0 16.7 12 4.7 4.7 0 0 0 12 7.3zm0 7.7A3 3 0 1 1 15 12a3 3 0 0 1-3 3zm4.8-7.9a1.1 1.1 0 1 1-1.1-1.1c.6 0 1.1.5 1.1 1.1z"/></svg>
                    </a>
                    <a href="https://www.youtube.com/@realchallengerboys" target="_blank" rel="noopener" class="social-yt" aria-label="YouTube">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M10 15l5.19-3L10 9v6zm12-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== Header (logo + search + CTA) ==================== -->
    <div class="header-row">
        <div class="container header-main">
            <a href="{{ route('home') }}" class="brand">
                <img src="{{ asset('images/logo.png') }}" alt="Real Challenger Boys" class="brand-logo-img">
            </a>

            <div class="search-wrap">
                <form action="{{ route('search') }}" method="GET" class="search-form" role="search">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search players..." aria-label="Search">
                    <button type="submit" aria-label="Search">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/></svg>
                    </button>
                </form>
            </div>

            <a href="{{ route('be-a-player.create') }}" class="btn-cta">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.105 18.79l-1 .992a4.159 4.159 0 0 1 -6.038 -5.715l.157 -.166l8.282 -8.401l1.5 1.5l3.45 -3.391a2.08 2.08 0 0 1 3.057 2.815l-.116 .126l-3.391 3.45l1.5 1.5l-3.668 3.617"/><path d="M10.5 7.5l6 6"/><path d="M14 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/></svg>
                BE A PLAYER
            </a>
            <button class="menu-toggle" id="menuToggle" aria-label="Menu">&#9776;</button>
        </div>
    </div>

    <!-- ==================== Navbar (sticky teal) ==================== -->
    <div class="mainnav" id="mainnav">
        <div class="container">
            <nav class="nav">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('matches.index') }}" class="nav-link {{ request()->routeIs('matches.*') ? 'active' : '' }}">Fixtures</a>
                <a href="{{ route('players.index') }}" class="nav-link {{ request()->routeIs('players.*') ? 'active' : '' }}">Players</a>
                <a href="{{ route('news.index') }}" class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">News</a>
                <a href="{{ route('sponsors.index') }}" class="nav-link {{ request()->routeIs('sponsors.*') ? 'active' : '' }}">Sponsors</a>
            </nav>
        </div>
    </div>

    <main class="site-main">
        @yield('content')
    </main>

    <!-- ==================== Footer ==================== -->
    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <div class="brand">
                    <img src="{{ asset('images/logo.png') }}" alt="Real Challenger Boys" class="brand-logo-img">
                </div>
                <p>Real Challenger Boys (RCB) — Official cricket team website. Play with passion, win with honor. 🏏</p>
                <div class="socials">
                    <a href="https://www.facebook.com/realchallengerboys" target="_blank" rel="noopener" class="social-fb" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2v-3h2v-2.3c0-2 1.2-3.1 3-3.1.9 0 1.8.2 1.8.2v2h-1c-1 0-1.3.6-1.3 1.2V12h2.6l-.4 3h-2.2v7A10 10 0 0 0 22 12z"/></svg>
                    </a>
                    <a href="https://x.com/rcb_2004" target="_blank" rel="noopener" class="social-tw" aria-label="Twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.3 4.3 0 0 0 1.88-2.37 8.59 8.59 0 0 1-2.72 1.04 4.28 4.28 0 0 0-7.29 3.9A12.14 12.14 0 0 1 3.16 4.9a4.28 4.28 0 0 0 1.33 5.7A4.24 4.24 0 0 1 2.8 10v.05a4.28 4.28 0 0 0 3.44 4.19 4.28 4.28 0 0 1-1.92.07 4.28 4.28 0 0 0 4 2.97A8.58 8.58 0 0 1 2 19.54 12.09 12.09 0 0 0 8.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18-.01-.36-.02-.54A8.36 8.36 0 0 0 22.46 6z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/rcb.com.bd" target="_blank" rel="noopener" class="social-ig" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2C4.2 2 2 4.2 2 7v10c0 2.8 2.2 5 5 5h10c2.8 0 5-2.2 5-5V7c0-2.8-2.2-5-5-5H7zm10 2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h10zm-5 3.3A4.7 4.7 0 1 0 16.7 12 4.7 4.7 0 0 0 12 7.3zm0 7.7A3 3 0 1 1 15 12a3 3 0 0 1-3 3zm4.8-7.9a1.1 1.1 0 1 1-1.1-1.1c.6 0 1.1.5 1.1 1.1z"/></svg>
                    </a>
                    <a href="https://www.youtube.com/@realchallengerboys" target="_blank" rel="noopener" class="social-yt" aria-label="YouTube">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M10 15l5.19-3L10 9v6zm12-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                    </a>
                </div>
            </div>
            <div>
                <h4>Quick Link</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('matches.index') }}">Fixtures</a></li>
                    <li><a href="{{ route('players.index') }}">Team</a></li>
                    <li><a href="{{ route('news.index') }}">News</a></li>
                    <li><a href="{{ route('be-a-player.create') }}">Be a Player</a></li>
                </ul>
            </div>
            <div>
                <h4>Contact Us</h4>
                <ul class="footer-links">
                    <li>📍 East Shewrapara, Begum Rokeya Sarani, Mirpur, Dhaka - 1216, Bangladesh</li>
                    <li><a href="tel:01674297522">📞 01674297522</a></li>
                    <li><a href="mailto:info@rcb.com.bd">✉️ info@rcb.com.bd</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; {{ date('Y') }} REAL CHALLENGER BOYS (RCB). All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
