<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('site.admin.login') }} - RCB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="admin-login-body">
    <div class="waves">
        <div class="wave wave-1"></div>
        <div class="wave wave-2"></div>
        <div class="wave wave-3"></div>
    </div>

    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="card-glow"></div>

            <div class="login-header">
                <div class="logo-wrapper">
                    <img src="{{ asset('images/logo2.png') }}" alt="RCB" class="logo-img">
                </div>
                <h2>RCB<span>Admin</span></h2>
                <p class="subtitle">Control Panel</p>
            </div>

            <div class="divider"></div>

            @if (session('error') || $errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error', $errors->first()) }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="form-group">
                    <label>{{ __('site.admin.user_id') }} <span class="required">*</span></label>
                    <div class="input-group">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email" name="email" placeholder="admin@rcb.com.bd" value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <p class="admin-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('site.admin.password') }} <span class="required">*</span></label>
                    <div class="input-group">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" name="password" id="password" placeholder="{{ __('site.admin.password') }}" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="admin-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" checked> {{ __('site.admin.remember_me') }}
                    </label>
                    <a href="{{ route('home') }}" class="forgot-link">{{ __('site.admin.view_site') }}</a>
                </div>

                <button type="submit" class="btn-login">
                    {{ __('site.admin.sign_in') }} <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="login-footer">
                <a href="{{ route('home') }}">
                    <i class="fas fa-arrow-left"></i> {{ __('site.admin.view_site') }}
                </a>
                <span class="divider">&bull;</span>
                <a href="#">
                    <i class="fas fa-question-circle"></i> Help
                </a>
                <span class="divider">&bull;</span>
                <a href="#">
                    <i class="fas fa-shield-alt"></i> Privacy
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.querySelector('.toggle-password i');

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

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const form = document.querySelector('form');
                if (form) form.submit();
            }
        });
    </script>
</body>
</html>
