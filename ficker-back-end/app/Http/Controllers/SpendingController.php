<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Spending;
use Illuminate\Support\Facades\Auth;

class SpendingController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'planned_spending' => ['required', 'min:1']
        ]);

        $spending = Spending::create([
            'user_id' => Auth::user()->id,
            'planned_spending' => $request->planned_spending
        ]);

        $response = [
            'spending' => $spending
        ];

        return response()->json($response, 201);
    }

    public function showSpending(): JsonResponse
    {
        $spending = Spending::where('user_id', Auth::user()->id)
                                    ->latest()
                                    ->first('planned_spending');

        $response = [
            'spending' => $spending
        ];

        return response()->json($response, 200);
    }

    public function update(Request $request): JsonResponse
    {

        Spending::find($request->id)->update($request->all());

        $spending = Spending::find($request->id);

        $response = [
            'spending' => $spending
        ];

        return response()->json($response, 200);
    }

    public function spendingByDay(): JsonResponse
    {  
        try {
            $spending = Spending::where('user_id', Auth::user()->id)->
            whereDate('created_at', date('Y-m-d'))->get();
            $response = [
                'spending' => $spending
            ];
    
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = 'Nenhuma transação foi encontrada';
            $response = [
                "data" => [
                    "message" => $errorMessage,
                    "error" => $e
                ]
            ];

            return response()->json($response, 404);
        }  
    }
    
    public function spendingByMonth(): JsonResponse
    {   
        try {
            $spending = Spending::where('user_id', Auth::user()->id)->
            whereMonth('created_at', date('m'))->get();
    
            $response = [
                'spending' => $spending
            ];
    
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = 'Nenhuma transação foi encontrada';
            $response = [
                "data" => [
                    "message" => $errorMessage,
                    "error" => $e
                ]
            ];

            return response()->json($response, 404);
        }  
    }


    public function spendingByYear(): JsonResponse
    {   
        try {
            $spending = Spending::where('user_id', Auth::user()->id)->
            whereYear('created_at', date('Y'))->get();
            $response = [
                'spending' => $spending
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = 'Nenhuma transação foi encontrada';
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
