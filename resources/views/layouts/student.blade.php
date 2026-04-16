<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Portal') - Votera</title>
    <link rel="stylesheet" href="{{ asset('css/votera.css') }}">
    <style>
        body { background: #f6f7fb; }
        .student-shell { max-width: 1100px; margin: 0 auto; padding: 24px 18px 40px; }
        .student-topbar { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:20px; }
        .student-brand { font-weight: 700; letter-spacing: .04em; color: var(--ink); }
        .student-nav { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:24px; }
        .student-nav a { text-decoration:none; padding:8px 12px; border:1px solid var(--border); border-radius:10px; color: var(--ink); font-size: .9rem; }
        .student-nav a.active { background: var(--ink); color: #fff; border-color: var(--ink); }
        .student-grid { display:grid; gap:16px; }
        .student-card { background:#fff; border:1px solid var(--border); border-radius:14px; padding:16px; }
    </style>
    @stack('styles')
</head>
<body>
<div class="student-shell">
    <div class="student-topbar">
        <div>
            <div class="student-brand">VOTERA STUDENT PORTAL</div>
            <div style="font-size:.82rem;color:var(--ash);">{{ auth()->user()->name }} - {{ auth()->user()->student_id ?? auth()->user()->email }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-secondary btn-sm" type="submit">Sign out</button>
        </form>
    </div>

    <nav class="student-nav">
        <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('student.elections') }}" class="{{ request()->routeIs('student.elections') || request()->routeIs('student.vote') ? 'active' : '' }}">Live Elections</a>
        <a href="{{ route('student.history') }}" class="{{ request()->routeIs('student.history') ? 'active' : '' }}">My Votes</a>
        <a href="{{ route('student.profile') }}" class="{{ request()->routeIs('student.profile') ? 'active' : '' }}">Profile</a>
    </nav>

    @if(session('success'))
        <div class="v-alert v-alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="v-alert v-alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>
