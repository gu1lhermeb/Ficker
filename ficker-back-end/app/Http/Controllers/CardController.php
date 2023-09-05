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
}
