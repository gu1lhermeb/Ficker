<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Spending;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    public function balance(){

        $incomes = Transaction::where([
            'user_id' => Auth::user()->id,
            'type_id' => 1,
        ])->sum('value');
        
        $outgoings = Transaction::where([
            'user_id' => Auth::user()->id,
            'type_id' => 2,
        ])->sum('value');

        $balance = $incomes - $outgoings;

        $spending = Spending::where('user_id', Auth::user()->id)
                                    ->latest()
                                    ->first('value');

    
        // $spending->spending = $spending->value;

        $spending->balance = $balance;

        $real = Transaction::whereMonth('date', now()->month)
                                ->whereYear('date', now()->year)
                                ->where('user_id', Auth::user()->id)
                                ->where('type_id', 2)
                                ->sum('value');

        $spending->real_spending = $real;

        $response = [
            'spending' => $spending
        ];

        return response()->json($response, 200);
    }
}
