<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;


class CardController extends Controller
{
    public function store(Request $request) : JsonResponse
    {

        $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'flag_id' => ['required'],
            'expiration' => ['required', 'date'],
        ]);

        $card = Card::create([
            'user_id' => Auth::user()->id,
            'flag_id' => $request->flag_id,
            'description' => $request->description,
            'expiration' => $request->expiration
        ]);

        $response = [
            'card' => $card
        ];

        return response()->json($response, 201);
    }
}