<?php
// FILE: app/Http/Controllers/Admin/ElectionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::with('department')
                             ->withCount(['votes', 'positions'])
                             ->latest()
                             ->get();
        $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
        return view('admin.elections.index', compact('elections', 'routePrefix'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.elections.create', compact('departments'));
    }

    public function show(Election $election)
    {
        $election->load([
            'department',
            'positions.candidates',
            'positions.votes',
        ]);

        $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
        return view('admin.elections.show', compact('election', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $election = Election::create([
            'title'       => $request->title,
            'description' => $request->description,
            'department_id' => $request->department_id,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'status'      => 'pending',
            'created_by'  => auth()->id(),
        ]);

        $defaultPositions = [
            ['name' => 'President', 'description' => 'Leads the student council and represents the department.', 'order' => 1],
            ['name' => 'Vice President', 'description' => 'Supports the president and leads initiatives.', 'order' => 2],
            ['name' => 'Secretary', 'description' => 'Handles documentation, records, and communications.', 'order' => 3],
            ['name' => 'Treasurer', 'description' => 'Manages funds, budgets, and financial records.', 'order' => 4],
            ['name' => 'Auditor', 'description' => 'Oversees financial audits and ensures transparency.', 'order' => 5],
            ['name' => 'Business Manager', 'description' => 'Handles fundraising and external partnerships.', 'order' => 6],
            ['name' => 'Senator', 'description' => 'Represents students in the student council.', 'order' => 7],
        ];

        foreach ($defaultPositions as $position) {
            Position::create([
                'election_id' => $election->id,
                'name' => $position['name'],
                'description' => $position['description'],
                'order' => $position['order'],
            ]);
        }

        AuditLog::record('election_created', "Created election: {$election->title}", 'Election', $election->id);

        return redirect()->route('admin.elections.index')
                         ->with('success', 'Election created.');
    }

    public function edit(Election $election)
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.elections.edit', compact('election', 'departments'));
    }

    public function update(Request $request, Election $election)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $election->update($request->only('title', 'description', 'department_id', 'start_date', 'end_date'));

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

        if ($next === 'ongoing') {
            $positions = $election->positions()->withCount('candidates')->get();
            if ($positions->isEmpty()) {
                return back()->with('error', 'Cannot start election: add at least one position first.');
            }

            $hasPositionWithoutCandidates = $positions->contains(fn ($position) => $position->candidates_count === 0);
            if ($hasPositionWithoutCandidates) {
                return back()->with('error', 'Cannot start election: every position must have at least one candidate.');
            }
        }

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