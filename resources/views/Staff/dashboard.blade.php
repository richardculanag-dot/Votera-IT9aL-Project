@extends('layouts.app')

@section('title', 'Staff Dashboard')

@section('breadcrumb')
    Staff / <span>Dashboard</span>
@endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">
            Staff Dashboard
            <span id="live-indicator" style="display:inline-flex;align-items:center;gap:6px;margin-left:12px;font-size:0.7rem;background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:99px;font-weight:600;">
                <span style="width:6px;height:6px;background:#15803d;border-radius:50%;animation:pulse 2s infinite;"></span>
                LIVE
            </span>
        </div>
        <div class="v-page-header__sub" id="current-election-title">Overview — 2025 General Election</div>
    </div>
    <span class="badge {{ $votingStatus === 'open' ? 'badge-open' : 'badge-closed' }}" id="voting-status-badge">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 8 8" style="width:7px;height:7px;"><circle cx="4" cy="4" r="4"/></svg>
        Voting {{ ucfirst($votingStatus) }}
    </span>
</div>

<div class="v-stats">
    <div class="v-stat-card" style="cursor:not-allowed;opacity:0.85;">
        <div class="v-stat-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="v-stat-card__label">Total Students</div>
        <div class="v-stat-card__value" id="total-students">{{ $totalStudents }}</div>
        <div class="v-stat-card__sub">Admin access only</div>
    </div>
    <a href="{{ route('staff.elections.index') }}" class="v-stat-card" style="text-decoration:none;display:block;">
        <div class="v-stat-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div class="v-stat-card__label">Candidates</div>
        <div class="v-stat-card__value" id="total-candidates">{{ $totalCandidates }}</div>
    </a>
    <a href="{{ route('staff.results') }}" class="v-stat-card" style="text-decoration:none;display:block;">
        <div class="v-stat-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <div class="v-stat-card__label">Votes Cast</div>
        <div class="v-stat-card__value" id="total-votes">{{ $totalVotes }}</div>
        <div class="v-stat-card__sub" id="participation-percent">{{ $totalStudents > 0 ? round(($totalVotes / $totalStudents) * 100) : 0 }}% participation</div>
    </a>
</div>

<div class="v-card">
    <div class="v-card__header"><span class="v-card__title">Voting Status</span></div>
    <div class="v-card__body" style="text-align:center;padding:28px;">
        <div style="font-size:3rem;font-weight:800;color:{{ $votingStatus === 'open' ? 'var(--success)' : 'var(--ash)' }};" id="voting-status-text">
            {{ strtoupper($votingStatus) }}
        </div>
        <p style="font-size:0.82rem;margin-top:8px;">Contact an Admin to change the voting status.</p>
    </div>
</div>

@endsection

@push('scripts')
<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>
<script>
function updateStaffDashboard() {
    fetch('/api/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-students').textContent = data.total_students;
            document.getElementById('total-votes').textContent = data.total_votes_cast;
            
            const participation = data.total_students > 0 ? Math.round((data.total_votes_cast / data.total_students) * 100) : 0;
            document.getElementById('participation-percent').textContent = participation + '% participation';
            
            if (data.current_election) {
                const status = data.current_election.status;
                const isLocked = data.current_election.is_locked;
                const votingStatus = (status === 'ongoing' && !isLocked) ? 'open' : 'closed';
                
                document.getElementById('voting-status-text').textContent = votingStatus.toUpperCase();
                document.getElementById('voting-status-text').style.color = votingStatus === 'open' ? '#15803d' : 'var(--ash)';
                
                document.getElementById('voting-status-badge').className = 'badge ' + (votingStatus === 'open' ? 'badge-open' : 'badge-closed');
                document.getElementById('current-election-title').textContent = data.current_election.title || 'Overview — 2025 General Election';
            }
        })
        .catch(error => console.error('Error fetching stats:', error));
}

setInterval(updateStaffDashboard, 10000);
</script>
@endpush