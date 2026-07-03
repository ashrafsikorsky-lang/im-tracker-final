<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentationController;

// Redirect homepage to login
Route::get('/', function () { 
    return redirect('/login'); 
});

// MIDDLEWARE: Only logged-in users can access these
Route::middleware(['auth'])->group(function () {
    
    // GET ROUTES (Viewing Pages)
    Route::get('/dashboard', [TournamentController::class, 'dashboard'])->name('dashboard');
    Route::get('/store-data', [TournamentController::class, 'storeData']);
    Route::get('/view-information', [TournamentController::class, 'viewInformation']);

    // POST ROUTES (Submitting Forms)
    Route::post('/store-team', [TournamentController::class, 'storeTeam']);
    Route::post('/update-team-info', [TournamentController::class, 'updateTeamInfo']);
    Route::post('/store-match', [TournamentController::class, 'storeMatch']);
    
    // THE MISSING ROUTE: This tells Laravel how to delete the team!
    Route::post('/delete-team', [TournamentController::class, 'deleteTeam']);

    // PUT ROUTE (Updating Points Inline)
    Route::put('/teams/{id}', [TournamentController::class, 'updateTeamInline'])->name('teams.update');
    
    // DELETE ROUTE (Removing Players)
    Route::delete('/delete-player/{id}', [TournamentController::class, 'destroyPlayer']);
    Route::delete('/delete-match/{id}', [TournamentController::class, 'destroyMatch']);

    // SUPPORT & DISPUTE ROUTES
    Route::get('/support', [TournamentController::class, 'createInquiry']);
    Route::post('/submit-inquiry', [TournamentController::class, 'storeInquiry']);
    Route::get('/admin-inbox', [TournamentController::class, 'adminInbox']);
    Route::delete('/resolve-inquiry/{id}', [TournamentController::class, 'destroyInquiry']);

    // DOCUMENTATION ROUTE
    Route::get('/docs', [DocumentationController::class, 'index'])->name('docs.index');
});

require __DIR__.'/auth.php';