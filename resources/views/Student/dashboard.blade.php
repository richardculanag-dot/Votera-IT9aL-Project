@extends('layouts.student')

@push('scripts')
<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>
<script>
function updateStudentDashboard() {
    fetch('/api/election/current')
        .then(response => response.json())
        .then(data => {
            const liveCountEl = document.getElementById('live-election-count');
            if (data.election && data.election.status === 'ongoing' && !data.election.is_locked) {
                if (liveCountEl) liveCountEl.textContent = '1';
                
                const liveElectionsList = document.getElementById('live-elections-list');
                if (liveElectionsList) {
                    const electionItem = liveElectionsList.querySelector('.live-election-item');
                    if (electionItem) {
                        electionItem.style.display = 'block';
                        const titleEl = electionItem.querySelector('.election-title');
                        if (titleEl) titleEl.textContent = data.election.title;
                    }
                }
            } else {
                if (liveCountEl) liveCountEl.textContent = '0';
            }
        })
        .catch(error => console.error('Error fetching election status:', error));
}

setInterval(updateStudentDashboard, 10000);
</script>
@endpush

@section('content')
<div class="student-grid" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));">
    <div class="student-card">
        <div style="font-size:.82rem;color:var(--ash);">
            <span style="display:inline-flex;align-items:center;gap:4px;">
                <span style="width:6px;height:6px;background:#15803d;border-radius:50%;animation:pulse 2s infinite;"></span>
                Live Elections
            </span>
        </div>
        <div style="font-size:1.8rem;font-weight:700;" id="live-election-count">{{ $liveElections->count() }}</div>
    </div>
    <div class="student-card">
        <div style="font-size:.82rem;color:var(--ash);">Upcoming Elections</div>
        <div style="font-size:1.8rem;font-weight:700;">{{ $upcomingElections->count() }}</div>
    </div>
    <div class="student-card">
        <div style="font-size:.82rem;color:var(--ash);">Total Votes Cast</div>
        <div style="font-size:1.8rem;font-weight:700;">{{ $voteCount }}</div>
    </div>
</div>

<div class="student-grid" style="margin-top:16px;grid-template-columns:1fr 1fr;">
    <div class="student-card">
        <h3 style="margin:0 0 10px;">
            <span style="display:inline-flex;align-items:center;gap:4px;">
                <span style="width:6px;height:6px;background:#15803d;border-radius:50%;animation:pulse 2s infinite;"></span>
                Open in Your Department
            </span>
        </h3>
        <div id="live-elections-list">
            @forelse($liveElections as $election)
                <div class="live-election-item" style="padding:10px 0;border-bottom:1px solid var(--border);">
                    <div class="election-title" style="font-weight:600;">{{ $election->title }}</div>
                    <div style="font-size:.85rem;color:var(--ash);">{{ $election->description }}</div>
                    <a class="btn btn-primary btn-sm" style="margin-top:8px;" href="{{ route('student.vote', $election) }}">Vote Now</a>
                </div>
            @empty
                <div class="live-election-item" style="display:none;">
                    <div class="election-title"></div>
                </div>
                <p style="color:var(--ash);">No live election right now.</p>
            @endforelse
        </div>
        @if($upcomingElections->count() > 0)
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);">
                <div style="font-size:.78rem;color:var(--ash);font-weight:600;margin-bottom:6px;">Coming Soon</div>
                @foreach($upcomingElections->take(2) as $election)
                    <div style="font-size:.85rem;padding:4px 0;color:var(--ash);">{{ $election->title }}</div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="student-card">
        <h3 style="margin:0 0 10px;">Recent Activity</h3>
        @forelse($recentVotes as $vote)
            <div style="padding:8px 0;border-bottom:1px solid var(--border);">
                <div style="font-weight:600;">{{ $vote->position?->name }}: {{ $vote->candidate?->name }}</div>
                <div style="font-size:.82rem;color:var(--ash);">{{ $vote->election?->title ?? 'General Voting' }} - {{ $vote->created_at?->format('M d, Y h:i A') }}</div>
            </div>
        @empty
            <p style="color:var(--ash);">No submitted votes yet.</p>
        @endforelse
    </div>
</div>
@endsection