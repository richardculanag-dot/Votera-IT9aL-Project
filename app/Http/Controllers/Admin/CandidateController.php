<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    private function blockEndedElectionChanges(Election $election)
    {
        if ($election->status === 'ended') {
            $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
            return redirect()
                ->route("{$routePrefix}.elections.positions.candidates.index", [$election, request()->route('position')])
                ->with('error', 'This election has already ended. Candidate management is locked.');
        }

        return null;
    }

    public function index(Election $election, Position $position)
    {
        abort_if((int) $position->election_id !== (int) $election->id, 404);

        $candidates = Candidate::where('position_id', $position->id)
            ->with('position')
            ->withCount('votes')
            ->get();

        $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
        return view('admin.candidates.index', compact('candidates', 'position', 'election', 'routePrefix'));
    }

    public function create(Election $election, Position $position)
    {
        abort_if((int) $position->election_id !== (int) $election->id, 404);
        if ($redirect = $this->blockEndedElectionChanges($election)) {
            return $redirect;
        }
        $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
        return view('admin.candidates.create', compact('position', 'election', 'routePrefix'));
    }

    public function store(Request $request, Election $election, Position $position)
    {
        abort_if((int) $position->election_id !== (int) $election->id, 404);
        if ($redirect = $this->blockEndedElectionChanges($election)) {
            return $redirect;
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'platform'    => 'nullable|string|max:1000',
            'grade_level' => 'nullable|string|max:50',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('candidates', 'public');
        }

        Candidate::create([
            'name'        => $request->name,
            'position_id' => $position->id,
            'platform'    => $request->platform,
            'grade_level' => $request->grade_level,
            'image'       => $imagePath,
        ]);

        $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
        return redirect()->route("{$routePrefix}.elections.positions.candidates.index", [$election, $position])
                         ->with('success', 'Candidate added successfully.');
    }

    public function edit(Election $election, Position $position, Candidate $candidate)
    {
        abort_if((int) $position->election_id !== (int) $election->id, 404);
        abort_if((int) $candidate->position_id !== (int) $position->id, 404);
        if ($redirect = $this->blockEndedElectionChanges($election)) {
            return $redirect;
        }
        $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
        return view('admin.candidates.edit', compact('candidate', 'position', 'election', 'routePrefix'));
    }

    public function update(Request $request, Election $election, Position $position, Candidate $candidate)
    {
        abort_if((int) $position->election_id !== (int) $election->id, 404);
        abort_if((int) $candidate->position_id !== (int) $position->id, 404);
        if ($redirect = $this->blockEndedElectionChanges($election)) {
            return $redirect;
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'platform'    => 'nullable|string|max:1000',
            'grade_level' => 'nullable|string|max:50',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $candidate->image;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($candidate->image) {
                Storage::disk('public')->delete($candidate->image);
            }
            $imagePath = $request->file('image')->store('candidates', 'public');
        }

        $candidate->update([
            'name'        => $request->name,
            'platform'    => $request->platform,
            'grade_level' => $request->grade_level,
            'image'       => $imagePath,
        ]);

        $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
        return redirect()->route("{$routePrefix}.elections.positions.candidates.index", [$election, $position])
                         ->with('success', 'Candidate updated successfully.');
    }

    public function destroy(Election $election, Position $position, Candidate $candidate)
    {
        abort_if((int) $position->election_id !== (int) $election->id, 404);
        abort_if((int) $candidate->position_id !== (int) $position->id, 404);
        if ($redirect = $this->blockEndedElectionChanges($election)) {
            return $redirect;
        }

        if ($candidate->image) {
            Storage::disk('public')->delete($candidate->image);
        }
        $candidate->delete();

        $routePrefix = request()->routeIs('staff.*') ? 'staff' : 'admin';
        return redirect()->route("{$routePrefix}.elections.positions.candidates.index", [$election, $position])
                         ->with('success', 'Candidate removed.');
    }
}