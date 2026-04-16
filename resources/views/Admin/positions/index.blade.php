@extends('layouts.app')

@section('title', 'Election Positions')

@section('breadcrumb')
    {{ ucfirst($routePrefix) }} / <a href="{{ route($routePrefix . '.elections.index') }}">Elections</a> / <span>Positions</span>
@endsection

@section('content')

<div class="v-page-header">
    <div>
        <div class="v-page-header__title">Positions: {{ $election->title }}</div>
        <div class="v-page-header__sub">Manage election-scoped positions</div>
    </div>
    <div class="v-page-header__actions">
        <a href="{{ route($routePrefix . '.elections.show', $election) }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Election
        </a>
        <a href="{{ route($routePrefix . '.elections.positions.create', $election) }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Position
        </a>
    </div>
</div>

<div class="v-card">
    @if($positions->isEmpty())
        <div class="v-empty">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0120 9.414V19a2 2 0 01-2 2z"/></svg>
            <h3>No positions yet</h3>
            <p>Create your first elective position to get started.</p>
            <a href="{{ route($routePrefix . '.elections.positions.create', $election) }}" class="btn btn-primary mt-3">Add Position</a>
        </div>
    @else
        <div class="v-table-wrap">
            <table class="v-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Position Name</th>
                        <th>Description</th>
                        <th>Candidates</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($positions as $position)
                    <tr>
                        <td style="color:var(--ash);">{{ $position->id }}</td>
                        <td class="fw-600">{{ $position->name }}</td>
                        <td style="max-width:280px;">{{ $position->description ?? '—' }}</td>
                        <td>
                            <span class="badge badge-student">{{ $position->candidates_count }}</span>
                        </td>
                        <td style="color:var(--ash);">{{ $position->order }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route($routePrefix . '.elections.positions.candidates.index', [$election, $position]) }}" class="btn btn-secondary btn-sm">Candidates</a>
                                <a href="{{ route($routePrefix . '.elections.positions.edit', [$election, $position]) }}" class="btn btn-secondary btn-sm">Edit</a>
                                @if($routePrefix === 'admin')
                                <form method="POST" action="{{ route($routePrefix . '.elections.positions.destroy', [$election, $position]) }}" onsubmit="return confirm('Delete this position? This will also remove all associated candidates and votes.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection