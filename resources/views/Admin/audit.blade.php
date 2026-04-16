{{-- FILE: resources/views/admin/audit.blade.php --}}
@extends('layouts.app')
@section('title', 'Audit Log')
@section('breadcrumb') Admin / <span>Audit Log</span> @endsection

@section('content')
<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Audit Log</div>
        <div class="v-page-header__sub">Track all system actions and events</div>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back
    </a>
</div>

<div style="display:flex;gap:8px;margin-bottom:20px;">
    <a href="{{ route('admin.audit', ['type' => 'management']) }}" 
       class="btn {{ $type === 'management' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Management Logs
    </a>
    <a href="{{ route('admin.audit', ['type' => 'votes']) }}" 
       class="btn {{ $type === 'votes' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        Student Votes
    </a>
</div>

<div class="v-card">
    @if($logs->isEmpty())
        <div class="v-empty">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <h3>No activity logged yet</h3>
            <p>Actions performed in the system will appear here.</p>
        </div>
    @else
    <div class="v-table-wrap">
        <table class="v-table">
            <thead>
                <tr>
                    <th>When</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td style="white-space:nowrap;color:var(--ash);font-size:0.8rem;">
                        {{ $log->created_at->format('M d, Y') }}<br>
                        <span style="font-size:0.72rem;">{{ $log->created_at->format('h:i A') }}</span>
                    </td>
                    <td>
                        @if($log->user)
                            <div style="font-weight:600;font-size:0.85rem;">{{ $log->user->name }}</div>
                            <div style="font-size:0.72rem;color:var(--ash);">
                                <span class="badge badge-{{ $log->user->role }}" style="padding:1px 6px;">{{ ucfirst($log->user->role) }}</span>
                            </div>
                        @else
                            <span style="color:var(--ash);">System</span>
                        @endif
                    </td>
                    <td>
                        <code style="font-size:0.75rem;background:var(--surface);padding:2px 7px;border-radius:5px;border:1px solid var(--border);color:var(--ink-soft);">
                            {{ $log->action }}
                        </code>
                    </td>
                    <td style="font-size:0.84rem;max-width:320px;">{{ $log->description }}</td>
                    <td style="font-size:0.78rem;color:var(--ash);">{{ $log->ip_address ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:14px 20px;border-top:1px solid var(--border);">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection