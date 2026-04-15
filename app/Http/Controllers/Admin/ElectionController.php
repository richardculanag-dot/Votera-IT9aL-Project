<?php
// FILE: app/Http/Controllers/Admin/ElectionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::withCount(['votes', 'positions'])
                             ->latest()
                             ->get();
        return view('admin.elections.index', compact('elections'));
    }

    public function create()
    {
        return view('admin.elections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $election = Election::create([
            'title'       => $request->title,
            'description' => $request->description,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'status'      => 'pending',
            'created_by'  => auth()->id(),
        ]);

        AuditLog::record('election_created', "Created election: {$election->title}", 'Election', $election->id);

        return redirect()->route('admin.elections.index')
                         ->with('success', 'Election created.');
    }

    public function edit(Election $election)
    {
        return view('admin.elections.edit', compact('election'));
    }

    public function update(Request $request, Election $election)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $election->update($request->only('title', 'description', 'start_date', 'end_date'));

        AuditLog::record('election_updated', "Updated election: {$election->title}", 'Election', $election->id);

        return redirect()->route('admin.elections.index')
                         ->with('success', 'Election updated.');
    }

    public function destroy(Election $election)
    {
        AuditLog::record('election_deleted', "Deleted election: {$election->title}", 'Election', $election->id);
        $election->delete(); // soft delete

        return redirect()->route('admin.elections.index')
                         ->with('success', 'Election removed.');
    }

    // ── Status toggle ──────────────────────────────────────
    public function toggleStatus(Election $election)
    {
        $next = match ($election->status) {
            'pending'  => 'ongoing',
            'ongoing'  => 'ended',
            default    => 'pending',
        };

        $election->update(['status' => $next]);

        AuditLog::record(
            "election_{$next}",
            "Election '{$election->title}' status changed to {$next}",
            'Election',
            $election->id
        );

        return back()->with('success', "Election is now: " . ucfirst($next));
    }
}