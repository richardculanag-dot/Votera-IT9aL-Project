{{-- FILE: resources/views/admin/staff/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Staff Accounts')
@section('breadcrumb') Admin / <span>Staff</span> @endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Staff Accounts</div>
        <div class="v-page-header__sub">Manage staff user accounts</div>
    </div>
    <div class="v-page-header__actions">
        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Staff
        </a>
    </div>
</div>

<div class="v-card">
    <div class="v-card__body" style="padding:0;">
        <div class="v-table-wrap">
            <table class="v-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $member)
                    <tr>
                        <td style="font-weight:600;">{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>
                            @if($member->department)
                                <span class="badge badge-staff">{{ $member->department->code }}</span>
                            @else
                                <span style="color:var(--ash);">All Departments</span>
                            @endif
                        </td>
                        <td style="color:var(--ash);font-size:0.8rem;">{{ $member->created_at->format('M d, Y') }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.staff.edit', $member) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.staff.destroy', $member) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remove this staff account?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:40px;color:var(--ash);">
                            No staff accounts found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top:20px;">
    {{ $staff->links() }}
</div>

@endsection