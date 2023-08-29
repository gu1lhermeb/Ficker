<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Card;
use Illuminate\Cache\Repository;

class TransactionController extends Controller
{

    public function store(Request $request): JsonResponse
    {

        $request->validate([
            'description' => ['required', 'string', 'max:50'],
            'category_id' => ['required'],
            'date' => ['required', 'date'],
            'type' => ['required'],
            'value' => ['required', 'decimal:0,2']
        ]);

        // Verificando se o cartão existe no banco

        if (!(is_null($request->card_id))) {

            try {

                Card::findOrFail($request->card_id);

            } catch (\Exception $e) {
                $errorMessage = "Error: Cartão não encontrado.";
                $response = [
                    "data" => [
                        "error" => "$errorMessage"
                    ]
                ];
    
                return response()->json($response, 404);
            }
        }

        // Cadastrando nova categoria

        if ($request->category_id == '0') {

            $request->validate([
                'category_description' => ['required', 'string', 'max:255'],
            ]);

            $category = Category::create([
                'category_description' => $request->category_description,
            ]);

        } else {

            $category = Category::find($request->category_id);
        }

        // Cadastrando transação

        if (!(is_null($request->installments))) { // Com parcelas

            $response = [];

            for ($i = 1; $i <= $request->installments; $i++) {

                $transaction = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'card_id' => $request->card_id,
                    'category_id' => $category->id,
                    'description' => $request->description." ".$i."/".$request->installments,
                    'date' => $request->date,
                    'type' => $request->type,
                    'value' => $request->value / $request->installments,
                    'installments' => $request->installments
                ]);

                array_push($response, $transaction);
            }

            return response()->json($response, 201);

        } else { // Sem parcelas

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

    }

    public function showCategories(): JsonResponse
    {
        $categories = Category::all();
        $response = [];
        foreach($categories as $category){
            array_push($response, $category);
        }
        return response()->json($response, 200);
    }

    public function showTransactions(): JsonResponse
    {
        $transactions = Auth::user()->transactions;
        $response = [];
        foreach($transactions  as $transaction){
            array_push($response, $transaction);
        }
        return response()->json($response, 200);
    }
}