<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Card;
use App\Models\Flag;
use App\Models\Transaction;
use App\Models\Installment;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class CardController extends Controller
{
    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'card_description' => ['required', 'string', 'min:2', 'max:50'],
            'flag_id' => ['required'],
            'expiration' => ['required', 'integer', 'min:1', 'max:31'],
            'closure' => ['required', 'integer', 'min:1', 'max:31']
        ]);


        $card = Card::create([
            'user_id' => Auth::user()->id,
            'flag_id' => $request->flag_id,
            'card_description' => $request->card_description,
            'expiration' => $request->expiration,
            'closure' => $request->closure
        ]);

        $response = [
            'card' => $card
        ];

        return response()->json($response, 201);
    }

    public function showCards() :JsonResponse
    {
        try {
            $cards = Auth::user()->cards;
            $response = [];
            foreach($cards as $card){
                $invoice = Self::showInvoiceCard($card->id);
                $card->invoice = $invoice;
                array_push($response, $card);
            }
            return response()->json($response, 200);

        } catch (\Exception $e) {
            $errorMessage = "Nenhum cartão cadastrado";
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];
            return response()->json($response, 404);
        }

    }

    public function showFlags() :JsonResponse
    {
        try {

            $flags = Flag::all();
            $response = [];
            foreach($flags as $flag){
                array_push($response, $flag);
            }
            return response()->json($response, 200);

        } catch (\Exception $e) {
            $errorMessage = "Nenhuma bandeira foi encontrada";
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];

            return response()->json($response, 404);
        }
    }

    public function showInvoiceCard($id)
    {

        try {
            $card = Card::findOrFail($id);
            $installments = Installment::where([
                'card_id' => $card->id
            ])->get();
            $date_now = date('Y-m');
            $day_now = date('d');
            $invoice = 0;
            foreach($installments as $installment){
                $new_installment = date('Y-m', strtotime($installment->pay_day));
                if($new_installment == $date_now and $day_now < $card->closure){
                    $invoice += $installment->value;
                }
            }

            return $invoice;

        } catch (\Exception $e) {
            $errorMessage = "Erro: " + $e;
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];
            return response()->json($response, 404);
        }
    }

    public function showCardTransactionsByMonth($id) { // Verifcar a questão do dia de fechamento da fatura (closure)

        try {

            $card = Card::findOrFail($id);

            $transactions = Transaction::whereMonth('date', now()->month)
                                    ->whereYear('date', now()->year)
                                    ->where('user_id', Auth::user()->id)
                                    ->where('card_id', $id)
                                    ->get();

            $response = [];
            foreach($transactions as $transaction) {
                $description = CategoryController::showCategory($transaction->category_id);
                $transaction->category_description = $description;
                array_push($response, $transaction);
            }

            return response()->json($response, 200);

        } catch(\Exception $e) {

            $errorMessage = "Erro: Este cartão não possui transações.";
            $response = [
                "data" => [
                    "message" => $errorMessage,
                    "error" => $e
                ]
            ];
            return response()->json($response, 404);
        }
    }
}
