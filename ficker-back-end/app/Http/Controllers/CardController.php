<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Card;
use App\Models\Flag;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;


class CardController extends Controller
{
    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'description' => ['required', 'string', 'min:2', 'max:50'],
            'flag_id' => ['required'],
            'expiration' => ['required', 'integer', 'min:1', 'max:31'],
        ]);


        $card = Card::create([
            'user_id' => Auth::user()->id,
            'flag_id' => $request->flag_id,
            'description' => $request->description,
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

    public function showBestDay(Request $request) :JsonResponse
    {
        try {

            $user_id = Auth::user()->id;
            $card_id = $request->card_id;
            $card = Card::where('id', $card_id)
                        ->where('user_id', $user_id)
                        ->first();

            $expiration = $card->expiration;
            $best_day = $expiration - 9;

            if($best_day < 0){
                $last_month = strtotime('last month');
                $days_last_month = date("t", $last_month);
                $days_last_month_int = (int) $days_last_month;
                $best_day = $days_last_month_int - abs($best_day);
            } elseif($expiration == 9){
                $last_month_last_day = date("d", strtotime("last day of last month"));
                $best_day = (int)$last_month_last_day;
            }

            $message = "Cartão encontrado";
            $response = [
                "message" => $message,
                "best_day" => $best_day
            ];

            return response()->json($response, 200);

        } catch (\Exception $e) {
            $errorMessage = "Cartão não encontrado";
            $response = [
                "data" => [
                    "error" => $errorMessage
                ]
            ];

            return response()->json($response, 404);
        }
    }
}