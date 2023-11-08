<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Card;
use App\Models\Flag;
use App\Models\Installment;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function store(Request $request): JsonResponse
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

        LevelController::completeMission(3);

        $response = [
            'card' => $card
        ];

        return response()->json($response, 201);
    }

    public function showCards(): JsonResponse
    {
        try {
            $cards = Auth::user()->cards;
            $response = ['data' => ['cards' => []]];
            foreach ($cards as $card) {
                $invoice = Self::invoice($card->id);
                $card->invoice = $invoice;
                array_push($response['data']['cards'], $card);
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

    public function showFlags(): JsonResponse
    {
        try {
            $flags = Flag::all();
            $response = ['data' => ['flags' => []]];
            foreach ($flags as $flag) {
                array_push($response['data']['flags'], $flag);
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

    public function invoice($id)
    {
        try {
            $card = Card::findOrFail($id);
            $installments = Installment::where([
                'card_id' => $card->id
            ])->get();
            $date_now = date('Y-m');
            $day_now = date('d');
            $invoice = 0;
            foreach ($installments as $installment) {
                $new_installment = date('Y-m', strtotime($installment->pay_day));
                if ($new_installment == $date_now and $day_now < $card->closure) {
                    $invoice += $installment->installment_value;
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

    public function showCardInvoice($id): JsonResponse
    {
        try {
            $card = Card::findOrFail($id);
            $installments = Installment::where([
                'card_id' => $card->id
            ])->get();
            $date_now = date('Y-m');
            $day_now = date('d');
            $invoice = 0;
            foreach ($installments as $installment) {
                $new_installment = date('Y-m', strtotime($installment->pay_day));
                if ($new_installment == $date_now and $day_now < $card->closure) {
                    $invoice += $installment->installment_value;
                }
            }

            $response = [
                "data" => [
                    "invoice" => $invoice
                ]
            ];
            return response()->json($response, 200);
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

    public function showInvoiceInstallments($id): JsonResponse
    {

        try {
            $card = Card::findOrFail($id);
            $installments = Installment::where([
                'card_id' => $card->id
            ])->get();
            $date_now = date('Y-m');
            $day_now = date('d');
            $response = [];
            foreach ($installments as $installment) {
                $new_installment = date('Y-m', strtotime($installment->pay_day));
                if ($new_installment == $date_now and $day_now < $card->closure) {
                    array_push($response, $installment);
                }
            }

            $response = [
                'data' => [
                    'installment' => $installment
                ]
            ];

            return response()->json($response, 200);
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
}
