<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', __('site.admin.dashboard')) - {{ __('site.admin.panel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="admin-body">
    <div class="admin-shell" id="adminShell">
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-brand">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="RCB">
                    <div>
                        <span class="admin-brand-name">RCB <span>{{ __('site.admin.dashboard') }}</span></span>
                        <span class="admin-brand-sub">{{ __('site.admin.panel') }}</span>
                    </div>
                </a>
                <button class="admin-close" onclick="closeSidebar()" aria-label="Close">&times;</button>
            </div>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="admin-ico"><i class="fas fa-tachometer-alt"></i></span> {{ __('site.admin.dashboard') }}
                </a>

                <span class="admin-nav-label">{{ __('site.admin.group_cricket') }}</span>
                <a href="{{ route('admin.players.index') }}" class="{{ request()->routeIs('admin.players.*') ? 'active' : '' }}">
                    <span class="admin-ico"><i class="fas fa-user-circle"></i></span> {{ __('site.admin.player') }}
                </a>
                <a href="{{ route('admin.teams.index') }}" class="{{ request()->routeIs('admin.teams.*') ? 'active' : '' }}">
                    <span class="admin-ico"><i class="fas fa-shield-alt"></i></span> {{ __('site.admin.team') }}
                </a>
                <a href="{{ route('admin.tournaments.index') }}" class="{{ request()->routeIs('admin.tournaments.*') ? 'active' : '' }}">
                    <span class="admin-ico"><i class="fas fa-trophy"></i></span> {{ __('site.admin.tournament') }}
                </a>
                <a href="{{ route('admin.matches.index') }}" class="{{ request()->routeIs('admin.matches.*') ? 'active' : '' }}">
                    <span class="admin-ico"><i class="fas fa-calendar-alt"></i></span> {{ __('site.admin.match') }}
                </a>
                <a href="{{ route('admin.squads.index') }}" class="{{ request()->routeIs('admin.squads.*') ? 'active' : '' }}">
                    <span class="admin-ico"><i class="fas fa-users-viewfinder"></i></span> {{ __('site.admin.squad') }}
                </a>
                <a href="{{ route('admin.scoreboards.index') }}" class="{{ request()->routeIs('admin.scoreboards.*') ? 'active' : '' }}">
                    <span class="admin-ico"><i class="fas fa-chart-line"></i></span> {{ __('site.admin.scoreboard') }}
                </a>
                <a href="{{ route('pages.records') }}" target="_blank">
                    <span class="admin-ico"><i class="fas fa-trophy"></i></span> {{ __('site.nav.records') }}
                    <span class="admin-nav-ext">&#8599;</span>
                </a>

                <span class="admin-nav-label">{{ __('site.admin.group_content') }}</span>
                <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                    <span class="admin-ico"><i class="fas fa-newspaper"></i></span> {{ __('site.admin.news') }}
                </a>
                <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                    <span class="admin-ico"><i class="fas fa-images"></i></span> {{ __('site.admin.gallery') }}
                </a>
                <a href="{{ route('pages.about') }}" target="_blank">
                    <span class="admin-ico"><i class="fas fa-info-circle"></i></span> {{ __('site.nav.about') }}
                    <span class="admin-nav-ext">&#8599;</span>
                </a>
                <a href="{{ route('pages.contact') }}" target="_blank">
                    <span class="admin-ico"><i class="fas fa-envelope"></i></span> {{ __('site.nav.contact') }}
                    <span class="admin-nav-ext">&#8599;</span>
                </a>

                <span class="admin-nav-label">{{ __('site.admin.group_static') }}</span>
                <a href="{{ route('admin.applications.index') }}" class="{{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                    <span class="admin-ico"><i class="fas fa-user-plus"></i></span> {{ __('site.admin.applications') }}
                    @if ($applicationsCount)
                        <span class="admin-nav-badge">{{ $applicationsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.sponsors.index') }}" class="{{ request()->routeIs('admin.sponsors.*') ? 'active' : '' }}">
                    <span class="admin-ico"><i class="fas fa-handshake"></i></span> {{ __('site.admin.sponsors') }}
                </a>

                <span class="admin-nav-label">{{ __('site.admin.settings') }}</span>
                <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <span class="admin-ico"><i class="fas fa-cog"></i></span> {{ __('site.admin.settings') }}
                </a>
            </nav>
            <div class="admin-sidebar-footer">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"><span class="admin-ico"><i class="fas fa-sign-out-alt"></i></span> {{ __('site.admin.logout') }}</button>
                </form>
            </div>
        </aside>
        <div class="admin-sidebar-backdrop" id="adminBackdrop" onclick="closeSidebar()"></div>

        <div class="admin-wrapper">
            <header class="admin-topbar" id="adminTopbar">
                <div class="admin-topbar-left">
                    <button class="admin-burger" id="adminBurger" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="admin-topbar-title">@yield('title', __('site.admin.dashboard'))</span>
                </div>

                <div class="admin-topbar-stats">
                    <div class="stat-badge" title="{{ __('site.admin.visitors_today') }}">
                        <span class="admin-ico"><i class="fas fa-user"></i></span>
                        <span>{{ __('site.admin.today') }}</span>
                        <strong>{{ number_format($visitorsToday) }}</strong>
                    </div>
                    <div class="stat-badge" title="{{ __('site.admin.visitors_total') }}">
                        <span class="admin-ico"><i class="fas fa-users"></i></span>
                        <span>{{ __('site.admin.total') }}</span>
                        <strong>{{ number_format($visitorsTotal) }}</strong>
                    </div>
                </div>

                <div class="admin-topbar-right">
                    <a href="{{ route('home') }}" target="_blank" class="visit-btn">
                        <i class="fas fa-globe"></i>
                        <span>{{ __('site.admin.visit_site') }}</span>
                    </a>
                    <div class="lang-switch" style="display:flex;gap:2px;background:rgba(255,255,255,.15);border-radius:30px;padding:3px;">
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" style="color:#fff;text-decoration:none;font-size:11px;font-weight:700;padding:3px 10px;border-radius:30px;{{ app()->getLocale() === 'en' ? 'background:rgba(255,255,255,.3);' : '' }}">ENG</a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'bn']) }}" style="color:#fff;text-decoration:none;font-size:11px;font-weight:700;padding:3px 10px;border-radius:30px;{{ app()->getLocale() === 'bn' ? 'background:rgba(255,255,255,.3);' : '' }}">BAN</a>
                    </div>
                    <div class="admin-profile" id="adminProfile">
                        <span class="admin-profile-name">Admin</span>
                        <div class="admin-avatar">A</div>
                        <div class="profile-dropdown">
                            <div class="pd-head">
                                <div class="pd-name">Admin</div>
                                <div class="pd-role">Administrator</div>
                            </div>
                            <a href="{{ route('admin.settings.edit') }}"><span class="admin-ico"><i class="fas fa-cog"></i></span> {{ __('site.admin.settings') }}</a>
                            <a href="{{ route('admin.logout') }}" class="logout-link" onclick="event.preventDefault(); document.getElementById('logoutFormTop').submit();"><span class="admin-ico"><i class="fas fa-sign-out-alt"></i></span> {{ __('site.admin.logout') }}</a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="admin-main">
                @if (session('success'))
                    <div class="admin-flash"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                @endif
                <div class="admin-content">
                    @yield('content')
                </div>
            </main>

            <footer class="admin-footer">
                <span>&copy; {{ date('Y') }} {{ __('site.admin.footer_text') }}</span>
                <span>&bull;</span>
                <a href="{{ route('home') }}" target="_blank">{{ __('site.admin.view_site') }}</a>
            </footer>
        </div>
    </div>
    <form method="POST" action="{{ route('admin.logout') }}" id="logoutFormTop" style="display:none;">@csrf</form>
    <script>
        function toggleSidebar() {
            var shell = document.getElementById('adminShell');
            var sidebar = document.getElementById('adminSidebar');
            var backdrop = document.getElementById('adminBackdrop');
            var burger = document.getElementById('adminBurger');
            var isMobile = window.innerWidth <= 1024;
            if (isMobile) {
                sidebar.classList.toggle('open');
                backdrop.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
            } else {
                shell.classList.toggle('collapsed');
                if (burger) burger.classList.toggle('active');
                localStorage.setItem('sidebarCollapsed', shell.classList.contains('collapsed') ? 'true' : 'false');
            }
        }
        function closeSidebar() {
            var sidebar = document.getElementById('adminSidebar');
            var backdrop = document.getElementById('adminBackdrop');
            var burger = document.getElementById('adminBurger');
            sidebar.classList.remove('open');
            backdrop.classList.remove('show');
            if (burger) burger.classList.remove('active');
            document.body.style.overflow = '';
        }
        (function () {
            var shell = document.getElementById('adminShell');
            var sidebar = document.getElementById('adminSidebar');
            var burger = document.getElementById('adminBurger');
            if (window.innerWidth > 1024 && shell && localStorage.getItem('sidebarCollapsed') === 'true') {
                shell.classList.add('collapsed');
                if (burger) burger.classList.add('active');
            }
            document.addEventListener('click', function (e) {
                if (window.innerWidth <= 1024 && sidebar && sidebar.classList.contains('open')) {
                    if (!sidebar.contains(e.target) && !(burger && burger.contains(e.target))) closeSidebar();
                }
            });
            window.addEventListener('resize', function () {
                if (window.innerWidth <= 1024) {
                    shell.classList.remove('collapsed');
                    closeSidebar();
                }
            });
        })();
    </script>
</body>
</html>
