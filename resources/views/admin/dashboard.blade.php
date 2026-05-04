{{-- FILE: resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb') Admin / <span>Dashboard</span> @endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">
            Dashboard
            <span id="live-indicator" style="display:inline-flex;align-items:center;gap:6px;margin-left:12px;font-size:0.7rem;background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:99px;font-weight:600;">
                <span style="width:6px;height:6px;background:#15803d;border-radius:50%;animation:pulse 2s infinite;"></span>
                LIVE
            </span>
        </div>
        <div class="v-page-header__sub" id="current-election-title">
            {{ $currentElection ? $currentElection->title : 'No active election' }}
        </div>
    </div>
    <div class="v-page-header__actions">
        @if($currentElection)
            <span class="badge badge-{{ $currentElection->status === 'ongoing' ? 'open' : 'closed' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 8 8" style="width:7px;height:7px;"><circle cx="4" cy="4" r="4"/></svg>
                {{ ucfirst($currentElection->status) }}
            </span>
        @endif
        <a href="{{ route('admin.elections.index') }}" class="btn btn-secondary btn-sm">Manage Elections</a>
        <a href="{{ route('admin.elections.create') }}" class="btn btn-primary btn-sm">+ New Election</a>
    </div>
</div>

{{-- Stat Cards --}}
<div class="v-stats">
    <a href="{{ route('admin.students.index') }}" class="v-stat-card" style="text-decoration:none;display:block;">
        <div class="v-stat-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="v-stat-card__label">Total Voters</div>
        <div class="v-stat-card__value" id="total-voters">{{ $totalStudents }}</div>
        <div class="v-stat-card__sub">Registered students</div>
    </a>
    <a href="{{ route('admin.votes.index') }}" class="v-stat-card" style="text-decoration:none;display:block;">
        <div class="v-stat-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div class="v-stat-card__label">Votes Cast</div>
        <div class="v-stat-card__value" id="votes-cast">{{ $totalVotesCast }}</div>
        <div class="v-stat-card__sub">Current election</div>
    </a>
    <div class="v-stat-card">
        <div class="v-stat-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div class="v-stat-card__label">Voter Turnout</div>
        <div class="v-stat-card__value" id="turnout-percent">{{ $turnoutPercent }}%</div>
        <div class="v-stat-card__sub">Of registered voters</div>
    </div>
    <a href="{{ route('admin.elections.index') }}" class="v-stat-card" style="text-decoration:none;display:block;">
        <div class="v-stat-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div class="v-stat-card__label">Active Elections</div>
        <div class="v-stat-card__value" id="active-elections">{{ $activeElections }}</div>
        <div class="v-stat-card__sub">Currently ongoing</div>
    </a>
</div>

{{-- Charts --}}
<div style="display:grid;grid-template-columns:1fr;gap:20px;margin-bottom:20px;">
    <div class="v-card">
        <div class="v-card__header">
            <span class="v-card__title">Votes by Position</span>
            @if($currentElection)
                <span style="font-size:0.75rem;color:var(--ash);">{{ $currentElection->title }}</span>
            @endif
        </div>
        <div class="v-card__body">
            @if(count($chartLabels) > 0)
                <div style="height:220px;max-height:220px;">
                    <canvas id="votesChart"></canvas>
                </div>
            @else
                <div class="v-empty" style="padding:40px 0;">
                    <p>No votes recorded yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Turnout Progress + Recent Votes --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
    <div class="v-card">
        <div class="v-card__header"><span class="v-card__title">Voter Turnout</span></div>
        <div class="v-card__body">
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="font-size:0.85rem;font-weight:600;" id="turnout-text">{{ $totalVotesCast }} of {{ $totalStudents }} students voted</span>
                <span style="font-size:0.85rem;font-weight:700;" id="turnout-percent-bar">{{ $turnoutPercent }}%</span>
            </div>
            <div style="height:12px;background:var(--surface);border-radius:99px;overflow:hidden;border:1px solid var(--border);">
                <div id="turnout-bar" style="height:100%;width:{{ $turnoutPercent }}%;background:var(--ink);border-radius:99px;transition:width 0.6s ease;"></div>
            </div>
            <p style="font-size:0.78rem;color:var(--ash);margin-top:8px;" id="remaining-votes">
                {{ $totalStudents - $totalVotesCast }} students have not voted yet.
            </p>
        </div>
    </div>

    <div class="v-card">
        <div class="v-card__header">
            <span class="v-card__title">Recent Votes</span>
            <a href="{{ route('admin.votes.index') }}" style="font-size:0.75rem;color:var(--ash);">View all →</a>
        </div>
        <div class="v-card__body" style="padding:0;" id="recent-votes-container">
            @forelse($recentLogs as $log)
            <div style="padding:10px 18px;border-bottom:1px solid var(--border);display:flex;gap:10px;align-items:flex-start;">
                <div style="width:7px;height:7px;border-radius:50%;background:var(--ink);margin-top:5px;flex-shrink:0;"></div>
                <div>
                    <div style="font-size:0.82rem;font-weight:600;">{{ $log->description }}</div>
                    <div style="font-size:0.72rem;color:var(--ash);">
                        {{ $log->user->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
            @empty
            <div class="v-empty" style="padding:28px;"><p>No votes recorded yet.</p></div>
            @endforelse
        </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
let votesChart = null;

function initChart(labels, data) {
    const ctx = document.getElementById('votesChart');
    if (!ctx) return;
    
    if (votesChart) {
        votesChart.destroy();
    }
    
    if (labels && labels.length > 0) {
        votesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Votes',
                    data: data,
                    backgroundColor: '#1a1a1a',
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ` ${ctx.raw} votes` } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { family: 'DM Sans' } },
                        grid: { color: '#e2e0dc' }
                    },
                    x: {
                        ticks: { font: { family: 'DM Sans', size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }
}

function updateDashboard() {
    fetch('/api/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-voters').textContent = data.total_students;
            document.getElementById('votes-cast').textContent = data.total_votes_cast;
            document.getElementById('turnout-percent').textContent = data.turnout_percent + '%';
            document.getElementById('active-elections').textContent = data.active_elections;
            
            const remaining = data.total_students - data.total_votes_cast;
            document.getElementById('turnout-text').textContent = data.total_votes_cast + ' of ' + data.total_students + ' students voted';
            document.getElementById('turnout-percent-bar').textContent = data.turnout_percent + '%';
            document.getElementById('turnout-bar').style.width = data.turnout_percent + '%';
            document.getElementById('remaining-votes').textContent = remaining + ' students have not voted yet.';
            
            if (data.current_election) {
                document.getElementById('current-election-title').textContent = data.current_election.title;
            }
            
            if (data.chart && data.chart.labels) {
                initChart(data.chart.labels, data.chart.data);
            }
        })
        .catch(error => console.error('Error fetching stats:', error));
}

@if(count($chartLabels) > 0)
initChart({!! json_encode($chartLabels) !!}, {!! json_encode($chartData) !!});
@endif

setInterval(updateDashboard, 10000);
</script>
@endpush