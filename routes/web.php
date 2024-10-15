<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Front\QuoteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [QuoteController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/quote', [QuoteController::class, 'store'])->name('quote.store');
    Route::put('/quote/update', [QuoteController::class, 'update'])->name('quote.update');
    Route::put('/quote/edit', [QuoteController::class, 'edit'])->name('quote.edit');
    Route::delete('/quote/{id}', [QuoteController::class, 'destroy'])->name('quote.destroy');
    Route::put('/quote/{id}', [QuoteController::class, 'update'])->name('quote.update');
});

require __DIR__ . '/auth.php';
