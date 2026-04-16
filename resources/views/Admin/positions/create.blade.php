@extends('layouts.app')

@section('title', 'Add Position')

@section('breadcrumb')
    {{ ucfirst($routePrefix) }} / <a href="{{ route($routePrefix . '.elections.index') }}">Elections</a> / <a href="{{ route($routePrefix . '.elections.positions.index', $election) }}">Positions</a> / <span>Add</span>
@endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Add Position</div>
        <div class="v-page-header__sub">{{ $election->title }}</div>
    </div>
    <a href="{{ route($routePrefix . '.elections.positions.index', $election) }}" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back
    </a>
</div>

<div class="v-card" style="max-width:540px;">
    <div class="v-card__body">
        <form method="POST" action="{{ route($routePrefix . '.elections.positions.store', $election) }}">
            @csrf

            <div class="v-form-group">
                <label class="v-label" for="name">Position Name <span style="color:var(--danger)">*</span></label>
                <input id="name" class="v-input {{ $errors->has('name') ? 'error' : '' }}" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Student Council President" required autofocus>
                @error('name') <p class="v-input-error">{{ $message }}</p> @enderror
            </div>

            <div class="v-form-group">
                <label class="v-label" for="description">Description</label>
                <textarea id="description" class="v-textarea {{ $errors->has('description') ? 'error' : '' }}" name="description" placeholder="Brief description of this position's responsibilities…">{{ old('description') }}</textarea>
                @error('description') <p class="v-input-error">{{ $message }}</p> @enderror
            </div>

            <div class="v-form-group">
                <label class="v-label" for="order">Display Order</label>
                <input id="order" class="v-input" type="number" name="order" value="{{ old('order', 0) }}" min="0" max="999" style="max-width:120px;">
                <p class="v-input-hint">Lower numbers appear first on the ballot.</p>
            </div>

            <div class="v-divider"></div>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">Save Position</button>
                <a href="{{ route($routePrefix . '.elections.positions.index', $election) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection