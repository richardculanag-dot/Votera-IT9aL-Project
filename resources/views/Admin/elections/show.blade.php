@extends('layouts.app')
@section('title', 'Election Domain')
@section('breadcrumb') {{ ucfirst($routePrefix) }} / <a href="{{ route($routePrefix . '.elections.index') }}">Elections</a> / <span>{{ $election->title }}</span> @endsection

@section('content')
<div class="v-page-header">
    <div>
        <div class="v-page-header__title">{{ $election->title }}</div>
        <div class="v-page-header__sub">
            {{ $election->department?->name ?? 'No Department' }}
            <span class="v-pipe">|</span>
            <span class="badge badge-{{ $election->status }}">
                @if($election->isOngoing())<span class="badge-dot" style="background:currentColor;"></span>@endif
                {{ ucfirst($election->status) }}
            </span>
            @if($election->is_locked)
            <span class="badge badge-staff">Locked</span>
            @endif
        </div>
    </div>
    <div class="actions">
        <a href="{{ route($routePrefix . '.elections.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
        <a href="{{ route($routePrefix . '.elections.positions.index', $election) }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
            {{ $election->status === 'ended' ? 'View Positions' : 'Manage Positions' }}
        </a>
        @if($routePrefix === 'admin')
        <a href="{{ route('admin.elections.edit', $election) }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
        @endif
    </div>
</div>

<div class="domain-grid">
    <div class="v-card domain-card">
        <div class="v-card__header">
            <h3 class="v-card__title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Timeline
            </h3>
        </div>
        <div class="v-card__body">
            <div class="domain-stats">
                <div class="domain-stat">
                    <div class="domain-stat__icon" style="background:var(--success-bg);color:var(--success);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="domain-stat__label">Start Date</div>
                        <div class="domain-stat__value">{{ $election->start_date?->format('M d, Y') ?? '—' }}</div>
                    </div>
                </div>
                <div class="domain-stat">
                    <div class="domain-stat__icon" style="background:var(--danger-bg);color:var(--danger);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="domain-stat__label">End Date</div>
                        <div class="domain-stat__value">{{ $election->end_date?->format('M d, Y') ?? '—' }}</div>
                    </div>
                </div>
                <div class="domain-stat">
                    <div class="domain-stat__icon" style="background:#ede9fe;color:#5b21b6;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 14H5v2a3 3 0 005.356 1.857M13 9h5.143M7.857 9H5v2a3 3 0 005.357 1.857m5.143 2h-5.143M7.857 13H5v2a3 3 0 005.357 1.857m5.143 2h-5.143"/></svg>
                    </div>
                    <div>
                        <div class="domain-stat__label">Positions</div>
                        <div class="domain-stat__value">{{ $election->positions->count() }}</div>
                    </div>
                </div>
                <div class="domain-stat">
                    <div class="domain-stat__icon" style="background:var(--warning-bg);color:var(--warning);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <div class="domain-stat__label">Votes Cast</div>
                        <div class="domain-stat__value">{{ $election->votes->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="v-card domain-card domain-card--main">
        <div class="v-card__header">
            <h3 class="v-card__title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                Election Structure
            </h3>
            @if($election->status !== 'ended')
                <a href="{{ route($routePrefix . '.elections.positions.index', $election) }}" class="btn btn-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Position
                </a>
            @else
                <span class="badge badge-closed">Read-only (Election ended)</span>
            @endif
        </div>
        <div class="v-card__body">
            @forelse($election->positions as $position)
                <div class="position-item">
                    <div class="position-item__info">
                        <div class="position-item__name">{{ $position->name }}</div>
                        <div class="position-item__meta">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 14H5v2a3 3 0 005.356 1.857M13 9h5.143M7.857 9H5v2a3 3 0 005.357 1.857m5.143 2h-5.143M7.857 13H5v2a3 3 0 005.357 1.857m5.143 2h-5.143"/></svg>
                            {{ $position->candidates->count() }} candidate(s)
                        </div>
                    </div>
                    <a href="{{ route($routePrefix . '.elections.positions.candidates.index', [$election, $position]) }}" class="btn btn-secondary btn-sm">
                        Manage Candidates
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            @empty
                <div class="v-empty" style="padding:32px 24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    <h3>No positions yet</h3>
                    <p>Add positions to define election structure.</p>
                    <a href="{{ route($routePrefix . '.elections.positions.index', $election) }}" class="btn btn-primary mt-3">Add First Position</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.domain-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 18px;
    align-items: start;
}
@media (max-width: 900px) {
    .domain-grid {
        grid-template-columns: 1fr;
    }
}
.v-pipe {
    margin: 0 6px;
    color: var(--border);
}
.domain-card__header .v-card__title svg {
    width: 13px;
    height: 13px;
    margin-right: 5px;
    vertical-align: middle;
}
.domain-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.domain-stat {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    background: var(--surface);
    border-radius: 6px;
}
.domain-stat__icon {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
}
.domain-stat__icon svg {
    width: 14px;
    height: 14px;
}
.domain-stat__label {
    font-size: 0.65rem;
    color: var(--ash);
    margin-bottom: 1px;
}
.domain-stat__value {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--ink);
}
.position-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid var(--border-light);
}
.position-item:last-child {
    border-bottom: none;
}
.position-item__info {
    flex: 1;
}
.position-item__name {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--ink);
}
.position-item__meta {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.72rem;
    color: var(--ash);
    margin-top: 3px;
}
.position-item__meta svg {
    width: 11px;
    height: 11px;
}
</style>
@endsection
