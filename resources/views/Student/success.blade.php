@extends('layouts.student')

@section('title', 'Vote Submitted')

@section('content')
<div class="student-card" style="max-width:760px;margin:0 auto;">
        {{-- Brand --}}
        <div style="margin-bottom:28px;">
            <div style="width:36px;height:36px;background:var(--ink);border-radius:9px;display:grid;place-items:center;margin:0 auto 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2" style="width:18px;height:18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0121 17.5c0 2.485-3.582 4.5-8 4.5S5 19.985 5 17.5c0-1.127.38-2.18 1.04-3.078L12 14z"/>
                </svg>
            </div>
            <div style="font-size:0.68rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--ash);">Votera — 2025 General Election</div>
        </div>

        <div class="v-success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1>Vote Submitted!</h1>
        <p>Thank you, <strong>{{ auth()->user()->name }}</strong>. Your ballot has been recorded successfully. Your participation matters.</p>

        <div style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:24px;text-align:left;">
            <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--ash);font-weight:700;margin-bottom:8px;">Ballot Summary</div>
            @foreach(auth()->user()->votes()->with(['candidate','position'])->get() as $vote)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:5px 0;border-bottom:1px solid var(--border);">
                <span style="font-size:0.8rem;color:var(--ash);">{{ $vote->position->name }}</span>
                <span style="font-size:0.82rem;font-weight:600;color:var(--ink);">{{ $vote->candidate->name }}</span>
            </div>
            @endforeach
        </div>

        <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-block">Go to Student Dashboard</a>
        <a href="{{ route('student.history') }}" class="btn btn-secondary btn-block" style="margin-top:10px;">View Vote History</a>
</div>
@endsection