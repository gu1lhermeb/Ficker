<?php

namespace App\Http\Controllers;

use App\Exports\DadosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Transaction;


use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function exportarDados()
    {   
        $dados = Transaction::select('id', 'transaction_description', 'date', 'transaction_value')->get();
        return Excel::download(new DadosExport($dados), 'dados.xlsx');
    }
}
