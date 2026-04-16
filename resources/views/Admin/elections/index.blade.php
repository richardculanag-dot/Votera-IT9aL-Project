{{-- FILE: resources/views/admin/elections/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Elections')
@section('breadcrumb') {{ ucfirst($routePrefix) }} / <span>Elections</span> @endsection

@section('content')
<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Elections</div>
        <div class="v-page-header__sub">Manage election periods and their status</div>
    </div>
    @if($routePrefix === 'admin')
    <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Election
    </a>
    @endif
</div>

@if($elections->isEmpty())
    <div class="v-empty" style="padding:72px 24px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <h3>No elections yet</h3>
        <p>Create your first election to get started.</p>
        @if($routePrefix === 'admin')
        <a href="{{ route('admin.elections.create') }}" class="btn btn-primary mt-3">Create Election</a>
        @endif
    </div>
@else
<div class="election-grid">
    @foreach($elections as $election)
    <div class="election-card {{ $election->isOngoing() ? 'election-card--live' : '' }}">
        <div class="election-card__header">
            <span class="badge badge-{{ $election->status }}">
                @if($election->isOngoing())
                <span class="badge-dot" style="background:currentColor;"></span>
                @endif
                {{ ucfirst($election->status) }}
            </span>
            @if($election->is_locked)
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;color:var(--ash);"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            @endif
        </div>
        <h3 class="election-card__title">{{ $election->title }}</h3>
        <p class="election-card__dept">{{ $election->department?->name ?? 'No Department' }}</p>
        
        <div class="election-card__timeline">
            <div class="election-card__timeline-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>{{ $election->start_date?->format('M d, Y') ?? 'Not set' }}</span>
            </div>
            <div class="election-card__timeline-div">→</div>
            <div class="election-card__timeline-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ $election->end_date?->format('M d, Y') ?? 'Not set' }}</span>
            </div>
        </div>
        
        <div class="election-card__stats">
            <div class="election-card__stat">
                <span class="election-card__stat-value">{{ $election->positions_count }}</span>
                <span class="election-card__stat-label">Positions</span>
            </div>
            <div class="election-card__stat">
                <span class="election-card__stat-value">{{ $election->votes_count }}</span>
                <span class="election-card__stat-label">Votes</span>
            </div>
        </div>
        
        <div class="election-card__actions">
            @if($routePrefix === 'admin' && ! $election->isEnded())
            <form method="POST" action="{{ route('admin.elections.toggle', $election) }}" class="election-card__action-form">
                @csrf
                <button type="submit" class="election-card__action-btn {{ $election->isOngoing() ? 'btn-warning' : 'btn-success' }}"
                    onclick="return confirm('Change status of this election?')">
                    {{ $election->isOngoing() ? 'End Voting' : 'Start Voting' }}
                </button>
            </form>
            @endif
            <a href="{{ route($routePrefix . '.elections.show', $election) }}" class="election-card__action-link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Manage
            </a>
            @if($routePrefix === 'admin')
            <a href="{{ route('admin.elections.edit', $election) }}" class="election-card__action-link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            @if(! $election->isOngoing())
            <form method="POST" action="{{ route('admin.elections.destroy', $election) }}" onsubmit="return confirm('Delete this election? This cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit" class="election-card__action-link election-card__action-link--danger">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </form>
            @endif
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

<style>
.election-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 14px;
}
.election-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 14px;
    transition: all var(--transition);
    display: flex;
    flex-direction: column;
}
.election-card:hover {
    box-shadow: var(--shadow-sm);
    transform: translateY(-1px);
}
.election-card--live {
    border-color: var(--success-border);
    box-shadow: 0 0 0 1px var(--success);
}
.election-card__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}
.election-card__title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--ink);
    margin: 0 0 2px;
    line-height: 1.3;
}
.election-card__dept {
    font-size: 0.75rem;
    color: var(--ash);
    margin: 0 0 10px;
}
.election-card__timeline {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px;
    background: var(--surface);
    border-radius: 5px;
    margin-bottom: 10px;
}
.election-card__timeline-item {
    display: flex;
    align-items: center;
    gap: 3px;
    font-size: 0.68rem;
    color: var(--ash);
}
.election-card__timeline-item svg {
    width: 10px;
    height: 10px;
}
.election-card__timeline-div {
    font-size: 0.6rem;
    color: var(--ash-light);
}
.election-card__stats {
    display: flex;
    gap: 16px;
    padding: 8px 0;
    border-top: 1px solid var(--border-light);
    border-bottom: 1px solid var(--border-light);
    margin-bottom: 10px;
}
.election-card__stat {
    display: flex;
    flex-direction: column;
}
.election-card__stat-value {
    font-size: 1rem;
    font-weight: 700;
    color: var(--ink);
    line-height: 1;
}
.election-card__stat-label {
    font-size: 0.6rem;
    color: var(--ash);
    margin-top: 1px;
}
.election-card__actions {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    margin-top: auto;
}
.election-card__action-form {
    flex: 1;
}
.election-card__action-btn {
    width: 100%;
    padding: 4px 8px;
    border-radius: 5px;
    font-size: 0.68rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    font-family: var(--font-sans);
}
.election-card__action-link {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 4px 7px;
    border-radius: 5px;
    font-size: 0.68rem;
    font-weight: 600;
    background: var(--white);
    color: var(--ink);
    border: 1px solid var(--border);
    text-decoration: none;
    transition: all var(--transition);
}
.election-card__action-link:hover {
    background: var(--surface);
    border-color: #cac8c4;
    text-decoration: none;
}
.election-card__action-link svg {
    width: 10px;
    height: 10px;
}
.election-card__action-link--danger:hover {
    background: var(--danger-bg);
    border-color: var(--danger-border);
    color: var(--danger);
}
</style>
@endsection