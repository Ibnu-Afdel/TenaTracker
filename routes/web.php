<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\ChallengeDetail;
use App\Livewire\ChallengesDashboard;
use App\Livewire\SharedChallenge;
use App\Livewire\JournalEntryPage;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Public routes - no auth required
Route::get('/shared/challenges/{shareToken}', SharedChallenge::class)->name('shared.challenges');
Route::get('/shared/journal/{shareToken}', App\Livewire\SharedJournalEntry::class)->name('shared.journal');

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/dashboard', ChallengesDashboard::class)->name('dashboard');
    Route::get('/challenges/{challengeId}', ChallengeDetail::class)->name('challenges.detail');
    Route::get('/journal/create/{challengeId?}', JournalEntryPage::class)->name('journal.create');
    Route::get('/journal/{entry}', JournalEntryPage::class)->name('journal.edit');
    Route::get('/challenges/{challengeId}/journal/{entry}', JournalEntryPage::class)->name('journal.view');
});

require __DIR__.'/auth.php';
