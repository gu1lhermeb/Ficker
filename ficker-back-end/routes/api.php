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
    Route::get('/transactions/incomes', [TransactionController::class, 'showIncomes']); // Entradas
    Route::get('/transactions/outgoings', [TransactionController::class, 'showOutgoings']); // Saídas
    Route::get('/transactions/{id}', [TransactionController::class, 'showTransactionInstallments']); // Parcelas de uma transação
    Route::get('/categories/{id}', [TransactionController::class, 'showCategories']); // Categorias de entrada (1), saída (2) ou cartão de crédito (3)
    Route::post('/card', [CardController::class, 'store']);
    Route::get('/cards', [CardController::class, 'showCards']);
    Route::get('/cards/{id}', [TransactionController::class, 'showCardTransactions']); // Transações de um cartão de crédito
    Route::get('/flags', [CardController::class, 'showFlags']);
    Route::post('/best/day', [CardController::class, 'showBestDay']);
    Route::post('/invoice/card', [CardController::class, 'invoiceCard']);
});

require __DIR__.'/auth.php';