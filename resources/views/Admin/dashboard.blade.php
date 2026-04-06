@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    Admin / <span>Dashboard</span>
@endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Dashboard</div>
        <div class="v-page-header__sub">Overview of the 2025 General Election</div>
    </div>
    <div class="v-page-header__actions">
        <span class="badge {{ $votingStatus === 'open' ? 'badge-open' : 'badge-closed' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 8 8" style="width:7px;height:7px;"><circle cx="4" cy="4" r="4"/></svg>
            Voting {{ ucfirst($votingStatus) }}
        </span>
    </div>
</div>

{{-- Stats --}}
<div class="v-stats">
    <div class="v-stat-card">
        <div class="v-stat-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="v-stat-card__label">Total Students</div>
        <div class="v-stat-card__value">{{ $totalStudents }}</div>
        <div class="v-stat-card__sub">Registered voters</div>
    </div>
    <div class="v-stat-card">
        <div class="v-stat-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div class="v-stat-card__label">Candidates</div>
        <div class="v-stat-card__value">{{ $totalCandidates }}</div>
        <div class="v-stat-card__sub">Running this election</div>
    </div>
    <div class="v-stat-card">
        <div class="v-stat-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <div class="v-stat-card__label">Votes Cast</div>
        <div class="v-stat-card__value">{{ $totalVotes }}</div>
        <div class="v-stat-card__sub">
            @if($totalStudents > 0)
                {{ $totalStudents > 0 ? round(($totalVotes / $totalStudents) * 100) : 0 }}% participation
            @else
                —
            @endif
        </div>
    </div>
    <div class="v-stat-card">
        <div class="v-stat-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
        </div>
        <div class="v-stat-card__label">Voting Status</div>
        <div class="v-stat-card__value" style="font-size:1.4rem;">{{ ucfirst($votingStatus) }}</div>
        <div class="v-stat-card__sub">
            <a href="{{ route('admin.voting-control.index') }}" style="color:var(--ash);text-decoration:underline;font-size:0.76rem;">Manage →</a>
        </div>
    </div>
</div>

{{-- Quick actions --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
    <div class="v-card">
        <div class="v-card__header">
            <span class="v-card__title">Quick Actions</span>
        </div>
        <div class="v-card__body" style="display:flex;flex-direction:column;gap:10px;">
            <a href="{{ route('admin.candidates.create') }}" class="btn btn-secondary btn-block">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add New Candidate
            </a>
            <a href="{{ route('admin.positions.create') }}" class="btn btn-secondary btn-block">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add New Position
            </a>
            <a href="{{ route('admin.voting-control.index') }}" class="btn btn-secondary btn-block">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                Manage Voting
            </a>
            <a href="{{ route('admin.results') }}" class="btn btn-secondary btn-block">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                View Results
            </a>
        </div>
    </div>

    <div class="v-card">
        <div class="v-card__header">
            <span class="v-card__title">Election Status</span>
        </div>
        <div class="v-card__body" style="text-align:center;padding:32px 22px;">
            <div style="font-size:3.5rem;font-weight:800;color:{{ $votingStatus === 'open' ? 'var(--success)' : 'var(--ash)' }};line-height:1;">
                {{ strtoupper($votingStatus) }}
            </div>
            <p style="margin:10px 0 20px;font-size:0.85rem;">
                @if($votingStatus === 'open')
                    Students can currently cast their votes.
                @else
                    Voting is currently closed. Open it when ready.
                @endif
            </p>
            <form method="POST" action="{{ route('admin.voting-control.toggle') }}">
                @csrf
                <button type="submit" class="btn {{ $votingStatus === 'open' ? 'btn-danger' : 'btn-success' }} btn-block">
                    {{ $votingStatus === 'open' ? '🔒 Close Voting' : '🔓 Open Voting' }}
                </button>
            </form>
        </div>
    </div>
</div>

@endsection