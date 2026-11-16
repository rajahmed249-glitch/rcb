<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('site.admin.login') }} - {{ __('site.admin.panel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="admin-login-body">
    <div class="login-waves">
        <div class="wave wave-1"></div>
        <div class="wave wave-2"></div>
        <div class="wave wave-3"></div>
    </div>
    <div class="login-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    <div class="admin-login-wrap">
        <div class="admin-login-card">
            <div class="login-card-glow"></div>
            <div class="admin-login-badge">
                <img src="{{ asset('images/logo.png') }}" alt="RCB">
            </div>
            <h1>RCB<span>Admin</span></h1>
            <p class="admin-login-sub">Control Panel</p>
            <div class="login-divider"></div>
            @if (session('error') || $errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error', $errors->first()) }}
                </div>
            @endif
            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="admin-login-field">
                    <label>{{ __('site.admin.user_id') }} <span style="color:#EF4444;">*</span></label>
                    <div class="admin-login-input">
                        <span class="admin-ico"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" placeholder="admin@rcb.com.bd" value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <p class="admin-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="admin-login-field">
                    <label>{{ __('site.admin.password') }} <span style="color:#EF4444;">*</span></label>
                    <div class="admin-login-input">
                        <span class="admin-ico"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" id="password" placeholder="{{ __('site.admin.password') }}" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="admin-error">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="admin-login-btn">
                    {{ __('site.admin.sign_in') }} <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="admin-login-back">
                <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> {{ __('site.admin.view_site') }}</a>
                <span class="sep">&bull;</span>
                <a href="#"><i class="fas fa-question-circle"></i> Help</a>
            </div>
        </div>
    </div>
    <script>
        function togglePassword() {
            var password = document.getElementById('password');
            var icon = document.getElementById('toggleIcon');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
