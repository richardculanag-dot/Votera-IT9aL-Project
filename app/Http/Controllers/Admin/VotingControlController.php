<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VotingControlController extends Controller
{
    public function index()
    {
        $votingStatus = DB::table('voting_settings')
                          ->where('key', 'voting_status')
                          ->value('value');

        return view('admin.voting-control', compact('votingStatus'));
    }

    public function toggle(Request $request)
    {
        $current = DB::table('voting_settings')
                     ->where('key', 'voting_status')
                     ->value('value');

        $newStatus = ($current === 'open') ? 'closed' : 'open';

        DB::table('voting_settings')
          ->where('key', 'voting_status')
          ->update(['value' => $newStatus, 'updated_at' => now()]);

        $message = $newStatus === 'open'
            ? 'Voting has been opened. Students can now cast their votes.'
            : 'Voting has been closed.';

        return redirect()->route('admin.voting-control.index')
                         ->with('success', $message);
    }
}