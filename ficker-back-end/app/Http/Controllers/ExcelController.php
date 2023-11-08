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
        $dados = Transaction::query()
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->join('users', 'transactions.user_id', '=', 'users.id')
                ->select('users.name', 'transactions.transaction_description', 'transactions.date', 'transactions.transaction_value', 'transactions.installments', 'categories.category_description')->get();

        return Excel::download(new DadosExport($dados), 'dados.xlsx');
    }
}
