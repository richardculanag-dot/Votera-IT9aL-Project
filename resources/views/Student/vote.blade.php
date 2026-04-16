@extends('layouts.student')

@section('title', 'Cast Ballot')

@push('scripts')
<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
.election-status-live {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.7rem;
    background: #dcfce7;
    color: #15803d;
    padding: 3px 10px;
    border-radius: 99px;
    font-weight: 600;
}
.election-status-closed {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.7rem;
    background: #fef2f2;
    color: #b91c1c;
    padding: 3px 10px;
    border-radius: 99px;
    font-weight: 600;
}
</style>
<script>
function checkElectionStatus() {
    fetch('/api/election/current')
        .then(response => response.json())
        .then(data => {
            const statusBadge = document.getElementById('election-status');
            if (data.election) {
                if (data.election.status === 'ongoing' && !data.election.is_locked) {
                    statusBadge.innerHTML = '<span style="width:6px;height:6px;background:#15803d;border-radius:50%;animation:pulse 2s infinite;"></span> LIVE - Voting Open';
                    statusBadge.className = 'election-status-live';
                } else {
                    statusBadge.innerHTML = '<span style="width:6px;height:6px;background:#b91c1c;border-radius:50%;"></span> Closed';
                    statusBadge.className = 'election-status-closed';
                    
                    const form = document.querySelector('form');
                    if (form) {
                        const inputs = form.querySelectorAll('input[type="radio"]');
                        inputs.forEach(input => input.disabled = true);
                        
                        const submitBtn = form.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.textContent = 'Voting Closed';
                            submitBtn.classList.add('btn-secondary');
                            submitBtn.classList.remove('btn-primary');
                        }
                    }
                    
                    const warning = document.createElement('div');
                    warning.className = 'v-alert v-alert-error';
                    warning.style.marginBottom = '20px';
                    warning.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> The election has been closed. You can no longer submit your vote.';
                    
                    const formContainer = document.querySelector('.student-card');
                    if (formContainer && !document.getElementById('election-closed-warning')) {
                        warning.id = 'election-closed-warning';
                        formContainer.insertBefore(warning, formContainer.firstChild.nextSibling);
                    }
                }
            }
        })
        .catch(error => console.error('Error checking election status:', error));
}

setInterval(checkElectionStatus, 5000);
checkElectionStatus();
</script>
@endpush

@section('content')
<div class="student-card">
    <div style="margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;">
        <div>
            <a href="{{ route('student.elections') }}" class="btn btn-secondary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Elections
            </a>
        </div>
        <span id="election-status" class="election-status-live">
            <span style="width:6px;height:6px;background:#15803d;border-radius:50%;animation:pulse 2s infinite;"></span>
            LIVE - Voting Open
        </span>
    </div>
    <h1 style="font-family:var(--font-serif);font-size:2rem;font-style:italic;color:var(--ink);margin-bottom:4px;">Cast Your Ballot</h1>
    <p style="margin-top:0;color:var(--ash);">{{ $election->title }}</p>

    @if($errors->any())
        <div class="v-alert v-alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Please select a candidate for every position before submitting.
        </div>
    @endif

    <form method="POST" action="{{ route('student.vote.store', $election) }}">
        @csrf

        @php
            $votedPositionIds = $votedPositionIds ?? [];
        @endphp
        
        @foreach($positions as $position)
            @if(!in_array($position->id, $votedPositionIds))
            <div class="vote-section">
                <div class="vote-section__header">
                    <div class="vote-section__position">{{ $position->name }}</div>
                    @if($position->description)
                        <p style="font-size:0.82rem;color:var(--ash);margin-top:4px;">{{ $position->description }}</p>
                    @endif
                </div>
                <div class="vote-candidates">
                    @foreach($position->candidates as $candidate)
                    <div class="vote-candidate">
                        <input
                            type="radio"
                            id="candidate_{{ $candidate->id }}"
                            name="votes[{{ $position->id }}]"
                            value="{{ $candidate->id }}"
                            {{ old("votes.{$position->id}") == $candidate->id ? 'checked' : '' }}
                        >
                        <label class="vote-candidate__label" for="candidate_{{ $candidate->id }}">
                            <img class="vote-candidate__img" src="{{ $candidate->image_url }}" alt="{{ $candidate->name }}">
                            <div class="vote-candidate__name">{{ $candidate->name }}</div>
                            @if($candidate->grade_level)
                                <div class="vote-candidate__grade">{{ $candidate->grade_level }}</div>
                            @endif
                            @if($candidate->partylist)
                                <div class="vote-candidate__grade" style="color:#15803d;font-weight:600;">{{ $candidate->partylist }}</div>
                            @endif
                            @if($candidate->platform)
                                <div class="vote-candidate__platform">{{ $candidate->platform }}</div>
                            @endif
                            <div class="vote-candidate__check"></div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach

        <div style="border-top:2px solid var(--ink);padding-top:24px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
            <p style="font-size:0.82rem;color:var(--ash);max-width:380px;">
                ⚠ Once submitted, your votes <strong>cannot be changed</strong>. Please review your selections carefully.
            </p>
            <button type="submit" class="btn btn-primary btn-lg">
                Submit Ballot
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </button>
        </div>
    </form>
</div>
@endsection