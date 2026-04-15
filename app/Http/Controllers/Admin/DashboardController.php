<?php
// FILE: app/Http/Controllers/Admin/DashboardController.php — replace existing

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Position;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents    = User::where('role', 'student')->count();
        $totalCandidates  = Candidate::count();
        $totalPositions   = Position::count();
        $activeElections  = Election::ongoing()->count();

        // Latest ongoing election for detailed stats
        $currentElection  = Election::ongoing()->latest()->first()
                         ?? Election::latest()->first();

        $totalVotesCast   = $currentElection
            ? $currentElection->totalVotesCast()
            : 0;

        $turnoutPercent   = $currentElection
            ? $currentElection->turnoutPercent()
            : 0;

        // Chart data: votes per position for the current election
        $chartLabels = [];
        $chartData   = [];

        if ($currentElection) {
            $positionVotes = Vote::where('election_id', $currentElection->id)
                ->select('position_id', DB::raw('count(*) as total'))
                ->groupBy('position_id')
                ->with('position')
                ->get();

            foreach ($positionVotes as $pv) {
                $chartLabels[] = $pv->position->name ?? 'Unknown';
                $chartData[]   = $pv->total;
            }
        }

        // Recent audit logs
        $recentLogs = \App\Models\AuditLog::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalCandidates',
            'totalPositions',
            'activeElections',
            'currentElection',
            'totalVotesCast',
            'turnoutPercent',
            'chartLabels',
            'chartData',
            'recentLogs'
        ));
    }
}