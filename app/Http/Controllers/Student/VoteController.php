<?php
// FILE: app/Http/Controllers/Student/VoteController.php — replace existing

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Position;
use App\Models\Vote;
use App\Models\AuditLog;
use App\Models\Candidate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user();

        $liveElections = Election::query()
            ->where('department_id', $user->department_id)
            ->where('status', 'ongoing')
            ->where('is_locked', false)
            ->latest()
            ->get();

        $upcomingElections = Election::query()
            ->where('department_id', $user->department_id)
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $voteCount = Vote::where('user_id', $user->id)->count();
        $recentVotes = Vote::with(['candidate', 'position', 'election'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('Student.dashboard', compact(
            'liveElections',
            'upcomingElections',
            'voteCount',
            'recentVotes',
        ));
    }

    public function profile(): View
    {
        $user = auth()->user()->load(['department', 'course']);
        return view('Student.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
        ]);

        $user->name = $request->name;

        if ($request->hasFile('image')) {
            if ($user->image && file_exists(storage_path('app/public/' . $user->image))) {
                unlink(storage_path('app/public/' . $user->image));
            }
            $path = $request->file('image')->store('profile-images', 'public');
            $user->image = $path;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function elections(): View
    {
        $user = auth()->user();

        $elections = Election::query()
            ->with('department')
            ->where('department_id', $user->department_id)
            ->latest()
            ->get();

        return view('Student.elections', compact('elections'));
    }

    public function index(Election $election)
    {
        $user = auth()->user();
        if ((int) $election->department_id !== (int) $user->department_id) {
            abort(403, 'You are not eligible for this election.');
        }

        if (! $election->isOpenForVoting()) {
            return redirect()
                ->route('student.elections')
                ->with('error', 'This election is currently not open for voting.');
        }

        $positions = Position::where('election_id', $election->id)
            ->with('candidates')
            ->orderBy('order')
            ->get();

        if ($positions->isEmpty()) {
            return redirect()
                ->route('student.elections')
                ->with('error', 'This election has no positions yet and cannot accept ballots.');
        }

        $positionsWithoutCandidates = $positions->filter(fn ($position) => $position->candidates->isEmpty());
        if ($positionsWithoutCandidates->isNotEmpty()) {
            return redirect()
                ->route('student.elections')
                ->with('error', 'This election setup is incomplete. Some positions have no candidates.');
        }

        $votedPositionIds = Vote::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->pluck('position_id')
            ->toArray();

        if (count($votedPositionIds) >= $positions->count()) {
            return redirect()
                ->route('student.history')
                ->with('success', 'You have already submitted your ballot for this election.');
        }

        return view('student.vote', compact('positions', 'votedPositionIds', 'election'));
    }

    public function store(Request $request, Election $election)
    {
        $user = auth()->user();

        if ((int) $election->department_id !== (int) $user->department_id) {
            abort(403, 'You are not eligible for this election.');
        }

        if (! $election->isOpenForVoting()) {
            return back()->with('error', 'Voting is currently closed for this election.');
        }

        $positions = Position::where('election_id', $election->id)->pluck('id');
        if ($positions->isEmpty()) {
            return back()->with('error', 'This election has no positions yet and cannot accept ballots.');
        }

        $positionsWithCandidateCount = Candidate::whereIn('position_id', $positions)->distinct('position_id')->count('position_id');
        if ($positionsWithCandidateCount !== $positions->count()) {
            return back()->with('error', 'This election setup is incomplete. Some positions have no candidates.');
        }

        $request->validate(
            collect($positions)->mapWithKeys(fn ($positionId) => [
                "votes.{$positionId}" => 'required|integer',
            ])->toArray(),
            [],
        );

        $existingVotesForElection = Vote::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->count();
        if ($existingVotesForElection >= $positions->count()) {
            return redirect()
                ->route('student.history')
                ->with('success', 'You already submitted your ballot for this election.');
        }

        DB::beginTransaction();
        try {
            $createdVotes = 0;
            foreach ($positions as $positionId) {
                $candidateId = (int) $request->input("votes.{$positionId}");
                $candidateBelongsToPosition = Candidate::where('id', $candidateId)
                    ->where('position_id', $positionId)
                    ->exists();
                if (! $candidateBelongsToPosition) {
                    DB::rollBack();
                    return back()->with('error', 'Invalid ballot selection detected. Please try again.');
                }

                $alreadyVoted = Vote::where('user_id', $user->id)
                                    ->where('position_id', $positionId)
                                    ->exists();
                if ($alreadyVoted) continue;

                Vote::create([
                    'user_id'      => $user->id,
                    'candidate_id' => $candidateId,
                    'position_id'  => $positionId,
                    'election_id'  => $election->id,
                ]);
                $createdVotes++;
            }

            if ($createdVotes === 0) {
                DB::rollBack();
                return redirect()
                    ->route('student.history')
                    ->with('success', 'You already submitted your ballot for this election.');
            }

            AuditLog::record(
                'vote_cast',
                $user->name . ' submitted ballot for: ' . $election->title,
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

    public function history(): View
    {
        $votes = Vote::with(['candidate', 'position', 'election'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('Student.history', compact('votes'));
    }

    public function success()
    {
        return view('student.success');
    }
}