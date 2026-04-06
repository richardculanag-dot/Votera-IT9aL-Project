<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cast Your Vote — Votera</title>
    <link rel="stylesheet" href="{{ asset('css/votera.css') }}">
    <style>
        body { background: var(--surface); }
        .student-wrap { max-width: 780px; margin: 0 auto; padding: 32px 20px 60px; }
        .student-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }
        .student-brand { display: flex; align-items: center; gap: 10px; }
        .student-brand__logo { width: 36px; height: 36px; background: var(--ink); border-radius: 9px; display: grid; place-items: center; }
        .student-brand__logo svg { width: 18px; height: 18px; color: #fff; }
        .student-brand__name { font-weight: 700; font-size: 1rem; letter-spacing: 0.04em; }
        .student-brand__year { font-size: 0.72rem; color: var(--ash); }
    </style>
</head>
<body>
<div class="student-wrap">

    {{-- Header --}}
    <div class="student-header">
        <div class="student-brand">
            <div class="student-brand__logo">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0121 17.5c0 2.485-3.582 4.5-8 4.5S5 19.985 5 17.5c0-1.127.38-2.18 1.04-3.078L12 14z"/>
                </svg>
            </div>
            <div>
                <div class="student-brand__name">VOTERA</div>
                <div class="student-brand__year">2025 General Election</div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="text-align:right;">
                <div style="font-size:0.85rem;font-weight:600;">{{ auth()->user()->name }}</div>
                <div style="font-size:0.72rem;color:var(--ash);">{{ auth()->user()->student_id ?? auth()->user()->email }}</div>
            </div>
            <div style="width:34px;height:34px;background:var(--ink);border-radius:50%;display:grid;place-items:center;color:#fff;font-weight:700;font-size:0.8rem;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-secondary btn-sm">Sign out</button>
            </form>
        </div>
    </div>

    {{-- Title --}}
    <div style="margin-bottom:28px;">
        <h1 style="font-family:var(--font-serif);font-size:2rem;font-style:italic;color:var(--ink);">Cast Your Ballot</h1>
        <p style="margin-top:6px;">Select one candidate per position. Your vote is final once submitted.</p>
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="v-alert v-alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Please select a candidate for every position before submitting.
        </div>
    @endif

    <form method="POST" action="{{ route('student.vote.store') }}">
        @csrf

        @foreach($positions as $position)
            @if(!in_array($position->id, $votedPosition))
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
</body>
</html>