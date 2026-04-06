@extends('layouts.app')

@section('title', 'Staff Dashboard')

@section('breadcrumb')
    Staff / <span>Dashboard</span>
@endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Staff Dashboard</div>
        <div class="v-page-header__sub">Overview — 2025 General Election</div>
    </div>
    <span class="badge {{ $votingStatus === 'open' ? 'badge-open' : 'badge-closed' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 8 8" style="width:7px;height:7px;"><circle cx="4" cy="4" r="4"/></svg>
        Voting {{ ucfirst($votingStatus) }}
    </span>
</div>

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
    </div>
    <div class="v-stat-card">
        <div class="v-stat-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <div class="v-stat-card__label">Votes Cast</div>
        <div class="v-stat-card__value">{{ $totalVotes }}</div>
        <div class="v-stat-card__sub">{{ $totalStudents > 0 ? round(($totalVotes / $totalStudents) * 100) : 0 }}% participation</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
    <div class="v-card">
        <div class="v-card__header"><span class="v-card__title">Staff Actions</span></div>
        <div class="v-card__body" style="display:flex;flex-direction:column;gap:10px;">
            <a href="{{ route('staff.candidates.create') }}" class="btn btn-primary btn-block">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Candidate
            </a>
            <a href="{{ route('staff.candidates.index') }}" class="btn btn-secondary btn-block">View Candidates</a>
            <a href="{{ route('staff.positions.index') }}" class="btn btn-secondary btn-block">View Positions</a>
            <a href="{{ route('staff.results') }}" class="btn btn-secondary btn-block">View Results</a>
        </div>
    </div>
    <div class="v-card">
        <div class="v-card__header"><span class="v-card__title">Voting Status</span></div>
        <div class="v-card__body" style="text-align:center;padding:28px;">
            <div style="font-size:3rem;font-weight:800;color:{{ $votingStatus === 'open' ? 'var(--success)' : 'var(--ash)' }};">
                {{ strtoupper($votingStatus) }}
            </div>
            <p style="font-size:0.82rem;margin-top:8px;">Contact an Admin to change the voting status.</p>
        </div>
    </div>
</div>

@endsection