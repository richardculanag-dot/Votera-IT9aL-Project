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
            default  => 'student.dashboard',
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
        default  => 'student.dashboard',
    });
})->name('dashboard');

// ── Admin ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Staff Management
    Route::get('/staff', [Admin\StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [Admin\StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [Admin\StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{staff}/edit', [Admin\StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{staff}', [Admin\StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}', [Admin\StaffController::class, 'destroy'])->name('staff.destroy');

    // Students
    Route::get('/students', [Admin\StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [Admin\StudentController::class, 'show'])->name('students.show');

    // Votes
    Route::get('/votes', [Admin\VoteController::class, 'index'])->name('votes.index');

    // Elections
    Route::resource('elections', Admin\ElectionController::class);
    Route::post('/elections/{election}/toggle', [Admin\ElectionController::class, 'toggleStatus'])
         ->name('elections.toggle');

    // Election-scoped positions and candidates
    Route::get('/elections/{election}/positions', [Admin\PositionController::class, 'index'])
         ->name('elections.positions.index');
    Route::get('/elections/{election}/positions/create', [Admin\PositionController::class, 'create'])
         ->name('elections.positions.create');
    Route::post('/elections/{election}/positions', [Admin\PositionController::class, 'store'])
         ->name('elections.positions.store');
    Route::get('/elections/{election}/positions/{position}/edit', [Admin\PositionController::class, 'edit'])
         ->name('elections.positions.edit');
    Route::put('/elections/{election}/positions/{position}', [Admin\PositionController::class, 'update'])
         ->name('elections.positions.update');
    Route::delete('/elections/{election}/positions/{position}', [Admin\PositionController::class, 'destroy'])
         ->name('elections.positions.destroy');

    Route::get('/elections/{election}/positions/{position}/candidates', [Admin\CandidateController::class, 'index'])
         ->name('elections.positions.candidates.index');
    Route::get('/elections/{election}/positions/{position}/candidates/create', [Admin\CandidateController::class, 'create'])
         ->name('elections.positions.candidates.create');
    Route::post('/elections/{election}/positions/{position}/candidates', [Admin\CandidateController::class, 'store'])
         ->name('elections.positions.candidates.store');
    Route::get('/elections/{election}/positions/{position}/candidates/{candidate}/edit', [Admin\CandidateController::class, 'edit'])
         ->name('elections.positions.candidates.edit');
    Route::put('/elections/{election}/positions/{position}/candidates/{candidate}', [Admin\CandidateController::class, 'update'])
         ->name('elections.positions.candidates.update');
    Route::delete('/elections/{election}/positions/{position}/candidates/{candidate}', [Admin\CandidateController::class, 'destroy'])
         ->name('elections.positions.candidates.destroy');

    // Results
    Route::get('/results', [Admin\ResultsController::class, 'index'])->name('results');
    Route::get('/results/export/pdf', [Admin\ResultsController::class, 'exportPdf'])->name('results.export');

    // Audit Log
    Route::get('/audit', [Admin\AuditLogController::class, 'index'])->name('audit');
});

// ── Staff ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:staff'])
     ->prefix('staff')
     ->name('staff.')
     ->group(function () {

    Route::get('/dashboard', [Staff\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/elections', [Admin\ElectionController::class, 'index'])->name('elections.index');
    Route::get('/elections/{election}', [Admin\ElectionController::class, 'show'])->name('elections.show');

    // Election-scoped positions and candidates
    Route::get('/elections/{election}/positions', [Admin\PositionController::class, 'index'])
         ->name('elections.positions.index');
    Route::get('/elections/{election}/positions/create', [Admin\PositionController::class, 'create'])
         ->name('elections.positions.create');
    Route::post('/elections/{election}/positions', [Admin\PositionController::class, 'store'])
         ->name('elections.positions.store');
    Route::get('/elections/{election}/positions/{position}/edit', [Admin\PositionController::class, 'edit'])
         ->name('elections.positions.edit');
    Route::put('/elections/{election}/positions/{position}', [Admin\PositionController::class, 'update'])
         ->name('elections.positions.update');

    Route::get('/elections/{election}/positions/{position}/candidates', [Admin\CandidateController::class, 'index'])
         ->name('elections.positions.candidates.index');
    Route::get('/elections/{election}/positions/{position}/candidates/create', [Admin\CandidateController::class, 'create'])
         ->name('elections.positions.candidates.create');
    Route::post('/elections/{election}/positions/{position}/candidates', [Admin\CandidateController::class, 'store'])
         ->name('elections.positions.candidates.store');
    Route::get('/elections/{election}/positions/{position}/candidates/{candidate}/edit', [Admin\CandidateController::class, 'edit'])
         ->name('elections.positions.candidates.edit');
    Route::put('/elections/{election}/positions/{position}/candidates/{candidate}', [Admin\CandidateController::class, 'update'])
         ->name('elections.positions.candidates.update');

    // Results
    Route::get('/results', [Admin\ResultsController::class, 'index'])->name('results');
    Route::get('/results/export/pdf', [Admin\ResultsController::class, 'exportPdf'])->name('results.export');
});

// ── Student ──────────────────────────────────────────────────
Route::middleware(['auth', 'role:student'])
     ->prefix('student')
     ->name('student.')
     ->group(function () {

    Route::get('/', [Student\VoteController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [Student\VoteController::class, 'profile'])->name('profile');
    Route::put('/profile', [Student\VoteController::class, 'updateProfile'])->name('profile.update');
    Route::get('/elections', [Student\VoteController::class, 'elections'])->name('elections');
    Route::get('/elections/{election}/vote', [Student\VoteController::class, 'index'])->name('vote');
    Route::post('/elections/{election}/vote', [Student\VoteController::class, 'store'])->name('vote.store');
    Route::get('/history', [Student\VoteController::class, 'history'])->name('history');
    Route::get('/success', [Student\VoteController::class, 'success'])->name('vote.success');
});

// Backward compatibility from old /vote URLs
Route::middleware(['auth', 'role:student'])->get('/vote', function () {
    return redirect()->route('student.elections');
});