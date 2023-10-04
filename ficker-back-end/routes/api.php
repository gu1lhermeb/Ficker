<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SpendingController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\InstallmentController;

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
    Route::post('/transaction/store', [TransactionController::class, 'store']);
    Route::get('/transactions', [TransactionController::class, 'showTransactions']);
    Route::get('/transactions/{id}', [TransactionController::class, 'showTransaction']);
    Route::get('/transactions/type/{id}', [TransactionController::class, 'showTransactionsByType']); // Entradas ou saídas
    Route::get('/transactions/card/{id}', [TransactionController::class, 'showTransactionsByCard']); // Transações de um cartão de crédito
    Route::get('/transactions/{id}/installments', [InstallmentController::class, 'showInstallments']); // Parcelas de uma transação
    Route::delete('/transactions/delete/{id}', [TransactionController::class, 'destroy']);
    Route::put('/transactions/update/{id}', [TransactionController::class, 'update']);
    Route::post('/category/store', [CategoryController::class, 'store']);
    Route::get('/categories', [CategoryController::class, 'showCategories']);
    Route::get('/categories/{id}', [CategoryController::class, 'showCategory']);
    Route::get('/categories/type/{id}', [CategoryController::class, 'showCategoriesByType']); // Categorias de entrada (1), saída (2) ou cartão de crédito (3)
    Route::post('/card', [CardController::class, 'store']);
    Route::get('/cards', [CardController::class, 'showCards']);
    Route::get('/cards/{id}/invoice', [CardController::class, 'showCardInvoice']);
    Route::get('/cards/{id}/installments', [CardController::class, 'showInvoiceInstallments']); // Transações de um cartão no mês atual
    Route::get('/flags', [CardController::class, 'showFlags']);
    Route::get('/spending', [SpendingController::class, 'showSpending']);
    Route::post('/spending/store', [SpendingController::class, 'store']);
    Route::put('/spending/update/{id}', [SpendingController::class, 'update']);
    Route::get('/balance', [BalanceController::class, 'balance']); //Mostra o saldo atual;
    Route::get('/spending/day', [TransactionController::class, 'transactionSpendingByDay']); //Mostra o gasto do dia atual;
    Route::get('/spending/month', [TransactionController::class, 'transactionSpendingByMonth']); //Mostra o gasto do mês atual;
    Route::get('/spending/year', [TransactionController::class, 'transactionSpendingByYear']); //Mostra o gasto do ano atual;
});

require __DIR__.'/auth.php';
