<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Cache\Repository;

class TransactionController extends Controller
{

    public function store(Request $request) : JsonResponse
    {

        $request->validate([
            'description' => ['required', 'string', 'max:50'],
            'date' => ['required', 'date'],
            'type' => ['required'],
            'value' => ['required', 'decimal:0,2']
        ]);

        if($request->category_id == '0') { // Assumindo que o value da option NOVA seja 0

            $request->validate([
                'category_description' => ['required', 'string', 'max:255'],
            ]);

            $category = Category::create([
                'category_description' => $request->category_description,
            ]);

        } else {

            $category = Category::find($request->category_id); // Assumindo que o value das options sejam o respectivo id da categoria
        }

        $transaction = Transaction::create([
            'user_id' => Auth::user()->id,
            'card_id' => $request->card_id,
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

    public function showCategories() :JsonResponse
    {
        $categories = Category::all();
        $response = [];
        foreach($categories as $category){
            array_push($response, $category);
        }
        return response()->json($response, 200);
    }

    public function showTransactions(){
        $transactions = Transaction::where('user_id', Auth::user()->id)->get();
        $response = [];
        foreach($transactions  as $transaction){
            array_push($response, $transaction);
        }
        return response()->json($response, 200);
    }
}