{{-- FILE: resources/views/admin/staff/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Add Staff')
@section('breadcrumb') Admin / <a href="{{ route('admin.staff.index') }}">Staff</a> / <span>Add</span> @endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Add Staff Account</div>
    </div>
    <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back
    </a>
</div>

<div class="v-card" style="max-width:500px;">
    <div class="v-card__body">
        <form method="POST" action="{{ route('admin.staff.store') }}">
            @csrf

            <div class="v-form-group">
                <label class="v-label" for="name">Full Name <span style="color:var(--danger)">*</span></label>
                <input id="name" class="v-input {{ $errors->has('name') ? 'error' : '' }}" type="text" name="name" value="{{ old('name') }}" required autofocus>
                @error('name') <p class="v-input-error">{{ $message }}</p> @enderror
            </div>

            <div class="v-form-group">
                <label class="v-label" for="email">Email Address <span style="color:var(--danger)">*</span></label>
                <input id="email" class="v-input {{ $errors->has('email') ? 'error' : '' }}" type="email" name="email" value="{{ old('email') }}" required>
                @error('email') <p class="v-input-error">{{ $message }}</p> @enderror
            </div>

            <div class="v-form-group">
                <label class="v-label" for="department_id">Department (Optional)</label>
                <select id="department_id" class="v-select" name="department_id">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->code }} - {{ $dept->name }}</option>
                    @endforeach
                </select>
                <p class="v-input-hint">Leave empty for full system access</p>
            </div>

            <div class="v-form-group">
                <label class="v-label">Temporary Password</label>
                <input class="v-input" type="text" value="password" disabled>
                <p class="v-input-hint">Staff can change this after first login</p>
            </div>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">Create Staff Account</button>
                <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection