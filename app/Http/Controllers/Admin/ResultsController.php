<?php
// FILE: app/Http/Controllers/Admin/ResultsController.php — replace existing

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Position;
use App\Models\Vote;
use App\Models\User;

class ResultsController extends Controller
{
    public function index()
    {
        // Latest ongoing or most recent election
        $election = Election::ongoing()->latest()->first()
                 ?? Election::latest()->first();

        if (! $election) {
            return view('admin.results', [
                'election'       => null,
                'positions'      => collect(),
                'totalVoters'    => 0,
                'totalVotesCast' => 0,
                'turnout'        => 0,
            ]);
        }

        $positions = Position::where('election_id', $election->id)
            ->with(['candidates' => function ($q) use ($election) {
                $q->withCount(['votes as votes_count' => function ($q) use ($election) {
                    $q->where('election_id', $election->id);
                }])->orderByDesc('votes_count');
            }])
            ->orderBy('order')
            ->get();

        // Fallback for positions without election_id
        if ($positions->isEmpty()) {
            $positions = Position::with(['candidates' => function ($q) use ($election) {
                $q->withCount(['votes as votes_count' => function ($q) use ($election) {
                    $q->where('election_id', $election->id);
                }])->orderByDesc('votes_count');
            }])->orderBy('order')->get();
        }

        $totalVoters    = User::where('role', 'student')->count();
        $totalVotesCast = $election->totalVotesCast();
        $turnout        = $election->turnoutPercent();

        return view('admin.results', compact(
            'election', 'positions', 'totalVoters', 'totalVotesCast', 'turnout'
        ));
    }
}