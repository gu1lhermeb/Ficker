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
            'transaction_description' => ['required', 'string', 'max:50'],
            'category_id' => ['required'],
            'category_description' => ['required_if:category_id,0', 'string', 'max:50'],
            'date' => ['required', 'date'],
            'type_id' => ['required', 'min:1', 'max:2'],
            'transaction_value' => ['required', 'decimal:0,2', 'min:1'],
            'payment_method_id' => ['required_if:type_id,2', 'prohibited_if:type_id,1'],
            'installments' => ['required_if:payment_method_id,4', 'prohibited_unless:payment_method_id,4', 'min:1'],
            'card_id' => ['required_if:payment_method_id,4', 'prohibited_unless:payment_method_id,4']
        ]);

        // Validando card id

        if ($request->payment_method_id == 4) {

            try {

                Card::findOrFail($request->card_id);
            } catch (\Exception $e) {
                $errorMessage = "Error: Cartão não encontrado.";
                $response = [
                    "data" => [
                        "message" => $errorMessage,
                        "error" => $e
                    ]
                ];

                return response()->json($response, 404);
            }
        }

        // Cadastrando nova categoria

        if ($request->category_id == 0) {

            $category = CategoryController::storeInTransaction($request->category_description, $request->type_id);

        } else {

            $category = Category::find($request->category_id);
        }

        // Cadastrando transação

        if (is_null($request->installments)) { // Entrada e saída sem parcelas

            $transaction = Transaction::create([
                'user_id' => Auth::user()->id,
                'category_id' => $category->id,
                'type_id' => $request->type_id,
                'payment_method_id' => $request->payment_method_id,
                'transaction_description' => $request->transaction_description,
                'date' => $request->date,
                'transaction_value' => $request->transaction_value,
            ]);

            LevelController::completeMission($request->type_id);
            
            $response = [
                'data' => [
                    'trasanction' => $transaction
                ]
            ];

            return response()->json($response, 201);

        } else { // Saídas de cartão de crédito

            $transaction = Transaction::create([
                'user_id' => Auth::user()->id,
                'category_id' => $category->id,
                'type_id' => $request->type_id,
                'payment_method_id' => $request->payment_method_id,
                'card_id' => $request->card_id,
                'transaction_description' => $request->transaction_description,
                'date' => $request->date,
                'transaction_value' => $request->transaction_value,
                'installments' => $request->installments,
            ]);

            $response = [];
            $pay_day = date('Y-m-d');
            $new_pay_day_formated = $pay_day;
            $i = $request->installments;
            $value = (float)$request->transaction_value / (float)$request->installments;
            $value = (float) number_format($value, 2, '.', '');
            $firstInstallment = $request->transaction_value - ($value * ($i - 1));
            $firstInstallment =  (float) number_format($firstInstallment, 2, '.', '');


            for ($i = 1; $i <= $request->installments; $i++) {

                if ($i == 1) {
                    $installment = Installment::create([
                        'transaction_id' => $transaction->id,
                        'installment_description' => $request->transaction_description . ' ' . $i . '/' . $request->installments,
                        'installment_value' => $firstInstallment,
                        'card_id' => $request->card_id,
                        'pay_day' => $pay_day
                    ]);

                    array_push($response, $installment);
                } else {
                    $new_pay_day = strtotime('+1 months', strtotime($pay_day));
                    $new_pay_day_formated = date('Y-m-d', $new_pay_day);
                    $installment = Installment::create([
                        'transaction_id' => $transaction->id,
                        'installment_description' => $request->transaction_description . ' ' . $i . '/' . $request->installments,
                        'installment_value' => $value,
                        'card_id' => $request->card_id,
                        'pay_day' => $new_pay_day_formated
                    ]);

                    array_push($response, $installment);
                }

                $pay_day = $new_pay_day_formated;
            }

            LevelController::completeMission(4);

            return response()->json($response, 200);
        }
    }

    public function showTransactions(): JsonResponse
    {
        try {
            $transactions = Transaction::orderBy('date', 'desc')
                ->where('user_id', Auth::id())
                ->get();

            $most_expensive_transaction = Transaction::orderBy('transaction_value', 'desc')
                ->where(['user_id'=> Auth::id(),
                    'type_id' => 2])
                ->first()->transaction_value;

            $response = ['data' => ['transactions' => []], 'most_expensive' => $most_expensive_transaction, 'total' => count($transactions)];

            foreach($transactions as $transaction) {
                $description = Category::find($transaction->category_id)->category_description;
                $transaction->category_description = $description;
                array_push($response['data']['transactions'], $transaction);
            }

            return response()->json($response, 200);

        } catch (\Exception $e) {
            $errorMessage = 'Nenhuma transação foi encontrada';
            $response = [
                "data" => [
                    "message" => $errorMessage,
                    "error" => $e->getMessage()
                ]
            ];

            return response()->json($response, 404);
        }
    }

    public function showTransaction($id): JsonResponse
    {
        try {

            $transaction = Transaction::find($id);

            $description = Category::find($transaction->category_id)->category_description;
            $transaction->category_description = $description;

            $response = [
                'data' => [
                    'transaction' => $transaction
                ]
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = "Erro: Transação não encontrada.";
            $response = [
                "data" => [
                    "message" => $errorMessage,
                    "error" => $e->getMessage()
                ]
            ];
            return response()->json($response, 404);
        }
    }

    public function showTransactionsByType($id): JsonResponse
    {
        try {
            
            $transactions = Transaction::where([
                'user_id' => Auth::user()->id,
                'type_id' => $id
            ])->orderBy('date', 'desc')->get();

            $response = ['data' => ['transactions' => []], 'total' => count($transactions)];

            foreach ($transactions  as $transaction) {
                $description = Category::find($transaction->category_id)->category_description;
                $transaction->category_description = $description;
                array_push($response['data']['transactions'], $transaction);
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {

            $errorMessage = "Erro: Nenhuma transação encontrada.";
            $response = [
                "data" => [
                    "message" => $errorMessage,
                    "error" => $e->getMessage()
                ]
            ];
            return response()->json($response, 404);
        }
    }

    public function showTransactionsByCard($id): JsonResponse
    {
        try {

            $transactions = Transaction::where([
                'card_id' => $id
            ])->get();

            $response = ['data' => ['transactions' => []]];
            foreach ($transactions  as $transaction) {
                $description = Category::find($transaction->category_id)->category_description;
                $transaction->category_description = $description;
                array_push($response['data']['transactions'], $transaction);
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = "Erro: Este cartão não possui transações.";
            $response = [
                "data" => [
                    "message" => $errorMessage,
                    "error" => $e->getMessage()
                ]
            ];
            return response()->json($response, 404);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            Transaction::findOrFail($request->id);
        } catch (\Exception $e) {

            $errorMessage = "Essa transação não existe ou já foi excluída.";
            $response = [
                "data" => [
                    "message" => $errorMessage,
                    "error" => $e->getMessage()
                ]
            ];
            return response()->json($response, 404);
        }

        try {

            $request->validate([
                'transaction_description' => ['string', 'max:50'],
                'date' => ['date'],
                'transaction_value' => ['decimal:0,2', 'min:1'],
                'payment_method_id' => ['min:1', 'max:4'],
                'installments' => ['min:1'],
            ]);

            Transaction::find($request->id)->update($request->only([
                'transaction_description',
                'category_id',
                'date',
                'transaction_value',
                'payment_method_id',
                'installments'
            ]));

            $transaction = Transaction::find($request->id);

            if($transaction->payment_method_id == 4) {

                if(!(is_null($request->installments))) {

                    $installments = Installment::where('transaction_id', $request->id)->get();
                    $transaction = Transaction::find($request->id);

                    Installment::where('transaction_id', $request->id)->delete();
                    $date = $transaction->date;
                    for ($i = 1; $i <= $request->installments; $i++) {

                        Installment::create([
                            'transaction_id' => $request->id,
                            'installment_description' => $transaction->transaction_description.' '.$i.'/'.$request->installments,
                            'installment_value' => $transaction->transaction_value / $request->installments,
                            'card_id' => $transaction->card_id,
                            'pay_day' => $date
                        ]);

                        $date = strtotime('+1 months', strtotime($date));
                        $date = date('Y-m-d', $date);
                    }
                }

                if(!(is_null($request->transaction_value))) {

                    Installment::where('transaction_id', $request->id)->get()->each(function($installment) use ($request) {
                        
                        $transaction = Transaction::find($request->id);
    
                        $installment->update([
                            'installment_value' => $request->transaction_value / $transaction->installments
                        ]);
                    });
                }

                if(!(is_null($request->transaction_description))) {

                    $count = 1;
                    Installment::where('transaction_id', $request->id)->get()->each(function($installment) use ($request, &$count){

                        $transaction = Transaction::find($request->id);
    
                        $installment->update([
                            'installment_description' => $request->transaction_description.' '.$count.'/'.$transaction->installments,
                        ]);

                        $count++;
                    });
                }

                if(!(is_null($request->date))) {

                    $date = $request->date;
                    Installment::where('transaction_id', $request->id)->get()->each(function($installment) use (&$date){
    
                        $installment->update([
                            'pay_day' => $date,
                        ]);

                        $date = strtotime('+1 months', strtotime($date));
                        $date = date('Y-m-d', $date);
                    });
                }
            }

            $installments = Installment::where('transaction_id', $request->id)->get();

            $response = [
                "transaction" => $transaction,
                "installments" => $installments
            ];

            return response()->json($response, 200);

        } catch (\Exception $e) {

            $errorMessage = $e->getMessage();
            $response = [
                "data" => [
                    "message" => $errorMessage,
                ]
            ];
            return response()->json($response, 400);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Transaction::findOrFail($id)->delete();

            $message = 'Transação excluída com sucesso.';

            $response = [
                'data' => [
                    'message' => $message
                ]
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {

            $errorMessage = "Erro: Esta transação não existe.";
            $response = [
                "data" => [
                    "message" => $errorMessage,
                    "error" => $e->getMessage()
                ]
            ];
            return response()->json($response, 404);
        }
    }

}
