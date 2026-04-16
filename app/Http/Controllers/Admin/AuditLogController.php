<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'management');

        if ($type === 'votes') {
            $logs = AuditLog::with('user')
                ->where('action', 'like', 'vote_%')
                ->latest()
                ->paginate(30);
        } else {
            $logs = AuditLog::with('user')
                ->where('action', 'not like', 'vote_%')
                ->latest()
                ->paginate(30);
        }

        return view('admin.audit', compact('logs', 'type'));
    }
}