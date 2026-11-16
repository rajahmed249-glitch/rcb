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
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                <img src="{{ asset('images/logo.png') }}" alt="RCB">
                <span>{{ __('site.admin.panel') }}</span>
            </a>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 {{ __('site.admin.dashboard') }}</a>
                <a href="{{ route('admin.players.index') }}" class="{{ request()->routeIs('admin.players.*') ? 'active' : '' }}">🏏 {{ __('site.admin.players') }}</a>
                <a href="{{ route('admin.matches.index') }}" class="{{ request()->routeIs('admin.matches.*') ? 'active' : '' }}">🗓️ {{ __('site.admin.matches') }}</a>
                <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}">📰 {{ __('site.admin.news') }}</a>
                <a href="{{ route('admin.sponsors.index') }}" class="{{ request()->routeIs('admin.sponsors.*') ? 'active' : '' }}">🤝 {{ __('site.admin.sponsors') }}</a>
                <a href="{{ route('admin.applications.index') }}" class="{{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">📝 {{ __('site.admin.applications') }}</a>
            </nav>
            <div class="admin-sidebar-footer">
                <a href="{{ route('home') }}" target="_blank">🌐 {{ __('site.admin.view_site') }}</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit">🚪 {{ __('site.admin.logout') }}</button>
                </form>
            </div>
        </aside>
        <main class="admin-main">
            <div class="admin-topbar">
                <h1>@yield('title', __('site.admin.dashboard'))</h1>
                <div class="lang-switch">
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">ENG</a>
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'bn']) }}" class="{{ app()->getLocale() === 'bn' ? 'active' : '' }}">BAN</a>
                </div>
            </div>
            @if (session('success'))
                <div class="admin-flash">{{ session('success') }}</div>
            @endif
            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
