<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'logged_in' => auth()->check(),
        'user' => auth()->user(),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Events
    Route::get('/api/events', [EventController::class, 'index']); // Get all events
    Route::post('/api/events', [EventController::class, 'store']); // Create a new event
    Route::get('/events/{event}', [EventController::class, 'show']); // Get a single event
    Route::put('/api/events/{event}', [EventController::class, 'update']); // Update an event
    Route::delete('/api/events/{event}', [EventController::class, 'destroy']); // Delete an event
    Route::delete('/api/events', [EventController::class, 'destroy_all']); // Delete all performances for an event
    Route::get('/events', [EventController::class, 'test']); // test



    // Performances
    Route::post('/api/events/{eventId}/performances', [PerformanceController::class, 'store']); // Create a new performance
    Route::put('/api/performances/{performance}/start', [PerformanceController::class, 'startPerformance']); // Start a performance
    Route::put('/api/performances/{performance}/end', [PerformanceController::class, 'endPerformance']); // End a performance
});

require __DIR__.'/auth.php';
