<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::withCount('candidates')->orderBy('order')->get();
        return view('admin.positions.index', compact('positions'));
    }

    public function create()
    {
        return view('admin.positions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'order'       => 'nullable|integer',
        ]);

        Position::create([
            'name'        => $request->name,
            'description' => $request->description,
            'order'       => $request->order ?? 0,
        ]);

        return redirect()->route('admin.positions.index')
                         ->with('success', 'Position created successfully.');
    }

    public function edit(Position $position)
    {
        return view('admin.positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
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

        return redirect()->route('admin.positions.index')
                         ->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('admin.positions.index')
                         ->with('success', 'Position deleted.');
    }
}