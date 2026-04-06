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

    {{-- ── Sidebar ─────────────────────────────── --}}
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
            <div class="v-nav__label">Main</div>

            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="v-nav__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.positions.index') }}" class="v-nav__item {{ request()->routeIs('admin.positions*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0120 9.414V19a2 2 0 01-2 2z"/></svg>
                    Positions
                </a>
                <a href="{{ route('admin.candidates.index') }}" class="v-nav__item {{ request()->routeIs('admin.candidates*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Candidates
                </a>

                <div class="v-nav__label">Elections</div>

                <a href="{{ route('admin.voting-control.index') }}" class="v-nav__item {{ request()->routeIs('admin.voting-control*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                    Voting Control
                </a>
                <a href="{{ route('admin.results') }}" class="v-nav__item {{ request()->routeIs('admin.results') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Results
                </a>

            @elseif(auth()->user()->isStaff())
                <a href="{{ route('staff.dashboard') }}" class="v-nav__item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('staff.positions.index') }}" class="v-nav__item {{ request()->routeIs('staff.positions*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0120 9.414V19a2 2 0 01-2 2z"/></svg>
                    Positions
                </a>
                <a href="{{ route('staff.candidates.index') }}" class="v-nav__item {{ request()->routeIs('staff.candidates*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Candidates
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
            <form method="POST" action="{{ route('logout') }}" style="margin-top:8px;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm btn-block" style="font-size:0.75rem;padding:6px 10px;color:rgba(255,255,255,0.5);background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.1);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main ────────────────────────────────── --}}
    <main class="v-main">
        <div class="v-topbar">
            <div class="v-topbar__left">
                <span class="v-breadcrumb">@yield('breadcrumb', '<span>' . ucfirst(auth()->user()->role) . ' Panel</span>')</span>
            </div>
            <div class="v-topbar__right">
                <span class="badge badge-{{ auth()->user()->role }}">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
        </div>

        <div class="v-content">
            {{-- Flash messages --}}
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