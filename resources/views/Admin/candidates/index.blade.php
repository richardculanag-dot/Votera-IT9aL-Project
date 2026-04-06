@extends('layouts.app')

@section('title', 'Candidates')

@section('breadcrumb')
    Admin / <span>Candidates</span>
@endsection

@section('content')

<div class="v-page-header">
    <div>
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
            <span class="badge badge-open" style="font-size:0.65rem;">2025 General Election</span>
        </div>
        <div class="v-page-header__title">Digital Archive</div>
        <div class="v-page-header__sub">Manage the official roster of students running for leadership positions. Curate profiles, verify eligibility, and update statements.</div>
    </div>
    <div class="v-page-header__actions">
        <a href="{{ route('admin.candidates.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Candidate
        </a>
    </div>
</div>

@if($candidates->isEmpty())
    <div class="v-empty" style="background:var(--white);border:1px solid var(--border);border-radius:var(--radius-lg);padding:60px 24px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <h3>No candidates yet</h3>
        <p>Add the first candidate to populate the Digital Archive.</p>
        <a href="{{ route('admin.candidates.create') }}" class="btn btn-primary mt-3">Add Candidate</a>
    </div>
@else
    <div class="v-candidate-grid">
        @foreach($candidates as $candidate)
        <div class="v-candidate-card">
            <img class="v-candidate-card__img" src="{{ $candidate->image_url }}" alt="{{ $candidate->name }}">
            <div class="v-candidate-card__body">
                <div class="v-candidate-card__position">{{ $candidate->position->name ?? '—' }}</div>
                <div class="v-candidate-card__name">{{ $candidate->name }}</div>
                @if($candidate->platform)
                    <div class="v-candidate-card__platform">{{ $candidate->platform }}</div>
                @endif
            </div>
            <div class="v-candidate-card__footer">
                <div style="display:flex;align-items:center;gap:6px;">
                    <span class="badge badge-student" style="font-size:0.65rem;">{{ $candidate->votes_count }} votes</span>
                    @if($candidate->grade_level)
                        <span style="font-size:0.72rem;color:var(--ash);">{{ $candidate->grade_level }}</span>
                    @endif
                </div>
                <div class="v-candidate-card__actions">
                    <a href="{{ route('admin.candidates.edit', $candidate) }}" class="btn btn-secondary btn-sm" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <form method="POST" action="{{ route('admin.candidates.destroy', $candidate) }}" onsubmit="return confirm('Remove {{ $candidate->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Nominate new card --}}
        <a href="{{ route('admin.candidates.create') }}" class="v-candidate-card" style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:260px;border-style:dashed;background:transparent;box-shadow:none;text-decoration:none;gap:10px;color:var(--ash);">
            <div style="width:44px;height:44px;border:2px dashed var(--border);border-radius:50%;display:grid;place-items:center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            </div>
            <div style="font-weight:600;font-size:0.88rem;color:var(--ink);">Nominate New Candidate</div>
            <div style="font-size:0.75rem;text-align:center;padding:0 16px;">Begin the verification process for a student nominee</div>
        </a>
    </div>
@endif

@endsection