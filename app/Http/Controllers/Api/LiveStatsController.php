<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Vote;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LiveStatsController extends Controller
{
    public function electionStats($electionId)
    {
        $election = Election::with('department')->findOrFail($electionId);
        
        $totalVoters = \App\Models\User::where('role', 'student')
            ->where('department_id', $election->department_id)
            ->count();
            
        $totalVotes = Vote::where('election_id', $electionId)
            ->distinct('user_id')
            ->count('user_id');
            
        $turnoutPercent = $totalVoters > 0 ? round(($totalVotes / $totalVoters) * 100, 1) : 0;
        
        $latestVotes = Vote::with(['user', 'candidate.position'])
            ->where('election_id', $electionId)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($vote) {
                return [
                    'id' => $vote->id,
                    'voter_name' => $vote->user->name,
                    'candidate_name' => $vote->candidate->name,
                    'position_name' => $vote->candidate->position->name,
                    'time' => $vote->created_at->diffForHumans(),
                ];
            });
        
        return response()->json([
            'election' => [
                'id' => $election->id,
                'title' => $election->title,
                'status' => $election->status,
                'is_locked' => $election->is_locked,
            ],
            'stats' => [
                'total_voters' => $totalVoters,
                'total_votes' => $totalVotes,
                'turnout_percent' => $turnoutPercent,
            ],
            'latest_votes' => $latestVotes,
            'updated_at' => now()->toIso8601String(),
        ]);
    }
    
    public function liveResults($electionId)
    {
        $election = Election::findOrFail($electionId);
        
        $positions = Position::where('election_id', $electionId)
            ->with(['candidates' => function ($query) {
                $query->withCount('votes');
            }])
            ->orderBy('order')
            ->get()
            ->map(function ($position) {
                $totalVotesForPosition = $position->candidates->sum('votes_count');
                
                return [
                    'id' => $position->id,
                    'name' => $position->name,
                    'candidates' => $position->candidates->map(function ($candidate) use ($totalVotesForPosition) {
                        $percent = $totalVotesForPosition > 0 
                            ? round(($candidate->votes_count / $totalVotesForPosition) * 100, 1) 
                            : 0;
                            
                        return [
                            'id' => $candidate->id,
                            'name' => $candidate->name,
                            'image_url' => $candidate->image_url,
                            'partylist' => $candidate->partylist,
                            'votes' => $candidate->votes_count,
                            'percent' => $percent,
                        ];
                    })->sortByDesc('votes')->values(),
                ];
            });
        
        return response()->json([
            'election' => [
                'id' => $election->id,
                'title' => $election->title,
                'status' => $election->status,
            ],
            'positions' => $positions,
            'updated_at' => now()->toIso8601String(),
        ]);
    }
    
    public function currentElection()
    {
        $election = Election::with('department')
            ->where('status', 'ongoing')
            ->where('is_locked', false)
            ->first();
            
        if (!$election) {
            return response()->json(['election' => null]);
        }
        
        return response()->json([
            'election' => [
                'id' => $election->id,
                'title' => $election->title,
                'department' => $election->department->code,
                'status' => $election->status,
                'is_locked' => $election->is_locked,
            ],
        ]);
    }
    
    public function dashboardStats()
    {
        $totalStudents = \App\Models\User::where('role', 'student')->count();
        
        $activeElections = Election::where('status', 'ongoing')->count();
        
        $currentElection = Election::with('department')
            ->where('status', 'ongoing')
            ->first();
            
        $totalVotesCast = 0;
        $turnoutPercent = 0;
        
        if ($currentElection) {
            $totalVoters = \App\Models\User::where('role', 'student')
                ->where('department_id', $currentElection->department_id)
                ->count();
                
            $totalVotesCast = Vote::where('election_id', $currentElection->id)
                ->distinct('user_id')
                ->count('user_id');
                
            $turnoutPercent = $totalVoters > 0 
                ? round(($totalVotesCast / $totalVoters) * 100, 1) 
                : 0;
        }
        
        $chartLabels = [];
        $chartData = [];
        
        if ($currentElection) {
            $positions = Position::where('election_id', $currentElection->id)
                ->withCount('votes')
                ->orderBy('order')
                ->get();
                
            $chartLabels = $positions->pluck('name')->toArray();
            $chartData = $positions->pluck('votes_count')->toArray();
        }
        
        return response()->json([
            'total_students' => $totalStudents,
            'total_votes_cast' => $totalVotesCast,
            'turnout_percent' => $turnoutPercent,
            'active_elections' => $activeElections,
            'current_election' => $currentElection ? [
                'id' => $currentElection->id,
                'title' => $currentElection->title,
                'department' => $currentElection->department->code ?? null,
                'status' => $currentElection->status,
            ] : null,
            'chart' => [
                'labels' => $chartLabels,
                'data' => $chartData,
            ],
            'updated_at' => now()->toIso8601String(),
        ]);
    }
}