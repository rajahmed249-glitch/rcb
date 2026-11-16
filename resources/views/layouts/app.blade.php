<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Welcome to RCB') - REAL CHALLENGER BOYS</title>
    <meta name="description" content="Real Challenger Boys (RCB) - Official cricket team website.">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="container header-top">
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-logo">RCB</span>
                <span class="brand-name">REAL CHALLENGER BOYS</span>
            </a>

            <div class="header-actions">
                <div class="lang-switch" aria-label="Language">
                    <span class="active">ENG</span>
                    <span>BAN</span>
                </div>
                <form action="{{ route('search') }}" method="GET" class="search-form" role="search">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search players, teams, matches..." aria-label="Search">
                    <button type="submit" aria-label="Search">🔍</button>
                </form>
                <a href="{{ route('be-a-player.create') }}" class="btn btn-primary">BE A PLAYER</a>
                <button class="menu-toggle" id="menuToggle" aria-label="Menu">☰</button>
            </div>
        </div>

        <div class="mainnav">
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
    </header>

    <main class="site-main">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <h4>Support</h4>
                <ul class="footer-links">
                    <li><a href="tel:01674297522">📞 01674297522</a></li>
                    <li><a href="mailto:info@rcb.com.bd">✉️ info@rcb.com.bd</a></li>
                </ul>
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
