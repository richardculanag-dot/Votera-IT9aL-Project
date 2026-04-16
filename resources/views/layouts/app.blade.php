{{-- FILE: resources/views/layouts/app.blade.php — replace existing --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Votera') — Votera</title>
    <link rel="stylesheet" href="{{ asset('css/votera.css') }}">
    @stack('styles')
</head>
<body>
<div class="v-shell">

{{-- ── Sidebar ──────────────────────────────────────────── --}}
<aside class="v-sidebar">
    <a href="{{ route(auth()->user()->isAdmin() ? 'admin.dashboard' : 'staff.dashboard') }}" class="v-sidebar__brand">
        <div class="v-sidebar__logo">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0121 17.5c0 2.485-3.582 4.5-8 4.5S5 19.985 5 17.5c0-1.127.38-2.18 1.04-3.078L12 14z"/>
            </svg>
        </div>
        <div>
            <div class="v-sidebar__name">Votera</div>
            <div class="v-sidebar__sub">Academic Year 2025–2026</div>
        </div>
    </a>

    <nav class="v-nav">

        @if(auth()->user()->isAdmin())

        <div class="v-nav__section"><span class="v-nav__label">Overview</span></div>

        <a href="{{ route('admin.dashboard') }}" class="v-nav__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>

        <div class="v-nav__section"><span class="v-nav__label">Elections</span></div>

        <a href="{{ route('admin.elections.index') }}" class="v-nav__item {{ request()->routeIs('admin.elections*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Elections
        </a>
        <a href="{{ route('admin.results') }}" class="v-nav__item {{ request()->routeIs('admin.results') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Results
        </a>

        <div class="v-nav__section"><span class="v-nav__label">Users</span></div>

        <a href="{{ route('admin.staff.index') }}" class="v-nav__item {{ request()->routeIs('admin.staff*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Staff
        </a>
        <a href="{{ route('admin.students.index') }}" class="v-nav__item {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Students
        </a>

        <div class="v-nav__section"><span class="v-nav__label">System</span></div>

        <a href="{{ route('admin.audit') }}" class="v-nav__item {{ request()->routeIs('admin.audit') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Audit Log
        </a>

        @elseif(auth()->user()->isStaff())

        <div class="v-nav__section"><span class="v-nav__label">Overview</span></div>
        <a href="{{ route('staff.dashboard') }}" class="v-nav__item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <div class="v-nav__section"><span class="v-nav__label">Manage</span></div>
        <a href="{{ route('staff.elections.index') }}" class="v-nav__item {{ request()->routeIs('staff.elections*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Elections Domain
        </a>
        <a href="{{ route('staff.results') }}" class="v-nav__item {{ request()->routeIs('staff.results') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Results
        </a>

        @endif
    </nav>

    <div class="v-sidebar__footer">
        <div class="v-sidebar__user">
            <div class="v-sidebar__avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="v-sidebar__username">{{ auth()->user()->name }}</div>
                <div class="v-sidebar__role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="v-sidebar__signout">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Sign out
            </button>
        </form>
    </div>
</aside>

{{-- ── Main ────────────────────────────────────────────── --}}
<main class="v-main">
    <div class="v-topbar">
        <div class="v-breadcrumb">@yield('breadcrumb', '<span>' . ucfirst(auth()->user()->role) . ' Panel</span>')</div>
        <div class="v-topbar__right">
            <span class="badge badge-{{ auth()->user()->role }}">{{ ucfirst(auth()->user()->role) }}</span>
        </div>
    </div>

    <div class="v-content">
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
</main>
</div>
@stack('scripts')
</body>
</html>