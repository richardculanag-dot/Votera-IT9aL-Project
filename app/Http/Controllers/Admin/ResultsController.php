<?php
// FILE: app/Http/Controllers/Admin/ResultsController.php — replace existing

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResultsController extends Controller
{
    public function index(Request $request)
    {
        $elections = Election::with('department')
            ->whereIn('status', ['ongoing', 'ended'])
            ->orderByRaw("CASE WHEN status = 'ongoing' THEN 0 ELSE 1 END")
            ->latest('end_date')
            ->latest('id')
            ->get();

        if ($elections->isEmpty()) {
            return view('admin.results', [
                'elections'      => collect(),
                'election'       => null,
                'positions'      => collect(),
                'totalVoters'    => 0,
                'totalVotesCast' => 0,
                'turnout'        => 0,
                'canExportPdf'   => false,
            ]);
        }

        $selectedElectionId = (int) $request->query('election');
        $election = $elections->firstWhere('id', $selectedElectionId) ?? $elections->first();

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
        $canExportPdf   = $election->status === 'ended';

        return view('admin.results', compact(
            'elections', 'election', 'positions', 'totalVoters', 'totalVotesCast', 'turnout', 'canExportPdf'
        ));
    }

    public function exportPdf(Request $request)
    {
        $electionId = (int) $request->query('election');
        $election = Election::where('id', $electionId)->first();

        if (! $election) {
            abort(404, 'No election data available for export.');
        }

        if ($election->status !== 'ended') {
            abort(403, 'Only ended elections can be exported to PDF.');
        }

        $positions = Position::where('election_id', $election->id)
            ->with(['candidates' => function ($q) use ($election) {
                $q->withCount(['votes as votes_count' => function ($q) use ($election) {
                    $q->where('election_id', $election->id);
                }])->orderByDesc('votes_count');
            }])
            ->orderBy('order')
            ->get();

        // Fallback for positions without election_id (kept consistent with index()).
        if ($positions->isEmpty()) {
            $positions = Position::with(['candidates' => function ($q) use ($election) {
                $q->withCount(['votes as votes_count' => function ($q) use ($election) {
                    $q->where('election_id', $election->id);
                }])->orderByDesc('votes_count');
            }])->orderBy('order')->get();
        }

        $totalVoters = User::where('role', 'student')->count();
        $totalVotesCast = $election->totalVotesCast();
        $turnout = $election->turnoutPercent();

        $fileTitle = Str::slug($election->title) ?: 'election-results';
        $fileName = "{$fileTitle}-results.pdf";

        AuditLog::record(
            'results_export_pdf',
            "Exported results PDF for: {$election->title}",
            'Election',
            $election->id
        );

        $pdf = Pdf::loadView('Admin.results_pdf', compact(
            'election',
            'positions',
            'totalVoters',
            'totalVotesCast',
            'turnout'
        ))->setPaper('a4', 'portrait');

        return $pdf->download($fileName);
    }
}