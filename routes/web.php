<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Staff;
use App\Http\Controllers\Student;

// ─────────────────────────────────────────
// Guest / Root
// ─────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(match (auth()->user()->role) {
            'admin'  => 'admin.dashboard',
            'staff'  => 'staff.dashboard',
            default  => 'student.vote',
        });
    }
    return redirect()->route('login');
});

// ─────────────────────────────────────────
// Auth routes (Breeze)
// ─────────────────────────────────────────
require __DIR__ . '/auth.php';

// ─────────────────────────────────────────
// Redirect after login based on role
// ─────────────────────────────────────────
Route::middleware('auth')->get('/dashboard', function () {
    return redirect()->route(match (auth()->user()->role) {
        'admin'  => 'admin.dashboard',
        'staff'  => 'staff.dashboard',
        default  => 'student.vote',
    });
})->name('dashboard');

// ─────────────────────────────────────────
// Admin Routes
// ─────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
         ->name('dashboard');

    // Positions
    Route::resource('positions', Admin\PositionController::class);

    // Candidates
    Route::resource('candidates', Admin\CandidateController::class);

    // Voting Control
    Route::get('/voting-control', [Admin\VotingControlController::class, 'index'])
         ->name('voting-control.index');
    Route::post('/voting-control/toggle', [Admin\VotingControlController::class, 'toggle'])
         ->name('voting-control.toggle');

    // Results
    Route::get('/results', [Admin\ResultsController::class, 'index'])
         ->name('results');
});

// ─────────────────────────────────────────
// Staff Routes
// ─────────────────────────────────────────
Route::middleware(['auth', 'role:staff'])
     ->prefix('staff')
     ->name('staff.')
     ->group(function () {

    Route::get('/dashboard', [Staff\DashboardController::class, 'index'])
         ->name('dashboard');

    // Staff can manage candidates (view/create/edit only – no delete enforced in controller)
    Route::resource('candidates', Admin\CandidateController::class)
         ->except(['destroy']);

    // Staff can view positions (read-only)
    Route::get('/positions', [Admin\PositionController::class, 'index'])
         ->name('positions.index');

    // Staff can view results
    Route::get('/results', [Admin\ResultsController::class, 'index'])
         ->name('results');
});

// ─────────────────────────────────────────
// Student Routes
// ─────────────────────────────────────────
Route::middleware(['auth', 'role:student'])
     ->prefix('vote')
     ->name('student.')
     ->group(function () {

    Route::get('/',        [Student\VoteController::class, 'index'])
         ->name('vote');
    Route::post('/',       [Student\VoteController::class, 'store'])
         ->name('vote.store');
    Route::get('/success', [Student\VoteController::class, 'success'])
         ->name('vote.success');
});