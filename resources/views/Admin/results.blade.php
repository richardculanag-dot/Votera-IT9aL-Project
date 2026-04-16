{{-- FILE: resources/views/admin/results.blade.php --}}
@extends('layouts.app')

@section('title', 'Results')
@section('breadcrumb') Admin / <span>Results</span> @endsection

@push('styles')
<style>
.results-header-bar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}

.results-summary-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 12px 14px;
    box-shadow: var(--shadow-xs);
}

.results-summary-card__label {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--ash);
    font-weight: 700;
}

.results-summary-card__value {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--ink);
    margin-top: 3px;
}

.position-results {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 14px;
}

/* Candidate row */
.result-candidate {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid var(--border-light);
    animation: fadeIn .4s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

.rank-num {
    width: 20px;
    text-align: center;
    font-weight: 800;
    color: var(--ash-light);
    font-size: 0.85rem;
}

.rank-1 {
    color: #b45309;
    font-size: 0.9rem;
}

.result-candidate__img {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--border);
}

.winner-img {
    border-color: #f59e0b;
    box-shadow: 0 0 0 2px #fef3c7;
}

.result-name {
    font-weight: 600;
    font-size: 0.82rem;
    color: var(--ink);
}

/* Progress bar */
.result-bar-track {
    flex: 1;
    height: 5px;
    background: var(--surface-2);
    border-radius: 99px;
    overflow: hidden;
}

.result-bar-fill {
    height: 100%;
    border-radius: 99px;
    transition: width 1s cubic-bezier(0.16, 1, 0.3, 1);
}

.is-winner { background: var(--ink); }
.is-other { background: #cfcfcf; }

.result-bar-row {
    display: flex;
    align-items: center;
    gap: 6px;
}

.result-count {
    font-size: 0.7rem;
    font-weight: 700;
}

.result-pct {
    font-size: 0.68rem;
    color: var(--ash);
}

/* Chart */
.chart-wrap {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 14px;
    padding: 10px 0;
    flex-wrap: wrap;
}

.chart-legend {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.chart-legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.7rem;
}

.chart-legend-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}
</style>
@endpush

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Election Results</div>
        <div class="v-page-header__sub">
            @if($election)
                {{ $election->title }} ·
                <span class="badge badge-{{ $election->status }}">
                    {{ ucfirst($election->status) }}
                </span>
            @else
                No election data
            @endif
        </div>
    </div>
    <a href="{{ route('admin.elections.index') }}" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Elections
    </a>
</div>

@if(!$election)

<div class="v-empty">
    <h3>No elections yet</h3>
</div>

@else

{{-- SUMMARY --}}
<div class="results-header-bar">
    <div class="results-summary-card">
        <div class="results-summary-card__label">Total Voters</div>
        <div class="results-summary-card__value">{{ $totalVoters }}</div>
    </div>

    <div class="results-summary-card">
        <div class="results-summary-card__label">Votes Cast</div>
        <div class="results-summary-card__value">{{ $totalVotesCast }}</div>
    </div>

    <div class="results-summary-card">
        <div class="results-summary-card__label">Turnout</div>
        <div class="results-summary-card__value">{{ $turnout }}%</div>
    </div>

    <div class="results-summary-card">
        <div class="results-summary-card__label">Positions</div>
        <div class="results-summary-card__value">{{ $positions->count() }}</div>
    </div>
</div>

{{-- POSITIONS --}}
<div class="position-results">

@foreach($positions as $position)

@php
    $candidates = $position->candidates->sortByDesc('votes_count')->values();
    $total = $candidates->sum('votes_count');

    $colors = ['#1a1a1a','#4b4b4b','#7c7c7c','#adadad','#d4d4d4'];
@endphp

<div class="v-card" style="max-width:500px;">

    <div class="v-card__header">
        <div class="v-card__title">{{ $position->name }}</div>
        <div style="font-size:0.75rem;color:var(--ash);">
            {{ $total }} votes
        </div>
    </div>

    {{-- CHART --}}
    <div class="chart-wrap">
        <canvas id="chart_{{ $position->id }}" width="80" height="80"></canvas>

        <div class="chart-legend">
            @foreach($candidates->take(5) as $i => $c)
                <div class="chart-legend-item">
                    <div class="chart-legend-dot" style="background:{{ $colors[$i] }}"></div>
                    <span>{{ $c->name }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- CANDIDATES --}}
    @foreach($candidates as $i => $c)

        @php
            $pct = $total ? round(($c->votes_count / $total) * 100, 1) : 0;
            $isWinner = $i === 0 && $total > 0;
        @endphp

        <div class="result-candidate">

            <div class="rank-num {{ $isWinner ? 'rank-1' : '' }}">
                {{ $isWinner ? '🥇' : $i+1 }}
            </div>

            <img class="result-candidate__img {{ $isWinner ? 'winner-img' : '' }}"
                 src="{{ $c->image_url }}"
                 alt="">

            <div style="flex:1">
                <div class="result-name">
                    {{ $c->name }}
                    @if($isWinner) ⭐ Leading @endif
                </div>

                <div class="result-bar-row">
                    <div class="result-bar-track">
                        <div class="result-bar-fill {{ $isWinner ? 'is-winner' : 'is-other' }}"
                             style="width:{{ $pct }}%"></div>
                    </div>

                    <div class="result-count">{{ $c->votes_count }}</div>
                    <div class="result-pct">{{ $pct }}%</div>
                </div>
            </div>

        </div>

    @endforeach

</div>

@endforeach

</div>

@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const charts = @json(
        $positions->map(function($p){
            $c = $p->candidates->sortByDesc('votes_count')->values();
            return [
                'id' => $p->id,
                'labels' => $c->pluck('name'),
                'data' => $c->pluck('votes_count')
            ];
        })
    );

    const colors = ['#1a1a1a','#4b4b4b','#7c7c7c','#adadad','#d4d4d4'];

    charts.forEach(c => {
        const el = document.getElementById('chart_' + c.id);
        if (!el) return;

        new Chart(el, {
            type: 'doughnut',
            data: {
                labels: c.labels,
                datasets: [{
                    data: c.data,
                    backgroundColor: colors
                }]
            },
            options: {
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    });

});
</script>
@endpush