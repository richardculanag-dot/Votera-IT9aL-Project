<?php
// FILE: app/Http/Controllers/Student/VoteController.php — replace existing

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Position;
use App\Models\Vote;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function index()
    {
        $election = Election::ongoing()->latest()->first();

        if (! $election) {
            return view('student.voting-closed');
        }

        $positions     = Position::where('election_id', $election->id)
                                 ->with('candidates')
                                 ->orderBy('order')
                                 ->get();

        // Fallback: if positions have no election_id yet, load all
        if ($positions->isEmpty()) {
            $positions = Position::with('candidates')->orderBy('order')->get();
        }

        $votedPositionIds = Vote::where('user_id', auth()->id())
                               ->where('election_id', $election->id)
                               ->pluck('position_id')
                               ->toArray();

        if ($positions->count() > 0 && count($votedPositionIds) >= $positions->count()) {
            return redirect()->route('student.vote.success');
        }

        return view('student.vote', compact('positions', 'votedPositionIds', 'election'));
    }

    public function store(Request $request)
    {
        $election = Election::ongoing()->latest()->first();

        if (! $election) {
            return back()->with('error', 'Voting is currently closed.');
        }

        $positions = Position::where('election_id', $election->id)->get();
        if ($positions->isEmpty()) {
            $positions = Position::all();
        }

        $request->validate(
            collect($positions)->mapWithKeys(fn ($p) => [
                "votes.{$p->id}" => 'required|exists:candidates,id',
            ])->toArray(),
            [],
        );

        DB::beginTransaction();
        try {
            foreach ($positions as $position) {
                $candidateId = $request->input("votes.{$position->id}");

                $alreadyVoted = Vote::where('user_id', auth()->id())
                                    ->where('position_id', $position->id)
                                    ->where('election_id', $election->id)
                                    ->exists();
                if ($alreadyVoted) continue;

                Vote::create([
                    'user_id'      => auth()->id(),
                    'candidate_id' => $candidateId,
                    'position_id'  => $position->id,
                    'election_id'  => $election->id,
                ]);
            }

            AuditLog::record(
                'vote_cast',
                auth()->user()->name . ' submitted ballot for: ' . $election->title,
                'Vote',
                $election->id
            );

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