{{-- FILE: resources/views/admin/elections/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Election')
@section('breadcrumb') Admin / <a href="{{ route('admin.elections.index') }}">Elections</a> / <span>Edit</span> @endsection

@section('content')
<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Edit Election</div>
        <div class="v-page-header__sub">{{ $election->title }}</div>
    </div>
    <a href="{{ route('admin.elections.index') }}" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Elections
    </a>
</div>

<div class="v-form-grid">
    <div class="v-card v-form-card">
        <div class="v-card__header">
            <h3 class="v-card__title">Election Details</h3>
            <div>
                <span class="badge badge-{{ $election->status }}">{{ ucfirst($election->status) }}</span>
            </div>
        </div>
        <div class="v-card__body">
            <form method="POST" action="{{ route('admin.elections.update', $election) }}">
                @csrf @method('PUT')
                
                <div class="v-form-row">
                    <div class="v-form-group">
                        <label class="v-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                            Title <span style="color:var(--danger)">*</span>
                        </label>
                        <input class="v-input {{ $errors->has('title') ? 'error' : '' }}"
                               type="text" name="title" value="{{ old('title', $election->title) }}" required autofocus>
                        @error('title') <p class="v-input-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="v-form-row">
                    <div class="v-form-group">
                        <label class="v-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            Description
                        </label>
                        <textarea class="v-textarea v-textarea--tall" name="description">{{ old('description', $election->description) }}</textarea>
                    </div>
                </div>

                <div class="v-form-row">
                    <div class="v-form-group">
                        <label class="v-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Department <span style="color:var(--danger)">*</span>
                        </label>
                        <select class="v-select {{ $errors->has('department_id') ? 'error' : '' }}" name="department_id" required>
                            <option value="">— Select Department —</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $election->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->code }} - {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id') <p class="v-input-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="v-form-row">
                    <div class="v-form-row__split">
                        <div class="v-form-group">
                            <label class="v-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Start Date
                            </label>
                            <input class="v-input" type="date" name="start_date" value="{{ old('start_date', $election->start_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="v-form-group">
                            <label class="v-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                End Date
                            </label>
                            <input class="v-input" type="date" name="end_date" value="{{ old('end_date', $election->end_date?->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>

                <div class="v-form-row">
                    <div class="v-alert v-alert-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <strong>Status:</strong> To change the election status (start/end voting), use the toggle button on the elections list.
                        </div>
                    </div>
                </div>

                <div class="v-form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </button>
                    <a href="{{ route('admin.elections.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="v-card v-summary-card">
        <div class="v-card__header">
            <h3 class="v-card__title">Quick Summary</h3>
        </div>
        <div class="v-card__body">
            <div class="summary-list">
                <div class="summary-item">
                    <span class="summary-item__label">Created</span>
                    <span class="summary-item__value">{{ $election->created_at?->format('M d, Y') ?? '—' }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-item__label">Positions</span>
                    <span class="summary-item__value">{{ $election->positions->count() }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-item__label">Votes Cast</span>
                    <span class="summary-item__value">{{ $election->votes->count() }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-item__label">Last Updated</span>
                    <span class="summary-item__value">{{ $election->updated_at?->format('M d, Y') ?? '—' }}</span>
                </div>
            </div>
            <a href="{{ route('admin.elections.show', $election) }}" class="btn btn-secondary" style="width:100%;margin-top:16px;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                View Full Domain
            </a>
        </div>
    </div>
</div>

<style>
.v-form-grid {
    display: grid;
    grid-template-columns: 1fr 240px;
    gap: 16px;
    align-items: start;
}
@media (max-width: 900px) {
    .v-form-grid {
        grid-template-columns: 1fr;
    }
}
.v-form-card {
    max-width: 100%;
}
.v-card__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.v-card__header .v-card__title svg {
    width: 12px;
    height: 12px;
    margin-right: 4px;
    vertical-align: middle;
}
.v-form-row {
    margin-bottom: 14px;
}
.v-form-row__split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
@media (max-width: 500px) {
    .v-form-row__split {
        grid-template-columns: 1fr;
    }
}
.v-label svg {
    width: 11px;
    height: 11px;
    margin-right: 4px;
    vertical-align: middle;
    opacity: 0.5;
}
.v-textarea--tall {
    min-height: 80px;
}
.v-form-actions {
    display: flex;
    gap: 8px;
    padding-top: 8px;
    border-top: 1px solid var(--border);
    margin-top: 16px;
}
.v-summary-card {
    position: sticky;
    top: 80px;
}
.summary-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--border-light);
}
.summary-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.summary-item__label {
    font-size: 0.75rem;
    color: var(--ash);
}
.summary-item__value {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--ink);
}
</style>
@endsection