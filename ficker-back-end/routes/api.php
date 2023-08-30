<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CardController;
use App\Models\Card;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/transaction', [TransactionController::class, 'store']);
    Route::get('/transactions', [TransactionController::class, 'showTransactions']);
    Route::get('/categories', [TransactionController::class, 'showCategories']);
    Route::post('/card', [CardController::class, 'store']);
    Route::get('/cards', [CardController::class, 'showCards']);
    Route::get('/flags', [CardController::class, 'showFlags']);
    Route::post('/best/day', [CardController::class, 'showBestDay']);
    Route::post('/invoice/card', [CardController::class, 'invoiceCard']);
});

require __DIR__.'/auth.php';