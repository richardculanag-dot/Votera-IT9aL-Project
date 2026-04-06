<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with('position')->withCount('votes')->get();
        return view('admin.candidates.index', compact('candidates'));
    }

    public function create()
    {
        $positions = Position::orderBy('order')->get();
        return view('admin.candidates.create', compact('positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
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
            'position_id' => $request->position_id,
            'platform'    => $request->platform,
            'grade_level' => $request->grade_level,
            'image'       => $imagePath,
        ]);

        return redirect()->route('admin.candidates.index')
                         ->with('success', 'Candidate added successfully.');
    }

    public function edit(Candidate $candidate)
    {
        $positions = Position::orderBy('order')->get();
        return view('admin.candidates.edit', compact('candidate', 'positions'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
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
            'position_id' => $request->position_id,
            'platform'    => $request->platform,
            'grade_level' => $request->grade_level,
            'image'       => $imagePath,
        ]);

        return redirect()->route('admin.candidates.index')
                         ->with('success', 'Candidate updated successfully.');
    }

    public function destroy(Candidate $candidate)
    {
        if ($candidate->image) {
            Storage::disk('public')->delete($candidate->image);
        }
        $candidate->delete();

        return redirect()->route('admin.candidates.index')
                         ->with('success', 'Candidate removed.');
    }
}