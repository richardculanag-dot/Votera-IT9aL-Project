@extends('layouts.app')

@section('title', 'Edit Candidate')

@section('breadcrumb')
    {{ ucfirst($routePrefix) }} / <a href="{{ route($routePrefix . '.elections.index') }}">Elections</a> / <a href="{{ route($routePrefix . '.elections.positions.index', $election) }}">Positions</a> / <a href="{{ route($routePrefix . '.elections.positions.candidates.index', [$election, $position]) }}">Candidates</a> / <span>Edit</span>
@endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Edit Candidate</div>
        <div class="v-page-header__sub">Update profile for "{{ $candidate->name }}"</div>
    </div>
    <a href="{{ route($routePrefix . '.elections.positions.candidates.index', [$election, $position]) }}" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back
    </a>
</div>

<div class="v-card" style="max-width:580px;">
    <div class="v-card__body">
        {{-- Current photo preview --}}
        @if($candidate->image)
        <div class="v-form-group" style="display:flex;align-items:center;gap:14px;padding:14px;background:var(--surface);border-radius:9px;margin-bottom:20px;">
            <img src="{{ asset('storage/' . $candidate->image) }}" alt="{{ $candidate->name }}" style="width:54px;height:54px;border-radius:50%;object-fit:cover;border:2px solid var(--border);">
            <div>
                <div style="font-weight:600;font-size:0.88rem;">Current Photo</div>
                <small>Upload a new file below to replace it.</small>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route($routePrefix . '.elections.positions.candidates.update', [$election, $position, $candidate]) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="v-form-group" style="grid-column:1/-1;">
                    <label class="v-label" for="name">Full Name <span style="color:var(--danger)">*</span></label>
                    <input id="name" class="v-input {{ $errors->has('name') ? 'error' : '' }}" type="text" name="name" value="{{ old('name', $candidate->name) }}" required autofocus>
                    @error('name') <p class="v-input-error">{{ $message }}</p> @enderror
                </div>

                <div class="v-form-group">
                    <label class="v-label">Position</label>
                    <input class="v-input" type="text" value="{{ $position->name }}" disabled>
                </div>

                <div class="v-form-group">
                    <label class="v-label" for="grade_level">Grade Level</label>
                    <input id="grade_level" class="v-input" type="text" name="grade_level" value="{{ old('grade_level', $candidate->grade_level) }}" placeholder="e.g. Grade 12">
                </div>

                <div class="v-form-group">
                    <label class="v-label" for="partylist">Partylist (Optional)</label>
                    <input id="partylist" class="v-input" type="text" name="partylist" value="{{ old('partylist', $candidate->partylist) }}" placeholder="e.g. Independent, KASAMIS">
                    <p class="v-input-hint">Leave blank if candidate is independent</p>
                </div>
            </div>

            <div class="v-form-group">
                <label class="v-label" for="platform">Campaign Platform</label>
                <textarea id="platform" class="v-textarea" name="platform">{{ old('platform', $candidate->platform) }}</textarea>
            </div>

            <div class="v-form-group">
                <label class="v-label" for="image">Replace Profile Photo</label>
                <input id="image" class="v-input" type="file" name="image" accept="image/jpeg,image/png,image/gif" style="padding:7px 12px;">
                <p class="v-input-hint">Leave blank to keep the current photo.</p>
                @error('image') <p class="v-input-error">{{ $message }}</p> @enderror
            </div>

            <div class="v-divider"></div>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route($routePrefix . '.elections.positions.candidates.index', [$election, $position]) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection