<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents   = User::where('role', 'student')->count();
        $totalCandidates = Candidate::count();
        $totalVotes      = Vote::distinct('user_id')->count('user_id');
        $votingStatus    = DB::table('voting_settings')
                             ->where('key', 'voting_status')
                             ->value('value');

        return view('staff.dashboard', compact(
            'totalStudents',
            'totalCandidates',
            'totalVotes',
            'votingStatus'
        ));
    }
}