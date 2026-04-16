<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use App\Models\Election;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Vote::with(['user', 'candidate.position.election']);

        if ($request->election_id) {
            $query->where('election_id', $request->election_id);
        }

        $votes = $query->orderByDesc('created_at')->paginate(20);
        $elections = Election::orderBy('title')->get();

        return view('admin.votes.index', compact('votes', 'elections'));
    }
}