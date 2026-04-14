{{-- FILE: resources/views/auth/reset-password.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Votera</title>
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
            <div class="v-login-year">Set New Password</div>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="v-form-group">
                <label class="v-label" for="email">Email Address</label>
                <input id="email" class="v-input {{ $errors->has('email') ? 'error' : '' }}"
                       type="email" name="email" value="{{ old('email', $request->email) }}" required>
                @error('email') <p class="v-input-error">{{ $message }}</p> @enderror
            </div>

            <div class="v-form-group">
                <label class="v-label" for="password">New Password</label>
                <input id="password" class="v-input {{ $errors->has('password') ? 'error' : '' }}"
                       type="password" name="password" required autocomplete="new-password">
                @error('password') <p class="v-input-error">{{ $message }}</p> @enderror
            </div>

            <div class="v-form-group">
                <label class="v-label" for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" class="v-input"
                       type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Reset Password
            </button>
        </form>
    </div>
</div>
</body>
</html>