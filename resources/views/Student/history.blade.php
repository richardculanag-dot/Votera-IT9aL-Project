@extends('layouts.student')

@section('title', 'Vote History')

@section('content')
<div class="student-card">
    <div style="margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;">
        <h2 style="margin-top:0;">My Vote History</h2>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>
    @forelse($votes as $vote)
        <div style="padding:12px 0;border-bottom:1px solid var(--border);">
            <div style="font-weight:600;">{{ $vote->position?->name }} - {{ $vote->candidate?->name }}</div>
            <div style="font-size:.84rem;color:var(--ash);">
                Election: {{ $vote->election?->title ?? 'General Voting' }} |
                Submitted: {{ $vote->created_at?->format('M d, Y h:i A') }}
            </div>
        </div>
    @empty
        <p style="color:var(--ash);">No vote history yet.</p>
    @endforelse

    <div style="margin-top:14px;">
        {{ $votes->links() }}
    </div>
</div>
@endsection
