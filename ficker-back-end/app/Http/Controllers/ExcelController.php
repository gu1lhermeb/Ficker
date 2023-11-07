<?php

namespace App\Http\Controllers;

use App\Exports\DadosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Transaction;
use  App\Models\Category;
use App\Models\Spending;


use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function exportarDados()
    {   
        // 'user_id', user
        // 'card_id', card - pode ser null
        // 'category_id', Join com categoria
        // 'payment_method_id', Join com 
        // 'transaction_description',
        // 'date',
        // 'type_id',
        // 'transaction_value',
        // 'installments'
        $dados = Transaction::join('categories', 'transactions.category_id', '=', 'categories.id')
                            ->join('users', 'transactions.user_id', '=', 'users.id')
                            ->join('cards', 'transactions.card_id', '=', 'cards.id')
                            ->select('users.name','transactions.transaction_description', 
                            'transactions.date', 'transactions.transaction_value', 'transactions.installments', 'cards.card_description','categories.category_description')->get();

        return Excel::download(new DadosExport($dados), 'dados.xlsx');
    }
}
