{{-- FILE: resources/views/admin/elections/create.blade.php --}}
@extends('layouts.app')
@section('title', 'New Election')
@section('breadcrumb') Admin / <a href="{{ route('admin.elections.index') }}">Elections</a> / <span>Create</span> @endsection

@section('content')
<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Create Election</div>
        <div class="v-page-header__sub">Set up a new election period</div>
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
        </div>
        <div class="v-card__body">
            <form method="POST" action="{{ route('admin.elections.store') }}">
                @csrf
                <div class="v-form-row">
                    <div class="v-form-group">
                        <label class="v-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                            Title <span style="color:var(--danger)">*</span>
                        </label>
                        <input class="v-input {{ $errors->has('title') ? 'error' : '' }}"
                               type="text" name="title" value="{{ old('title') }}"
                               placeholder="e.g. 2025 General Election" required autofocus>
                        @error('title') <p class="v-input-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="v-form-row">
                    <div class="v-form-group">
                        <label class="v-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            Description
                        </label>
                        <textarea class="v-textarea v-textarea--tall" name="description" placeholder="Optional details about this election...">{{ old('description') }}</textarea>
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
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
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
                            <input class="v-input" type="date" name="start_date" value="{{ old('start_date') }}">
                            @error('start_date') <p class="v-input-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="v-form-group">
                            <label class="v-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                End Date
                            </label>
                            <input class="v-input" type="date" name="end_date" value="{{ old('end_date') }}">
                            @error('end_date') <p class="v-input-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="v-form-row">
                    <div class="v-alert v-alert-info">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <strong>Note:</strong> Once created, the election will start as <span class="badge badge-pending">Pending</span>. Use the toggle button on the elections list to open voting.
                        </div>
                    </div>
                </div>

                <div class="v-form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Create Election
                    </button>
                    <a href="{{ route('admin.elections.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="v-card v-tips-card">
        <div class="v-card__header">
            <h3 class="v-card__title">Tips</h3>
        </div>
        <div class="v-card__body">
            <ul class="v-tips-list">
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Choose a clear, descriptive title for the election</span>
                </li>
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Set dates that give students enough time to vote</span>
                </li>
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Add positions and candidates after creating the election</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
.v-form-grid {
    display: grid;
    grid-template-columns: 1fr 260px;
    gap: 20px;
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
.v-form-row {
    margin-bottom: 18px;
}
.v-form-row__split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
@media (max-width: 500px) {
    .v-form-row__split {
        grid-template-columns: 1fr;
    }
}
.v-label svg {
    width: 13px;
    height: 13px;
    margin-right: 5px;
    vertical-align: middle;
    opacity: 0.55;
}
.v-textarea--tall {
    min-height: 90px;
}
.v-form-actions {
    display: flex;
    gap: 10px;
    padding-top: 10px;
    border-top: 1px solid var(--border);
    margin-top: 20px;
}
.v-tips-card {
    position: sticky;
    top: 90px;
}
.v-tips-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.v-tips-list li {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 8px 0;
    border-bottom: 1px solid var(--border-light);
    font-size: 0.82rem;
    color: var(--ash);
    line-height: 1.45;
}
.v-tips-list li:last-child {
    border-bottom: none;
}
.v-tips-list li svg {
    width: 14px;
    height: 14px;
    color: var(--success);
    flex-shrink: 0;
    margin-top: 1px;
}
</style>
@endsection