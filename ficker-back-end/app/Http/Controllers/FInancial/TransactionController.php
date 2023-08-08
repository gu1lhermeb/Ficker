<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Category;

class TransactionController extends Controller
{

    public function store(Request $request) : JsonResponse 
    {

        $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'category' => ['required'],
            'category_description' => ['required', 'string', 'max:255'],
            'value' => ['required', 'decimal:2']
        ]);
        
        if($request->category == 0) { // Assumindo que o value da option NOVA seja 0

            $category = Category::create([
                'description' => $request->category_description,
                'type' => $request->type
            ]);

        } else {

            $category = Category::find($request->category); // Assumindo que o value das options sejam o respectivo id da categoria
        }

        $transaction = Transaction::create([
            'user_id' => Auth::user()->id,
            'category_id' => $category->id,
            'description' => $request->description,
            'date' => $request->date,
            'type' => $request->type,
            'value' => $request->value
        ]);

        $response = [
            'transaction' => $transaction
        ];

        return response()->json($response, 201);

    }
}