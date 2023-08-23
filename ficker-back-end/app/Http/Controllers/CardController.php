<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Card;
use App\Models\Flag;
use Illuminate\Support\Facades\Auth;


class CardController extends Controller
{
    public function store(Request $request) : JsonResponse
    {

        $request->validate([
            'description' => ['required', 'string', 'max:100'],
            'flag_id' => ['required'],
            'expiration' => ['required', 'integer', 'min_digits:2', 'max_digits:2'],
            'best_day' => ['required', 'integer', 'min_digits:2', 'max_digits:2']
        ]);

        $card = Card::create([
            'user_id' => Auth::user()->id,
            'flag_id' => $request->flag_id,
            'description' => $request->description,
            'expiration' => $request->expiration,
            'best_day' => $request->best_day
        ]);

        $response = [
            'card' => $card
        ];

        return response()->json($response, 201);
    }

    public function showCards() :JsonResponse
    {
        $cards = Auth::user()->cards;
        $response = [];
        foreach($cards as $card){
            array_push($response, $card);
        }
        return response()->json($response, 200);
    }

    public function showFlags() :JsonResponse
    {
        $flags = Flag::all();
        $response = [];
        foreach($flags as $flag){
            array_push($response, $flag);
        }
        return response()->json($response, 200);
    }
}