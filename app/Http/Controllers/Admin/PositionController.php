<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(Election $election)
    {
        $positions = Position::where('election_id', $election->id)
            ->withCount('candidates')
            ->orderBy('order')
            ->get();

        $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
        return view('admin.positions.index', compact('positions', 'election', 'routePrefix'));
    }

    public function create(Election $election)
    {
        $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
        return view('admin.positions.create', compact('election', 'routePrefix'));
    }

    public function store(Request $request, Election $election)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'order'       => 'nullable|integer',
        ]);

        Position::create([
            'election_id' => $election->id,
            'name'        => $request->name,
            'description' => $request->description,
            'order'       => $request->order ?? 0,
        ]);

        $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
        return redirect()->route("{$routePrefix}.elections.positions.index", $election)
                         ->with('success', 'Position created successfully.');
    }

    public function edit(Election $election, Position $position)
    {
        abort_if((int) $position->election_id !== (int) $election->id, 404);
        $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
        return view('admin.positions.edit', compact('position', 'election', 'routePrefix'));
    }

    public function update(Request $request, Election $election, Position $position)
    {
        abort_if((int) $position->election_id !== (int) $election->id, 404);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'order'       => 'nullable|integer',
        ]);

        $position->update([
            'name'        => $request->name,
            'description' => $request->description,
            'order'       => $request->order ?? 0,
        ]);

        $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
        return redirect()->route("{$routePrefix}.elections.positions.index", $election)
                         ->with('success', 'Position updated successfully.');
    }

    public function destroy(Election $election, Position $position)
    {
        abort_if((int) $position->election_id !== (int) $election->id, 404);
        $position->delete();
        return redirect()->route('admin.elections.positions.index', $election)
                         ->with('success', 'Position deleted.');
    }
}