<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Votera</title>
    <link rel="stylesheet" href="{{ asset('css/votera.css') }}">
</head>
<body>
<div class="v-login-bg">
    <div class="v-login-card">

        {{-- Brand --}}
        <div class="v-login-brand">
            <div class="v-login-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0121 17.5c0 2.485-3.582 4.5-8 4.5S5 19.985 5 17.5c0-1.127.38-2.18 1.04-3.078L12 14z"/>
                </svg>
            </div>
            <div class="v-login-title">VOTERA</div>
            <div class="v-login-year">Academic Year 2025–2026</div>
        </div>

        {{-- Session errors --}}
        @if($errors->any())
            <div class="v-alert v-alert-error" style="margin-bottom:16px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <h3>Login</h3>
        <p class="sub">Please authenticate to access your ballot.</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="v-form-group">
                <label class="v-label" for="email">Student ID</label>
                <div style="position:relative;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:#aaa;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <input
                        id="email"
                        class="v-input {{ $errors->has('email') ? 'error' : '' }}"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="e.g. admin@votera.edu"
                        style="padding-left:34px;"
                        autocomplete="username"
                        autofocus
                        required
                    >
                </div>
            </div>

            <div class="v-form-group">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">
                    <label class="v-label" for="password" style="margin-bottom:0;">Password</label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">FORGOT?</a>
                    @endif
                </div>
                <div style="position:relative;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:#aaa;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input
                        id="password"
                        class="v-input {{ $errors->has('password') ? 'error' : '' }}"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        style="padding-left:34px;"
                        autocomplete="current-password"
                        required
                    >
                </div>
            </div>

            <div class="v-check-row">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Remember this account</label>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Enter Dashboard
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <div class="v-login-footer">
            <p>Trouble signing in? <a href="mailto:registrar@school.edu">Contact Registrar</a></p>
        </div>
    </div>

<footer class="v-footer">
    <div class="v-system-label">Votera System 2026</div>
</footer></div>
</body>
</html>