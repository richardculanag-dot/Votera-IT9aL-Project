@extends('layouts.app')

@section('title', 'Add Position')

@section('breadcrumb')
    Admin / <a href="{{ route('admin.positions.index') }}">Positions</a> / <span>Add</span>
@endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Add Position</div>
        <div class="v-page-header__sub">Create a new elective position</div>
    </div>
    <a href="{{ route('admin.positions.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div class="v-card" style="max-width:540px;">
    <div class="v-card__body">
        <form method="POST" action="{{ route('admin.positions.store') }}">
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
                <a href="{{ route('admin.positions.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection