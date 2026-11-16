<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('site.admin.login') }} - {{ __('site.admin.panel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="admin-body admin-login-body">
    <div class="admin-login-card">
        <img src="{{ asset('images/logo.png') }}" alt="RCB" class="admin-login-logo">
        <h1>{{ __('site.admin.login') }}</h1>
        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <label>{{ __('site.admin.password') }}</label>
            <input type="password" name="password" required autofocus>
            @error('password')
                <p class="admin-error">{{ $message }}</p>
            @enderror
            <button type="submit" class="admin-btn">{{ __('site.admin.sign_in') }}</button>
        </form>
    </div>
</body>
</html>
