{{-- FILE: resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — Votera</title>
    <link rel="stylesheet" href="{{ asset('css/votera.css') }}">
</head>
<body>
<div class="v-login-bg">
    <div class="v-login-card">

        <div class="v-login-brand">
            <div class="v-login-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div class="v-login-title">VOTERA</div>
            <div class="v-login-year">Password Recovery</div>
        </div>

        @if (session('status'))
            <div class="v-alert v-alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ session('status') }}
            </div>
        @endif

        <h3>Forgot Password</h3>
        <p class="sub">Enter your registered email and we'll send you a reset link.</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="v-form-group">
                <label class="v-label" for="email">Email Address</label>
                <input id="email" class="v-input {{ $errors->has('email') ? 'error' : '' }}"
                       type="email" name="email" value="{{ old('email') }}"
                       placeholder="your@email.edu" required autofocus>
                @error('email') <p class="v-input-error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Send Reset Link
            </button>
        </form>

        <div class="v-login-footer">
            <p><a href="{{ route('login') }}">← Back to Login</a></p>
        </div>
    </div>
</div>
</body>
</html>