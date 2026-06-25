<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MOE LIMS') }} - Login</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* ----- Green Theme (same as dashboard) ----- */
        body {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
            max-width: 420px;
            width: 100%;
            border: 1px solid #a5d6a7;
            transition: transform 0.3s;
        }

        .login-card:hover {
            transform: translateY(-2px);
        }

        .login-card .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-card .logo img {
            height: 70px;
            margin-bottom: 10px;
        }

        .login-card .logo h4 {
            color: #2e7d32;
            font-weight: 700;
            margin: 0;
            font-size: 1.5rem;
        }

        .login-card .logo small {
            color: #388e3c;
            font-size: 0.85rem;
            display: block;
        }

        .login-card .form-control {
            border-radius: 50px;
            padding: 12px 20px;
            border: 1px solid #c8e6c9;
            background: #f8fdf8;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .login-card .form-control:focus {
            border-color: #2e7d32;
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
        }

        .login-card .btn-login {
            background: #2e7d32;
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-weight: 600;
            color: #fff;
            width: 100%;
            transition: background 0.3s;
            font-size: 1rem;
        }

        .login-card .btn-login:hover {
            background: #1b5e20;
        }

        .login-card .form-check-label {
            color: #4e4e4e;
        }

        .login-card .alert {
            border-radius: 50px;
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            color: #6c757d;
            font-size: 0.8rem;
        }

        .login-footer a {
            color: #2e7d32;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }
            .login-card .logo img {
                height: 55px;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo">
            <!-- Replace with your actual logo path -->
            <img src="{{ asset('images/moe-logo.png') }}" alt="MOE Logo" onerror="this.style.display='none';">
            <i class="fas fa-leaf" style="font-size:2.5rem; color:#2e7d32; display:none;" id="logoFallback"></i>
            <h4>MOE LIMS</h4>
            <small>Environmental Laboratory</small>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success mb-3" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0 list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="your@email.com">
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control" name="password" required placeholder="••••••••">
            </div>

            <!-- Remember Me (only) – removed "Forgot password" link -->
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        {{ __('Remember me') }}
                    </label>
                </div>
                <!-- Removed the "Forgot password?" link -->
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> {{ __('Log in') }}
            </button>
        </form>

        <div class="login-footer">
            &copy; {{ date('Y') }} <a href="https://moe.gov.kh" target="_blank">Ministry of Environment</a> – All rights reserved.
            <br>
            <small>Created by: KAO NGUONNORA</small>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const img = document.querySelector('.logo img');
            const fallback = document.getElementById('logoFallback');
            if (img) {
                img.onerror = function() {
                    this.style.display = 'none';
                    if (fallback) fallback.style.display = 'inline-block';
                };
            }
        });
    </script>
</body>
</html>