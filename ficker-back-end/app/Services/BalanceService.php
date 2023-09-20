<?php

namespace App\Services;

use App\Models\Transaction;

class Balance
{
    public function calculateBalance($user)
    {
        $expenses = Transaction::where('user_id', $user)->where('type_id',2)->sum('value');
        $belance = Transaction::where('user_id', $user)->where('type_id',1)->sum('value');
        $total = $belance - $expenses;
        return $total;
    }
}
