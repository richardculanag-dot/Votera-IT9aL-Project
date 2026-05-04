<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Closed — Votera</title>
    <link rel="stylesheet" href="{{ asset('css/votera.css') }}">
</head>
<body>
<div class="v-success-wrap">
    <div class="v-success-card">
        <div class="v-success-icon" style="background:#f1f0ee;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--ash);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h1>Voting is Closed</h1>
        <p>The election is not currently active. Please check back later or contact your school registrar for more information.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-secondary btn-block">Sign Out</button>
        </form>
    </div>
</div>
</body>
</html>