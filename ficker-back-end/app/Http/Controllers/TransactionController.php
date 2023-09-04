<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Card;
use App\Models\Installment;
use App\Models\Type;
use Carbon\Carbon;

class TransactionController extends Controller
{

    public function store(Request $request): JsonResponse
    {

        $request->validate([
            'description' => ['required', 'string', 'max:50'],
            'category_id' => ['required'],
            'date' => ['required', 'date'],
            'type_id' => ['required'],
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
                        "error" => $errorMessage
                    ]
                ];
    
                return response()->json($response, 404);
            }
        }

        // Cadastrando nova categoria

        if ($request->category_id == 0) {

            $request->validate([
                'category_description' => ['required', 'string', 'max:50', 'unique:categories'],
            ]);

            $category = CategoryController::store($request->category_description, $request->type_id);

        } else {

            $category = Category::find($request->category_id);
        }

        // Cadastrando transação

        if (is_null($request->installments)) { // Sem parcelas

            $transaction = Transaction::create([
                'user_id' => Auth::user()->id,
                'category_id' => $category->id,
                'description' => $request->description,
                'date' => $request->date,
                'value' => $request->value,
                'type_id' => $request->type_id
            ]);

            $response = [
                'data' => [
                    'trasanction' => $transaction
                ]
            ];

            return response()->json($response, 201);

        } else { // Com parcelas

            $transaction = Transaction::create([
                'user_id' => Auth::user()->id,
                'category_id' => $category->id,
                'card_id' => $request->card_id,
                'description' => $request->description,
                'date' => $request->date,
                'value' => $request->value,
                'installments' => $request->installments,
                'type_id' => $request->type_id
            ]);

            $response = [];
            $pay_day = date('Y-m-d');
            $new_pay_day_formated = $pay_day;
            $i = $request->installments;
            $value = (float)$request->value / (float)$request->installments;

            for($i = 1; $i <= $request->installments; $i++){

                if($i == 1){
                    $installment = Installment::create([
                        'transaction_id' => $transaction->id,
                        'description' => $request->description,
                        'value' => $value,
                        'card_id' => $request->card_id,
                        'pay_day' => $pay_day
                    ]);

                    array_push($response, $installment);

                } else {
                    $new_pay_day = strtotime('+1 months', strtotime($pay_day));
                    $new_pay_day_formated = date('Y-m-d', $new_pay_day);
                    $installment = Installment::create([
                        'transaction_id' => $transaction->id,
                        'description' => $request->description,
                        'value' => $value,
                        'card_id' => $request->card_id,
                        'pay_day' => $new_pay_day_formated
                    ]);

                    array_push($response, $installment);
                }

                $pay_day = $new_pay_day_formated;

                }

                return response()->json($response, 200);
            }
        }

    public function showCategories($id): JsonResponse
    {
        $categories = Type::find($id)->categories;

        $response = [];
        foreach($categories as $category){
            array_push($response, $category);
        }
        
        return response()->json($response, 200);
    }

    public function showIncomes(): JsonResponse
    {
        try {

            $transactions = Transaction::where([
                'user_id' => Auth::user()->id,
                'type_id' => 1
            ]);
    
            $response = [];
            foreach($transactions  as $transaction){
                array_push($response, $transaction);
            }
    
            return response()->json($response, 200);

        } catch(\Exception $e) {

            $errorMessage = "Nenhuma transação de entrada encontrada.";
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];
            return response()->json($response, 404);

        }
    }

    public function showOutgoings(): JsonResponse
    {
        try {

            $transactions = Transaction::where([
                'user_id' => Auth::user()->id,
                'type_id' => 2
            ]);
    
            $response = [];
            foreach($transactions  as $transaction){
                array_push($response, $transaction);
            }
    
            return response()->json($response, 200);

        } catch(\Exception $e) {

            $errorMessage = "Nenhuma transação de saída encontrada.";
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];
            return response()->json($response, 404);
        }
    }

    public function showCardTransactions($id): JsonResponse
    {
        try {

            $transactions = Transaction::where([
                'card_id' => $id
            ]);
    
            $response = [];
            foreach($transactions  as $transaction){
                array_push($response, $transaction);
            }
    
            return response()->json($response, 200);

        } catch(\Exception $e) {

            $errorMessage = "Este cartão não possui transações.";
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];
            return response()->json($response, 404);
        }
    }

    public function showTransactionInstallments($id): JsonResponse
    {
        try {

            $installments = Installment::where([
                'transaction_id' => $id
            ]);
    
            $response = [];
            foreach($installments  as $installment){
                array_push($response, $installment);
            }
    
            return response()->json($response, 200);

        } catch(\Exception $e) {

            $errorMessage = "Esta transação não possui parcelas.";
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];
            return response()->json($response, 404);
        }

    }
}