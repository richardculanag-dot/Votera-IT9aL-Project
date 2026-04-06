<?php

namespace App\Http\Controllers\Admin;

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

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalCandidates',
            'totalVotes',
            'votingStatus'
        ));
    }
}