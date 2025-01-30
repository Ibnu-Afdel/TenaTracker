<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\ChallengeDetail;
use App\Livewire\ChallengesDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', ChallengesDashboard::class)->middleware('auth')->name('dashboard');
Route::get('/challenges/{challengeId}', ChallengeDetail::class)->middleware('auth')->name('challenges.detail');
require __DIR__.'/auth.php';
