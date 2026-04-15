<?php
// FILE: routes/web.php — replace existing

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Staff;
use App\Http\Controllers\Student;

// ── Root redirect ────────────────────────────────────────────
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

require __DIR__ . '/auth.php';

// Post-login role redirect
Route::middleware('auth')->get('/dashboard', function () {
    return redirect()->route(match (auth()->user()->role) {
        'admin'  => 'admin.dashboard',
        'staff'  => 'staff.dashboard',
        default  => 'student.vote',
    });
})->name('dashboard');

// ── Admin ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Elections
    Route::resource('elections', Admin\ElectionController::class);
    Route::post('/elections/{election}/toggle', [Admin\ElectionController::class, 'toggleStatus'])
         ->name('elections.toggle');

    // Positions
    Route::resource('positions', Admin\PositionController::class);

    // Candidates
    Route::resource('candidates', Admin\CandidateController::class);

    // Results
    Route::get('/results', [Admin\ResultsController::class, 'index'])->name('results');

    // Audit Log
    Route::get('/audit', [Admin\AuditLogController::class, 'index'])->name('audit');
});

// ── Staff ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:staff'])
     ->prefix('staff')
     ->name('staff.')
     ->group(function () {

    Route::get('/dashboard', [Staff\DashboardController::class, 'index'])->name('dashboard');

    // Read-only positions
    Route::get('/positions', [Admin\PositionController::class, 'index'])->name('positions.index');

    // Candidates — no delete
    Route::resource('candidates', Admin\CandidateController::class)->except(['destroy']);

    // Results
    Route::get('/results', [Admin\ResultsController::class, 'index'])->name('results');
});

// ── Student ──────────────────────────────────────────────────
Route::middleware(['auth', 'role:student'])
     ->prefix('vote')
     ->name('student.')
     ->group(function () {

    Route::get('/',        [Student\VoteController::class, 'index'])->name('vote');
    Route::post('/',       [Student\VoteController::class, 'store'])->name('vote.store');
    Route::get('/success', [Student\VoteController::class, 'success'])->name('vote.success');
});