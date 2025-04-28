<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\ChallengeDetail;
use App\Livewire\ChallengesDashboard;
use App\Livewire\SharedChallenge;
use App\Livewire\JournalEntryPage;
use App\Livewire\JournalRichTextPage;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok'
    ]);
});

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
    Route::redirect('/journal', '/journal/create')->name('journal.redirect');
    Route::get('/journal/create/{challengeId?}', JournalRichTextPage::class)->name('journal.create');
    Route::get('/journal/{entry}', JournalRichTextPage::class)->name('journal.edit');
    Route::get('/challenges/{challengeId}/journal/{entry}', JournalEntryPage::class)->name('journal.view');
});


use Illuminate\Support\Facades\Artisan;

Route::get('/migrate', function () {
    $output = Artisan::call('migrate --force');
    return nl2br(Artisan::output()); // Show output for debugging
});


use Illuminate\Support\Facades\DB;

Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return "✅ Database connection is working!";
    } catch (\Exception $e) {
        return "❌ Database connection failed: " . $e->getMessage();
    }
});

Route::get('/migrate-fresh', function () {
    Artisan::call('migrate:fresh --force');
    return "Fresh migration completed!";
});

require __DIR__.'/auth.php';
