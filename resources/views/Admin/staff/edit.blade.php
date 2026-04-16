{{-- FILE: resources/views/admin/staff/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Staff')
@section('breadcrumb') Admin / <a href="{{ route('admin.staff.index') }}">Staff</a> / <span>Edit</span> @endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Edit Staff Account</div>
    </div>
    <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back
    </a>
</div>

<div class="v-card" style="max-width:500px;">
    <div class="v-card__body">
        <form method="POST" action="{{ route('admin.staff.update', $staff) }}">
            @csrf
            @method('PUT')

            <div class="v-form-group">
                <label class="v-label" for="name">Full Name <span style="color:var(--danger)">*</span></label>
                <input id="name" class="v-input {{ $errors->has('name') ? 'error' : '' }}" type="text" name="name" value="{{ old('name', $staff->name) }}" required autofocus>
                @error('name') <p class="v-input-error">{{ $message }}</p> @enderror
            </div>

            <div class="v-form-group">
                <label class="v-label" for="email">Email Address <span style="color:var(--danger)">*</span></label>
                <input id="email" class="v-input {{ $errors->has('email') ? 'error' : '' }}" type="email" name="email" value="{{ old('email', $staff->email) }}" required>
                @error('email') <p class="v-input-error">{{ $message }}</p> @enderror
            </div>

            <div class="v-form-group">
                <label class="v-label" for="department_id">Department (Optional)</label>
                <select id="department_id" class="v-select" name="department_id">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $staff->department_id) == $dept->id ? 'selected' : '' }}>
                            {{ $dept->code }} - {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="v-form-group">
                <label class="v-label" for="password">New Password (Optional)</label>
                <input id="password" class="v-input" type="password" name="password" placeholder="Leave blank to keep current">
                <p class="v-input-hint">Only fill if you want to reset the password</p>
            </div>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection