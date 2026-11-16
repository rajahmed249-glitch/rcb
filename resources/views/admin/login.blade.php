<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('site.admin.login') }} - {{ __('site.admin.panel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="admin-login-body">
    <div class="admin-login-wrap">
        <div class="admin-login-card">
            <div class="admin-login-badge">
                <img src="{{ asset('images/logo.png') }}" alt="RCB">
            </div>
            <h1>{{ __('site.admin.login') }}</h1>
            <p class="admin-login-sub">{{ __('site.admin.panel') }}</p>
            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="admin-login-field">
                    <label>{{ __('site.admin.password') }}</label>
                    <div class="admin-login-input">
                        <span>🔒</span>
                        <input type="password" name="password" placeholder="••••••••" required autofocus>
                    </div>
                    @error('password')
                        <p class="admin-error">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="admin-login-btn">{{ __('site.admin.sign_in') }} →</button>
            </form>
            <a href="{{ route('home') }}" class="admin-login-back">← {{ __('site.admin.view_site') }}</a>
        </div>
    </div>
</body>
</html>
