@extends('layouts.app')

@section('title', 'Add Candidate')

@section('breadcrumb')
    Admin / <a href="{{ route('admin.candidates.index') }}">Candidates</a> / <span>Add</span>
@endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Add Candidate</div>
        <div class="v-page-header__sub">Register a new student nominee</div>
    </div>
    <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div class="v-card" style="max-width:580px;">
    <div class="v-card__body">
        <form method="POST" action="{{ route('admin.candidates.store') }}" enctype="multipart/form-data">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="v-form-group" style="grid-column:1/-1;">
                    <label class="v-label" for="name">Full Name <span style="color:var(--danger)">*</span></label>
                    <input id="name" class="v-input {{ $errors->has('name') ? 'error' : '' }}" type="text" name="name" value="{{ old('name') }}" placeholder="Student's full name" required autofocus>
                    @error('name') <p class="v-input-error">{{ $message }}</p> @enderror
                </div>

                <div class="v-form-group">
                    <label class="v-label" for="position_id">Position <span style="color:var(--danger)">*</span></label>
                    <select id="position_id" class="v-select {{ $errors->has('position_id') ? 'error' : '' }}" name="position_id" required>
                        <option value="">— Select Position —</option>
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                {{ $position->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('position_id') <p class="v-input-error">{{ $message }}</p> @enderror
                </div>

                <div class="v-form-group">
                    <label class="v-label" for="grade_level">Grade Level</label>
                    <input id="grade_level" class="v-input" type="text" name="grade_level" value="{{ old('grade_level') }}" placeholder="e.g. Grade 12">
                </div>
            </div>

            <div class="v-form-group">
                <label class="v-label" for="platform">Campaign Platform</label>
                <textarea id="platform" class="v-textarea" name="platform" placeholder="Brief description of their campaign platform and goals…">{{ old('platform') }}</textarea>
            </div>

            <div class="v-form-group">
                <label class="v-label" for="image">Profile Photo</label>
                <input id="image" class="v-input" type="file" name="image" accept="image/jpeg,image/png,image/gif" style="padding:7px 12px;">
                <p class="v-input-hint">JPG, PNG or GIF. Max 2MB. Leave blank to use an auto-generated avatar.</p>
                @error('image') <p class="v-input-error">{{ $message }}</p> @enderror
            </div>

            <div class="v-divider"></div>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">Add Candidate</button>
                <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection