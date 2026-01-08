<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware('guest')->group(function () {

    Route::get('/otp', [OtpController::class, 'create'])
        ->name('otp.create');

    Route::post('/otp/send', [OtpController::class, 'send'])
        ->name('otp.send');    

    Route::post('/otp/verify', [OtpController::class, 'verify'])
        ->name('otp.verify');
});




Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/note/create', [NoteController::class, 'create'])->name('note.create');
    Route::get('/notes', [NoteController::class, 'index'])->name('note.index');
    Route::post('/note', [NoteController::class, 'store'])->name('note.store');
});




require __DIR__.'/auth.php';
