<?php

use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\API\QuoteController;
use App\Http\Controllers\Auth\Front\FavController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function () {
    return response()->json(['message' => 'Hello, World!']);
});

Route::post('register', [AuthController::class, 'store']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'destroy'])
    ->name('logout');


    Route::get('/quote', [QuoteController::class, 'index'])->name('quote.index');
    Route::post('/quote', [QuoteController::class, 'store'])->name('quote.store');
    Route::put('/quote/update', [QuoteController::class, 'update'])->name('quote.update');
    Route::delete('/quote/delete', [QuoteController::class, 'destroy'])->name('quote.destroy');

    Route::post('/quote/{quoteId}', [LikeController::class, 'toggleLike'])->name('quotes.toggle-like');
    
    Route::post('/update-language', [App\Http\Controllers\API\LangController::class, 'updateLanguage'])->name('changeLanguage');
});