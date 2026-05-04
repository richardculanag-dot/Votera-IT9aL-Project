{{-- FILE: resources/views/Admin/elections/trash/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Election trash')
@section('breadcrumb') Admin / <a href="{{ route('admin.elections.index') }}">Elections</a> / <span>Trash</span> @endsection

@section('content')
<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Election trash</div>
        <div class="v-page-header__sub">Soft-deleted elections stay here for {{ $retentionDays }} days, then are removed automatically. You can restore or delete permanently at any time.</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('admin.elections.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to elections
        </a>
        @if($elections->isNotEmpty())
        <form method="POST" action="{{ route('admin.elections.trash.empty') }}" onsubmit="return confirm('Permanently delete every election in trash? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <label class="trash-empty__confirm">
                <input type="checkbox" name="confirm" value="1" required>
                <span>I understand this is permanent</span>
            </label>
            <button type="submit" class="btn btn-secondary" style="border-color:var(--danger-border);color:var(--danger);">
                Clear trash
            </button>
        </form>
        @endif
    </div>
</div>

@if($errors->any())
    <div class="v-alert v-alert-error" style="margin-bottom:14px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ $errors->first() }}
    </div>
@endif

@if($elections->isEmpty())
    <div class="v-empty" style="padding:72px 24px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        <h3>Trash is empty</h3>
        <p>Deleted elections will appear here until they are restored, cleared, or automatically purged after {{ $retentionDays }} days.</p>
    </div>
@else
<div class="v-card">
    <div class="v-card__body" style="padding:0;">
        <table class="trash-table">
            <thead>
                <tr>
                    <th>Election</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Deleted</th>
                    <th>Auto-purge after</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($elections as $election)
                <tr>
                    <td><strong>{{ $election->title }}</strong></td>
                    <td>{{ $election->department?->name ?? '—' }}</td>
                    <td><span class="badge badge-{{ $election->status }}">{{ ucfirst($election->status) }}</span></td>
                    <td>{{ $election->deleted_at?->format('M j, Y g:i A') }}</td>
                    <td>{{ $election->trashPurgesAt()?->format('M j, Y') }}</td>
                    <td>
                        <div class="trash-table__actions">
                            <form method="POST" action="{{ route('admin.elections.trash.restore', $election->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Restore</button>
                            </form>
                            <form method="POST" action="{{ route('admin.elections.trash.destroy', $election->id) }}" onsubmit="return confirm('Permanently delete this election and all related data? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--danger);border-color:var(--danger-border);">Delete forever</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<style>
.trash-empty__confirm {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    color: var(--ash);
    margin-right: 8px;
    user-select: none;
}
.trash-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}
.trash-table th,
.trash-table td {
    padding: 12px 14px;
    text-align: left;
    border-bottom: 1px solid var(--border-light);
}
.trash-table th {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--ash);
    background: var(--surface);
}
.trash-table__actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    flex-wrap: wrap;
}
@media (max-width: 768px) {
    .trash-table thead { display: none; }
    .trash-table tr { display: block; border-bottom: 1px solid var(--border); padding: 12px 0; }
    .trash-table td { display: block; border: none; padding: 4px 14px; }
    .trash-table__actions { justify-content: flex-start; padding-top: 8px; }
}
</style>
@endsection
