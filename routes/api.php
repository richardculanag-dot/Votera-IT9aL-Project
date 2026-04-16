<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LiveStatsController;

Route::prefix('election')->group(function () {
    Route::get('/current', [LiveStatsController::class, 'currentElection']);
    Route::get('/{electionId}/stats', [LiveStatsController::class, 'electionStats']);
    Route::get('/{electionId}/results', [LiveStatsController::class, 'liveResults']);
});

Route::get('/dashboard/stats', [LiveStatsController::class, 'dashboardStats']);