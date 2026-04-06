@extends('layouts.app')

@section('title', 'Voting Control')

@section('breadcrumb')
    Admin / <span>Voting Control</span>
@endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Voting Control</div>
        <div class="v-page-header__sub">Open or close voting for all registered students</div>
    </div>
</div>

<div class="v-toggle-card">
    <div style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--ash);font-weight:700;">Current Status</div>

    <div class="v-toggle-status {{ $votingStatus }}">
        {{ strtoupper($votingStatus) }}
    </div>

    <p class="v-toggle-desc">
        @if($votingStatus === 'open')
            Voting is currently <strong>open</strong>. Students can log in and submit their ballots. Click below to close the election.
        @else
            Voting is currently <strong>closed</strong>. Students will see a "Voting Closed" message when they log in. Click below to open the election.
        @endif
    </p>

    <form method="POST" action="{{ route('admin.voting-control.toggle') }}">
        @csrf
        <button type="submit" class="btn btn-lg btn-block {{ $votingStatus === 'open' ? 'btn-danger' : 'btn-success' }}"
            onclick="return confirm('{{ $votingStatus === 'open' ? 'Close voting? Students will no longer be able to vote.' : 'Open voting? Students will be able to cast their ballots.' }}')">
            @if($votingStatus === 'open')
                🔒 &nbsp;Close Voting Now
            @else
                🔓 &nbsp;Open Voting Now
            @endif
        </button>
    </form>

    <p style="margin-top:16px;font-size:0.76rem;color:var(--ash);">
        Last updated: {{ \DB::table('voting_settings')->where('key','voting_status')->value('updated_at') ?? 'N/A' }}
    </p>
</div>

<div class="v-card" style="max-width:460px;margin:20px auto 0;">
    <div class="v-card__header">
        <span class="v-card__title">⚠ Important Notes</span>
    </div>
    <div class="v-card__body">
        <ul style="padding-left:18px;display:flex;flex-direction:column;gap:7px;">
            <li style="font-size:0.85rem;color:var(--ash);">Students who have already voted <strong>cannot vote again</strong>, even if voting is reopened.</li>
            <li style="font-size:0.85rem;color:var(--ash);">Closing voting does <strong>not</strong> delete any submitted votes.</li>
            <li style="font-size:0.85rem;color:var(--ash);">You can toggle voting status multiple times as needed.</li>
            <li style="font-size:0.85rem;color:var(--ash);">View the <a href="{{ route('admin.results') }}">Results page</a> to see live vote counts.</li>
        </ul>
    </div>
</div>

@endsection