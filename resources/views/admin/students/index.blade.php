{{-- FILE: resources/views/admin/students/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Students')
@section('breadcrumb') Admin / <span>Students</span> @endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Students</div>
        <div class="v-page-header__sub">All registered students across departments</div>
    </div>
</div>

<div class="v-card">
    <div class="v-card__header">
        <span class="v-card__title">Filter Students</span>
    </div>
    <div class="v-card__body">
        <form method="GET" action="{{ route('admin.students.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;">
            <div class="v-form-group" style="margin-bottom:0;flex:1;min-width:200px;">
                <input type="text" name="search" class="v-input" placeholder="Search by name, email, or student ID..." value="{{ request('search') }}">
            </div>
            <div class="v-form-group" style="margin-bottom:0;min-width:180px;">
                <select name="department_id" class="v-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->code }} - {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-sm">Clear</a>
        </form>
    </div>
</div>

<div class="v-card" style="margin-top:20px;">
    <div class="v-card__body" style="padding:0;">
        <div class="v-table-wrap">
            <table class="v-table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td style="font-family:monospace;font-size:0.8rem;">{{ $student->student_id ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.students.show', $student) }}" style="font-weight:600;color:var(--ink);">
                                {{ $student->name }}
                            </a>
                        </td>
                        <td>{{ $student->email }}</td>
                        <td>
                            @if($student->department)
                                <span class="badge badge-admin">{{ $student->department->code }}</span>
                            @else
                                <span style="color:var(--ash);">-</span>
                            @endif
                        </td>
                        <td style="color:var(--ash);">{{ $student->course->name ?? '-' }}</td>
                        <td style="color:var(--ash);font-size:0.8rem;">{{ $student->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:var(--ash);">
                            No students found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top:20px;">
    {{ $students->withQueryString()->links() }}
</div>

@endsection