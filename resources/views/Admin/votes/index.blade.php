{{-- FILE: resources/views/admin/votes/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Votes')
@section('breadcrumb') Admin / <span>Votes</span> @endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Votes</div>
        <div class="v-page-header__sub">All recorded votes</div>
    </div>
</div>

<div class="v-card">
    <div class="v-card__header">
        <span class="v-card__title">Filter Votes</span>
    </div>
    <div class="v-card__body">
        <form method="GET" action="{{ route('admin.votes.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;">
            <div class="v-form-group" style="margin-bottom:0;min-width:200px;">
                <select name="election_id" class="v-select">
                    <option value="">All Elections</option>
                    @foreach($elections as $election)
                        <option value="{{ $election->id }}" {{ request('election_id') == $election->id ? 'selected' : '' }}>
                            {{ $election->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('admin.votes.index') }}" class="btn btn-secondary btn-sm">Clear</a>
        </form>
    </div>
</div>

<div class="v-card" style="margin-top:20px;">
    <div class="v-card__body" style="padding:0;">
        <div class="v-table-wrap">
            <table class="v-table">
                <thead>
                    <tr>
                        <th>Voter</th>
                        <th>Election</th>
                        <th>Position</th>
                        <th>Candidate</th>
                        <th>Voted At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($votes as $vote)
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ $vote->user->name ?? 'Unknown' }}</div>
                            <div style="font-size:0.75rem;color:var(--ash);">{{ $vote->user->email ?? '' }}</div>
                        </td>
                        <td style="font-size:0.85rem;">{{ $vote->election->title ?? '-' }}</td>
                        <td style="font-size:0.85rem;">{{ $vote->position->name ?? '-' }}</td>
                        <td>
                            @if($vote->candidate)
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <img src="{{ $vote->candidate->image_url }}" style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
                                    <span style="font-weight:600;">{{ $vote->candidate->name }}</span>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td style="color:var(--ash);font-size:0.8rem;">{{ $vote->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:40px;color:var(--ash);">
                            No votes found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top:20px;">
    {{ $votes->withQueryString()->links() }}
</div>

@endsection