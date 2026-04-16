@extends('layouts.student')

@section('title', 'Elections')

@section('content')
<div class="student-page-header">
    <div class="student-page-header__text">
        <h1>Live Elections</h1>
        <p>Cast your vote for the upcoming academic year</p>
    </div>
    <div class="student-page-header__graphic">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
    </div>
</div>

@forelse($elections as $election)
    <div class="election-vote-card {{ $election->status === 'ongoing' ? 'election-vote-card--active' : '' }}">
        <div class="election-vote-card__header">
            <div class="election-vote-card__status">
                <span class="badge badge-{{ $election->status }}">
                    @if($election->status === 'ongoing')
                    <span class="badge-dot" style="background:currentColor;"></span>
                    Live
                    @else
                    {{ ucfirst($election->status) }}
                    @endif
                </span>
                @if($election->is_locked)
                <span class="badge badge-staff">Locked</span>
                @endif
            </div>
            <div class="election-vote-card__date">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $election->start_date?->format('M d') ?? 'TBD' }} - {{ $election->end_date?->format('M d, Y') ?? 'TBD' }}
            </div>
        </div>
        
        <h2 class="election-vote-card__title">{{ $election->title }}</h2>
        <p class="election-vote-card__dept">{{ $election->department?->name ?? 'All Departments' }}</p>
        
        <div class="election-vote-card__meta">
            <div class="election-vote-card__meta-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 14H5v2a3 3 0 005.356 1.857M13 9h5.143M7.857 9H5v2a3 3 0 005.357 1.857m5.143 2h-5.143M7.857 13H5v2a3 3 0 005.357 1.857m5.143 2h-5.143"/></svg>
                {{ $election->positions->count() }} positions
            </div>
            <div class="election-vote-card__meta-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 14H5v2a3 3 0 005.356 1.857M13 9h5.143M7.857 9H5v2a3 3 0 005.357 1.857m5.143 2h-5.143M7.857 13H5v2a3 3 0 005.357 1.857m5.143 2h-5.143"/></svg>
                {{ $election->positions->sum(fn($p) => $p->candidates->count()) }} candidates
            </div>
        </div>
        
        @if($election->status === 'ongoing' && ! $election->is_locked)
            <a href="{{ route('student.vote', $election) }}" class="election-vote-card__cta">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                Cast Your Vote
            </a>
        @elseif($election->status === 'ended')
            <div class="election-vote-card__closed">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Voting has ended
            </div>
        @else
            <div class="election-vote-card__pending">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Opens {{ $election->start_date?->format('M d, Y') ?? 'soon' }}
            </div>
        @endif
    </div>
@empty
    <div class="student-empty">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <h2>No elections available</h2>
        <p>Check back later for upcoming elections in your department.</p>
    </div>
@endforelse

<style>
.student-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 16px;
}
.student-page-header__text h1 {
    font-size: 1.1rem;
    margin: 0 0 2px;
}
.student-page-header__text p {
    font-size: 0.75rem;
    margin: 0;
}
.student-page-header__graphic {
    width: 32px;
    height: 32px;
    background: var(--surface);
    border-radius: 8px;
    display: grid;
    place-items: center;
    color: var(--ink);
}
.student-page-header__graphic svg {
    width: 16px;
    height: 16px;
    opacity: 0.5;
}
.election-vote-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 14px;
    margin-bottom: 12px;
    transition: all var(--transition);
}
.election-vote-card:hover {
    border-color: #cac8c4;
}
.election-vote-card--active {
    border-color: var(--success-border);
    box-shadow: 0 0 0 1px var(--success);
}
.election-vote-card__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}
.election-vote-card__status {
    display: flex;
    gap: 5px;
}
.election-vote-card__date {
    display: flex;
    align-items: center;
    gap: 3px;
    font-size: 0.68rem;
    color: var(--ash);
}
.election-vote-card__date svg {
    width: 10px;
    height: 10px;
}
.election-vote-card__title {
    font-size: 0.95rem;
    font-weight: 700;
    margin: 0 0 4px;
    font-family: var(--font-serif);
    font-style: italic;
}
.election-vote-card__dept {
    font-size: 0.75rem;
    color: var(--ash);
    margin: 0 0 12px;
}
.election-vote-card__meta {
    display: flex;
    gap: 12px;
    padding: 8px 0;
    border-top: 1px solid var(--border-light);
    border-bottom: 1px solid var(--border-light);
    margin-bottom: 12px;
}
.election-vote-card__meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.7rem;
    color: var(--ash);
}
.election-vote-card__meta-item svg {
    width: 10px;
    height: 10px;
}
.election-vote-card__cta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    width: 100%;
    padding: 8px;
    background: var(--ink);
    color: #fff;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.82rem;
    text-decoration: none;
    transition: all var(--transition);
}
.election-vote-card__cta:hover {
    background: #2d2d2d;
    text-decoration: none;
    transform: translateY(-1px);
}
.election-vote-card__cta svg {
    width: 12px;
    height: 12px;
}
.election-vote-card__pending,
.election-vote-card__closed {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 8px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.75rem;
}
.election-vote-card__pending {
    background: var(--surface);
    color: var(--ash);
}
.election-vote-card__pending svg {
    width: 11px;
    height: 11px;
}
.election-vote-card__closed {
    background: var(--surface-2);
    color: var(--ash);
}
.election-vote-card__closed svg {
    width: 11px;
    height: 11px;
}
.student-empty {
    text-align: center;
    padding: 36px 16px;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 10px;
}
.student-empty svg {
    width: 32px;
    height: 32px;
    margin-bottom: 10px;
    opacity: 0.3;
}
.student-empty h2 {
    font-size: 0.9rem;
    margin: 0 0 4px;
}
.student-empty p {
    font-size: 0.75rem;
    margin: 0;
}
@media (max-width: 500px) {
    .student-page-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    .student-page-header__graphic {
        display: none;
    }
}
</style>
@endsection
