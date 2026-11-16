<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Real Challenger Boys') - Real Challenger Boys</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
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
            <button class="menu-toggle" id="menuToggle" aria-label="Menu">&#9776;</button>

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
                <div class="live-results" id="liveResults"></div>
            </div>

            <div class="lang-switch header-lang">
                <a href="#" class="active">ENG</a>
                <a href="#">BAN</a>
            </div>

            <button class="search-toggle" id="searchToggle" aria-label="Search">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/></svg>
            </button>

            <a href="{{ route('be-a-player.create') }}" class="btn-cta">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.105 18.79l-1 .992a4.159 4.159 0 0 1 -6.038 -5.715l.157 -.166l8.282 -8.401l1.5 1.5l3.45 -3.391a2.08 2.08 0 0 1 3.057 2.815l-.116 .126l-3.391 3.45l1.5 1.5l-3.668 3.617"/><path d="M10.5 7.5l6 6"/><path d="M14 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/></svg>
                BE A PLAYER
            </a>
        </div>
    </div>

    <div class="mobile-search" id="mobileSearch">
        <form action="{{ route('search') }}" method="GET" class="search-form" role="search">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search players, teams, matches..." aria-label="Search">
            <button type="submit" aria-label="Search">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/></svg>
            </button>
        </form>
        <div class="live-results" id="mobileLiveResults"></div>
    </div>

    <!-- ==================== Navbar (sticky teal) ==================== -->
    <div class="mainnav" id="mainnav">
        <div class="container">
            <nav class="nav">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('pages.about') }}" class="nav-link {{ request()->routeIs('pages.about') ? 'active' : '' }}">About</a>
                <a href="{{ route('pages.gallery') }}" class="nav-link {{ request()->routeIs('pages.gallery') ? 'active' : '' }}">Gallery</a>
                <a href="{{ route('news.index') }}" class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">News</a>
                <a href="{{ route('matches.index') }}" class="nav-link {{ request()->routeIs('matches.*') ? 'active' : '' }}">Fixtures</a>
                <a href="{{ route('players.index') }}" class="nav-link {{ request()->routeIs('players.*') ? 'active' : '' }}">Players</a>
                <a href="{{ route('pages.teams') }}" class="nav-link {{ request()->routeIs('pages.teams') ? 'active' : '' }}">Team</a>
                <a href="{{ route('pages.records') }}" class="nav-link {{ request()->routeIs('pages.records') ? 'active' : '' }}">Records</a>
                <a href="{{ route('pages.contact') }}" class="nav-link {{ request()->routeIs('pages.contact') ? 'active' : '' }}">Contact Us</a>
                <a href="{{ route('be-a-player.create') }}" class="nav-cta-mobile">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.105 18.79l-1 .992a4.159 4.159 0 0 1 -6.038 -5.715l.157 -.166l8.282 -8.401l1.5 1.5l3.45 -3.391a2.08 2.08 0 0 1 3.057 2.815l-.116 .126l-3.391 3.45l1.5 1.5l-3.668 3.617"/><path d="M10.5 7.5l6 6"/><path d="M14 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/></svg>
                    BE A PLAYER
                </a>
            </nav>
        </div>
    </div>

    <main class="site-main">
        @yield('content')
    </main>

    <!-- ==================== Footer ==================== -->
    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <h3>Support</h3>
                <div class="support-list">
                    <a href="tel:01674297522" class="support-pill">
                        <span class="support-icon">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M2 3a1 1 0 011-1h3.28a1 1 0 01.95.68l1.52 4.55a1 1 0 01-.27 1.03l-2.1 2.1a16 16 0 006.95 6.95l2.1-2.1a1 1 0 011.03-.27l4.55 1.52a1 1 0 01.68.95V21a1 1 0 01-1 1H19c-9.39 0-17-7.61-17-17V4a1 1 0 011-1z"/></svg>
                        </span>
                        <span>01674297522</span>
                    </a>
                    <a href="mailto:info@rcb.com.bd" class="support-pill">
                        <span class="support-icon">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18a2 2 0 002 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </span>
                        <span>info@rcb.com.bd</span>
                    </a>
                </div>
            </div>
            <div>
                <h3>Quick Link</h3>
                <ul class="quick-links">
                    <li><a href="{{ route('pages.about') }}">» About</a></li>
                    <li><a href="{{ route('pages.teams') }}">» Team</a></li>
                    <li><a href="{{ route('matches.index') }}">» Fixtures</a></li>
                    <li><a href="{{ route('pages.gallery') }}">» Gallery</a></li>
                    <li><a href="{{ route('pages.records') }}">» Records</a></li>
                </ul>
            </div>
            <div>
                <h3>Facebook Page</h3>
                <div class="fb-wrap">
                    <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Frealchallengerboys&tabs=timeline&width=340&height=250&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=776730922422337" height="250" class="fb-iframe" title="RCB Facebook Page" loading="lazy"></iframe>
                </div>
            </div>
            <div>
                <h3>Contact Us</h3>
                <ul class="contact-list">
                    <li>
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/></svg>
                        <span>Location: East Shewrapara, Begum Rokeya Sarani, Mirpur, Dhaka - 1216; Bangladesh</span>
                    </li>
                    <li>
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18a2 2 0 002 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        <a href="mailto:info@rcb.com.bd">info@rcb.com.bd</a>
                    </li>
                    <li>
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M2 3h4l2 5-2 2c1 3 4 6 7 7l2-2 5 2v4c-9 0-18-9-18-18z"/></svg>
                        <a href="tel:01674297522">01674297522</a>
                    </li>
                </ul>
                <div class="footer-socials">
                    <a href="https://www.facebook.com/realchallengerboys" target="_blank" rel="noopener" class="social-circle fb" aria-label="Facebook">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 5 3.66 9.13 8.44 9.88v-6.99H8.08V12h2.36V9.79c0-2.33 1.39-3.62 3.52-3.62.99 0 2.02.18 2.02.18v2.22h-1.14c-1.12 0-1.47.7-1.47 1.42V12h2.5l-.4 2.89h-2.1v6.99C18.34 21.13 22 17 22 12z"/></svg>
                    </a>
                    <a href="https://x.com/rcb_2004" target="_blank" rel="noopener" class="social-circle tw" aria-label="Twitter">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.25 4.25 0 001.88-2.34 8.52 8.52 0 01-2.7 1.03 4.24 4.24 0 00-7.3 3.87A12.05 12.05 0 013 4.8a4.25 4.25 0 001.31 5.66c-.64-.02-1.25-.2-1.77-.49v.05a4.25 4.25 0 003.4 4.16c-.48.13-.99.16-1.51.06a4.25 4.25 0 003.97 2.95A8.52 8.52 0 012 19.54a12.05 12.05 0 006.56 1.92c7.88 0 12.2-6.53 12.2-12.2 0-.19 0-.39-.01-.58A8.67 8.67 0 0022.46 6z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/rcb.com.bd" target="_blank" rel="noopener" class="social-circle ig" aria-label="Instagram">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M7 2C4.24 2 2 4.24 2 7v10c0 2.76 2.24 5 5 5h10c2.77 0 5-2.24 5-5V7c0-2.76-2.23-5-5-5H7zm10 2c1.66 0 3 1.34 3 3v10c0 1.66-1.34 3-3 3H7c-1.65 0-3-1.34-3-3V7c0-1.66 1.35-3 3-3h10zm-5 3a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm4.5-.1a1.1 1.1 0 100 2.2 1.1 1.1 0 000-2.2z"/></svg>
                    </a>
                    <a href="https://www.youtube.com/@realchallengerboys" target="_blank" rel="noopener" class="social-circle yt" aria-label="YouTube">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a2.994 2.994 0 0 0-2.112-2.12C19.3 3.5 12 3.5 12 3.5s-7.3 0-9.386.566a2.994 2.994 0 0 0-2.112 2.12C0 8.3 0 12 0 12s0 3.7.502 5.814a2.994 2.994 0 0 0 2.112 2.12C4.7 20.5 12 20.5 12 20.5s7.3 0 9.386-.566a2.994 2.994 0 0 0 2.112-2.12C24 15.7 24 12 24 12s0-3.7-.502-5.814zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; {{ date('Y') }} <strong>REAL CHALLENGER BOYS (RCB)</strong>. All Rights Reserved
                <span class="footer-dot">•</span>
                <span>Designed by: <a href="https://rcb.com.bd/" target="_blank" rel="noopener" class="footer-credit">RCB</a></span></p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp button -->
    <a href="https://wa.link/c1y139" target="_blank" rel="noopener" class="float-btn whatsapp-btn" aria-label="WhatsApp">
        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M16.72 14.47c-.27-.13-1.59-.78-1.84-.87-.25-.1-.43-.13-.62.13-.18.27-.72.87-.88 1.05-.16.18-.32.2-.59.07-.27-.13-1.13-.42-2.15-1.35-.79-.71-1.32-1.58-1.48-1.85-.15-.27-.02-.41.11-.54.12-.12.27-.32.41-.48.14-.16.18-.27.27-.45.09-.18.05-.34-.02-.47-.07-.13-.62-1.5-.85-2.05-.22-.53-.45-.46-.62-.47h-.53c-.18 0-.48.07-.73.34s-.95.93-.95 2.27 1 2.64 1.14 2.82c.14.18 1.96 3 4.75 4.2.66.29 1.18.46 1.58.59.66.21 1.26.18 1.74.11.53-.08 1.59-.65 1.81-1.28.22-.63.22-1.18.15-1.28-.06-.1-.25-.16-.52-.29z"/><path d="M20.52 3.48A11.94 11.94 0 0012 0a12 12 0 00-10.6 17.4L0 24l6.73-1.75A11.97 11.97 0 0012 24c6.63 0 12-5.37 12-12 0-3.2-1.24-6.21-3.48-8.52zM12 22a9.94 9.94 0 01-5.06-1.39l-.36-.21-4 .99 1.05-3.9-.25-.4A9.94 9.94 0 1112 22z"/></svg>
    </a>

    <!-- Scroll to top button -->
    <button id="backToTop" class="float-btn backtop-btn hidden" aria-label="Scroll to top">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"/></svg>
    </button>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
