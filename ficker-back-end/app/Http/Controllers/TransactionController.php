<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Card;
use App\Models\Installment;

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

        if($request->type_id == 3) {

            $request->validate([
                'installments' => ['required', 'min:1', 'max:12'],
                'card_id' => ['required']
            ]);
            
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
            $value = (float) number_format($value,2,'.','');
            $firstInstallment = $request->value - ($value * ($i-1));
            $firstInstallment =  (float) number_format($firstInstallment,2,'.','');


            for($i = 1; $i <= $request->installments; $i++){

                if($i == 1){
                    $installment = Installment::create([
                        'transaction_id' => $transaction->id,
                        'description' => $request->description,
                        'value' => $firstInstallment,
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

        public function showTransaction($id): JsonResponse
    {
        try {

            $transaction = Transaction::find($id);

            $description = CategoryController::showCategory($transaction->category_id);
            $transaction->category_description = $description;

            $response = [
                "transaction" => $transaction
            ];

            return response()->json($response, 200);

        } catch(\Exception $e) {
            $errorMessage = "Erro: Transação não encontrada.";
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];
            return response()->json($response, 404);
        }
    }

    public function showTransactions($id): JsonResponse
    {
        try {

            $transactions = Transaction::where([
                'user_id' => Auth::user()->id,
                'type_id' => $id
            ])->get();

            $response = [];
            foreach($transactions  as $transaction){
                $description = CategoryController::showCategory($transaction->category_id);
                $transaction->category_description = $description;
                array_push($response, $transaction);
            }

            return response()->json($response, 200);

        } catch(\Exception $e) {

            $errorMessage = "Erro: Nenhuma transação encontrada.";
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
            ])->get();

            $response = [];
            foreach($transactions  as $transaction){
                $description = CategoryController::showCategory($transaction->category_id);
                $transaction->category_description = $description;
                array_push($response, $transaction);
            }

            return response()->json($response, 200);

        } catch(\Exception $e) {
            $errorMessage = "Erro: Este cartão não possui transações.";
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];
            return response()->json($response, 404);
        }
    }

    public function showInstallments($id): JsonResponse
    {
        try {

            $installments = Installment::where([
                'transaction_id' => $id
            ])->get();

            $response = [];
            foreach($installments  as $installment){
                array_push($response, $installment);
            }

            return response()->json($response, 200);

        } catch(\Exception $e) {

            $errorMessage = "Erro: Esta transação não possui parcelas.";
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];
            return response()->json($response, 404);
        }

    }

    public function update(Request $request) {

        try {

            Transaction::findOrFail($request->id);

        } catch(\Exception $e) {    

            $errorMessage = "Erro: Esta transação não existe.";
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];
            return response()->json($response, 404);
        }

        try {

            Transaction::findOrFail($request->id)->update($request->all());

            $transaction = Transaction::find($request->id);

            $response = [
                "transaction" => $transaction
            ];

            return response()->json($response, 200);

        } catch(\Exception $e) {

            $errorMessage = "Erro: Teste.";
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];
            return response()->json($response, 404);
        }
    }

    public function destroy($id) {

        try{

            Transaction::findOrFail($id)->delete();

            return response(204);

        } catch(\Exception $e) {

            $errorMessage = "Erro: Esta transação não existe.";
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];
            return response()->json($response, 404);

        }
    }
}