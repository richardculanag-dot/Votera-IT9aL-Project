<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->user()) {
            return redirect()->route(match ($request->user()->role) {
                'admin' => 'admin.dashboard',
                'staff' => 'staff.dashboard',
                default => 'student.dashboard',
            });
        }

        return view('landing');
    }
}
