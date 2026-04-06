<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Vote;

class ResultsController extends Controller
{
    public function index()
    {
        // Load positions with their candidates and vote counts
        $positions = Position::with(['candidates' => function ($q) {
            $q->withCount('votes')->orderByDesc('votes_count');
        }])->orderBy('order')->get();

        $totalVotesCast = Vote::distinct('user_id')->count('user_id');

        return view('admin.results', compact('positions', 'totalVotesCast'));
    }
}