<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Election;
use Illuminate\Http\Request;

class ElectionTrashController extends Controller
{
    public function index()
    {
        $elections = Election::onlyTrashed()
            ->with('department')
            ->withCount(['votes', 'positions'])
            ->latest('deleted_at')
            ->get();

        return view('admin.elections.trash.index', [
            'elections' => $elections,
            'retentionDays' => Election::TRASH_RETENTION_DAYS,
        ]);
    }

    public function restore(Request $request, int $id)
    {
        $election = Election::onlyTrashed()->findOrFail($id);

        $election->restore();

        AuditLog::record(
            'election_restored',
            "Restored election from trash: {$election->title}",
            'Election',
            $election->id
        );

        return redirect()
            ->route('admin.elections.index')
            ->with('success', 'Election restored to the elections list.');
    }

    public function destroy(Request $request, int $id)
    {
        $election = Election::onlyTrashed()->findOrFail($id);
        $title = $election->title;

        $election->forceDelete();

        AuditLog::record(
            'election_permanently_deleted',
            "Permanently deleted election from trash: {$title}",
            'Election',
            $id
        );

        return redirect()
            ->route('admin.elections.trash.index')
            ->with('success', 'Election permanently removed.');
    }

    public function empty(Request $request)
    {
        $request->validate([
            'confirm' => 'required|accepted',
        ]);

        $count = Election::onlyTrashed()->count();

        Election::onlyTrashed()->each(fn (Election $e) => $e->forceDelete());

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'election_trash_emptied',
            'model' => 'Election',
            'model_id' => null,
            'description' => "Emptied election trash ({$count} record(s) permanently deleted).",
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('admin.elections.trash.index')
            ->with('success', $count > 0
                ? "Trash cleared ({$count} election(s) permanently removed)."
                : 'Trash was already empty.');
    }
}
