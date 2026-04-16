@extends('layouts.student')

@section('title', 'My Profile')

@section('content')
<div class="student-card" style="max-width:720px;">
    <div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;">
        <h2 style="margin-top:0;">My Profile</h2>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    @if(session('success'))
        <div class="v-alert v-alert-success" style="margin-bottom:20px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" style="display:grid;grid-template-columns:200px 1fr;gap:28px;align-items:start;">
        @csrf
        @method('PUT')

        <div style="text-align:center;">
            <div style="position:relative;display:inline-block;">
                <img id="preview-avatar" src="{{ auth()->user()->image_url }}" 
                     style="width:140px;height:140px;border-radius:50%;object-fit:cover;border:4px solid var(--border);box-shadow:var(--shadow);">
                <label for="image-upload" style="position:absolute;bottom:5px;right:5px;width:34px;height:34px;background:var(--ink);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;border:3px solid var(--white);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;color:#fff;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13h3m-3-4v4m-4-1l-4-4-4 4"/>
                    </svg>
                </label>
                <input id="image-upload" type="file" name="image" accept="image/jpeg,image/png,image/gif" style="display:none;" onchange="document.getElementById('preview-avatar').src = window.URL.createObjectURL(this.files[0])">
            </div>
            <p style="font-size:0.75rem;color:var(--ash);margin-top:8px;">Click to change photo</p>
        </div>

        <div>
            <div style="background:var(--surface);padding:20px;border-radius:12px;margin-bottom:16px;">
                <h3 style="font-size:1rem;margin-bottom:16px;">Personal Information</h3>
                
                <div class="v-form-group">
                    <label class="v-label" for="name">Full Name</label>
                    <input id="name" class="v-input" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <div style="font-size:0.75rem;color:var(--ash);margin-bottom:4px;">Email</div>
                        <div style="font-weight:500;">{{ auth()->user()->email }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;color:var(--ash);margin-bottom:4px;">Student ID</div>
                        <div style="font-weight:500;font-family:monospace;">{{ auth()->user()->student_id ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <div style="background:var(--surface);padding:20px;border-radius:12px;margin-bottom:16px;">
                <h3 style="font-size:1rem;margin-bottom:16px;">Academic Information</h3>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <div style="font-size:0.75rem;color:var(--ash);margin-bottom:4px;">Department</div>
                        <div style="font-weight:500;">
                            @if(auth()->user()->department)
                                <span class="badge badge-admin">{{ auth()->user()->department->code }}</span>
                            @else
                                Not assigned
                            @endif
                        </div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;color:var(--ash);margin-bottom:4px;">Course</div>
                        <div style="font-weight:500;">{{ auth()->user()->course?->name ?? 'Not assigned' }}</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Save Changes
            </button>
        </div>
    </form>
</div>

<style>
@media (max-width: 600px) {
    form[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection