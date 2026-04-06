<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function index()
    {
        // Check if voting is open
        $votingStatus = DB::table('voting_settings')
                          ->where('key', 'voting_status')
                          ->value('value');

        if ($votingStatus !== 'open') {
            return view('student.voting-closed');
        }

        // Check if student already voted (all positions)
        $positions     = Position::with('candidates')->orderBy('order')->get();
        $votedPosition = Vote::where('user_id', auth()->id())
                             ->pluck('position_id')
                             ->toArray();

        // If they've voted in every position, redirect to success
        if (count($votedPosition) >= $positions->count() && $positions->count() > 0) {
            return redirect()->route('student.vote.success');
        }

        return view('student.vote', compact('positions', 'votedPosition'));
    }

    public function store(Request $request)
    {
        // Ensure voting is open
        $votingStatus = DB::table('voting_settings')
                          ->where('key', 'voting_status')
                          ->value('value');

        if ($votingStatus !== 'open') {
            return back()->with('error', 'Voting is currently closed.');
        }

        $positions = Position::all();

        $request->validate(
            collect($positions)->mapWithKeys(fn ($p) => [
                "votes.{$p->id}" => 'required|exists:candidates,id',
            ])->toArray()
        );

        DB::beginTransaction();
        try {
            foreach ($positions as $position) {
                $candidateId = $request->input("votes.{$position->id}");

                // Skip if already voted for this position
                $alreadyVoted = Vote::where('user_id', auth()->id())
                                    ->where('position_id', $position->id)
                                    ->exists();
                if ($alreadyVoted) {
                    continue;
                }

                Vote::create([
                    'user_id'      => auth()->id(),
                    'candidate_id' => $candidateId,
                    'position_id'  => $position->id,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()->route('student.vote.success');
    }

    public function success()
    {
        return view('student.success');
    }
}