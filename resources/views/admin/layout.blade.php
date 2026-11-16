<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('site.admin.panel')) - {{ __('site.admin.panel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-brand">
                <img src="{{ asset('images/logo.png') }}" alt="RCB">
                <div>
                    <span class="admin-brand-name">RCB Dashboard</span>
                    <span class="admin-brand-sub">{{ __('site.admin.panel') }}</span>
                </div>
            </div>
            <nav class="admin-nav">
                <span class="admin-nav-label">{{ __('site.admin.group_main') }}</span>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="admin-ico">📊</span> {{ __('site.admin.dashboard') }}
                </a>

                <span class="admin-nav-label">{{ __('site.admin.group_cricket') }}</span>
                <a href="{{ route('admin.players.index') }}" class="{{ request()->routeIs('admin.players.*') ? 'active' : '' }}">
                    <span class="admin-ico">🏏</span> {{ __('site.admin.players') }}
                </a>
                <a href="{{ route('admin.matches.index') }}" class="{{ request()->routeIs('admin.matches.*') ? 'active' : '' }}">
                    <span class="admin-ico">🗓️</span> {{ __('site.admin.matches') }}
                </a>
                <a href="{{ route('pages.teams') }}" target="_blank">
                    <span class="admin-ico">👕</span> {{ __('site.admin.total_teams') }}
                    <span class="admin-nav-ext">↗</span>
                </a>
                <a href="{{ route('pages.records') }}" target="_blank">
                    <span class="admin-ico">🏆</span> {{ __('site.nav.records') }}
                    <span class="admin-nav-ext">↗</span>
                </a>

                <span class="admin-nav-label">{{ __('site.admin.group_content') }}</span>
                <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                    <span class="admin-ico">📰</span> {{ __('site.admin.news') }}
                </a>
                <a href="{{ route('pages.gallery') }}" target="_blank">
                    <span class="admin-ico">🖼️</span> {{ __('site.nav.gallery') }}
                    <span class="admin-nav-ext">↗</span>
                </a>
                <a href="{{ route('pages.about') }}" target="_blank">
                    <span class="admin-ico">ℹ️</span> {{ __('site.nav.about') }}
                    <span class="admin-nav-ext">↗</span>
                </a>
                <a href="{{ route('pages.contact') }}" target="_blank">
                    <span class="admin-ico">✉️</span> {{ __('site.nav.contact') }}
                    <span class="admin-nav-ext">↗</span>
                </a>

                <span class="admin-nav-label">{{ __('site.admin.group_static') }}</span>
                <a href="{{ route('admin.applications.index') }}" class="{{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                    <span class="admin-ico">📝</span> {{ __('site.admin.applications') }}
                    @if ($applicationsCount)
                        <span class="admin-nav-badge">{{ $applicationsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.sponsors.index') }}" class="{{ request()->routeIs('admin.sponsors.*') ? 'active' : '' }}">
                    <span class="admin-ico">🤝</span> {{ __('site.admin.sponsors') }}
                </a>
            </nav>
            <div class="admin-sidebar-footer">
                <a href="{{ route('home') }}" target="_blank"><span class="admin-ico">🌐</span> {{ __('site.admin.view_site') }}</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"><span class="admin-ico">🚪</span> {{ __('site.admin.logout') }}</button>
                </form>
            </div>
        </aside>
        <div class="admin-sidebar-backdrop" id="adminBackdrop"></div>

        <div class="admin-wrapper">
            <header class="admin-header">
                <div class="admin-header-left">
                    <button class="admin-burger" id="adminBurger" aria-label="Menu">&#9776;</button>
                    <div class="admin-header-title">
                        <h1>@yield('title', __('site.admin.dashboard'))</h1>
                        <p>{{ __('site.admin.subtitle') }}</p>
                    </div>
                </div>
                <div class="admin-header-right">
                    <div class="admin-visitor-chips">
                        <div class="admin-visitor-chip">
                            <span class="admin-visitor-ico">👥</span>
                            <div>
                                <strong>{{ number_format($visitorsToday) }}</strong>
                                <small>{{ __('site.admin.visitors_today') }}</small>
                            </div>
                        </div>
                        <div class="admin-visitor-chip alt">
                            <span class="admin-visitor-ico">🌐</span>
                            <div>
                                <strong>{{ number_format($visitorsTotal) }}</strong>
                                <small>{{ __('site.admin.visitors_total') }}</small>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.applications.index') }}" class="admin-header-ico" title="{{ __('site.admin.applications') }}">
                        📝
                        @if ($applicationsCount)
                            <span class="admin-header-badge">{{ $applicationsCount }}</span>
                        @endif
                    </a>
                    <div class="lang-switch">
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">ENG</a>
                        <a href="{{ request()->fullUrlWithQuery(['lang' => 'bn']) }}" class="{{ app()->getLocale() === 'bn' ? 'active' : '' }}">BAN</a>
                    </div>
                    <div class="admin-avatar" title="{{ __('site.admin.panel') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="RCB">
                    </div>
                </div>
            </header>

            <main class="admin-main">
                @if (session('success'))
                    <div class="admin-flash">✅ {{ session('success') }}</div>
                @endif
                <div class="admin-content">
                    @yield('content')
                </div>
            </main>

            <footer class="admin-footer">
                <span>© {{ date('Y') }} {{ __('site.admin.footer_text') }}</span>
                <span class="admin-footer-dot">•</span>
                <a href="{{ route('home') }}" target="_blank">{{ __('site.admin.view_site') }}</a>
            </footer>
        </div>
    </div>
    <script>
        (function () {
            var burger = document.getElementById('adminBurger');
            var sidebar = document.getElementById('adminSidebar');
            var backdrop = document.getElementById('adminBackdrop');
            if (burger && sidebar) {
                burger.addEventListener('click', function () {
                    sidebar.classList.toggle('open');
                    if (backdrop) backdrop.classList.toggle('show');
                });
            }
            if (backdrop) {
                backdrop.addEventListener('click', function () {
                    sidebar.classList.remove('open');
                    backdrop.classList.remove('show');
                });
            }
        })();
    </script>
</body>
</html>
