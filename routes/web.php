<?php

use App\Http\Controllers\PollController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public Poll Voting Page (Direct slug access)
Route::get('/poll/{poll:slug}', [PollController::class, 'show'])->name('polls.show');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // User Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Poll Management
    Route::get('/polls/create', [PollController::class, 'create'])->name('polls.create');
    Route::get('/polls/{poll}/edit', [PollController::class, 'edit'])->name('polls.edit');
    Route::post('/polls/{poll}/duplicate', [PollController::class, 'duplicate'])->name('polls.duplicate');
    Route::post('/polls/{poll}/toggle-close', [PollController::class, 'toggleClose'])->name('polls.toggle-close');
    Route::delete('/polls/{poll}', [PollController::class, 'destroy'])->name('polls.destroy');

    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
