@extends('layouts.app')

@section('title', 'Edit Position')

@section('breadcrumb')
    Admin / <a href="{{ route('admin.positions.index') }}">Positions</a> / <span>Edit</span>
@endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Edit Position</div>
        <div class="v-page-header__sub">Update "{{ $position->name }}"</div>
    </div>
    <a href="{{ route('admin.positions.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div class="v-card" style="max-width:540px;">
    <div class="v-card__body">
        <form method="POST" action="{{ route('admin.positions.update', $position) }}">
            @csrf @method('PUT')

            <div class="v-form-group">
                <label class="v-label" for="name">Position Name <span style="color:var(--danger)">*</span></label>
                <input id="name" class="v-input {{ $errors->has('name') ? 'error' : '' }}" type="text" name="name" value="{{ old('name', $position->name) }}" required autofocus>
                @error('name') <p class="v-input-error">{{ $message }}</p> @enderror
            </div>

            <div class="v-form-group">
                <label class="v-label" for="description">Description</label>
                <textarea id="description" class="v-textarea {{ $errors->has('description') ? 'error' : '' }}" name="description">{{ old('description', $position->description) }}</textarea>
                @error('description') <p class="v-input-error">{{ $message }}</p> @enderror
            </div>

            <div class="v-form-group">
                <label class="v-label" for="order">Display Order</label>
                <input id="order" class="v-input" type="number" name="order" value="{{ old('order', $position->order) }}" min="0" style="max-width:120px;">
            </div>

            <div class="v-divider"></div>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('admin.positions.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection