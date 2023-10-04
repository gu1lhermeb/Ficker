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
        try {
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
        } catch (\Exception $e) {
            $errorMessage = 'Erro na busca de gastos planejados.';
            $response = [
                'data' => [
                    'message' => $errorMessage,
                    'error' => $e->getMessage()
                ]
            ];

            return response()->json($response, 500);
        }
    }

    public function showSpending(): JsonResponse
    {
        try {
            $spending = Spending::where('user_id', Auth::user()->id)
                ->latest()
                ->first('value');

            $response = [
                'data' => [
                    'spending' => $spending
                ]
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = 'Erro ao exibit os gastos planejados.';
            $response = [
                'data' => [
                    'message' => $errorMessage,
                    'error' => $e->getMessage()
                ]
            ];

            return response()->json($response, 500);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            Spending::find($request->id)->update($request->all());

            $spending = Spending::find($request->id);

            $response = [
                'data' => [
                    'spending' => $spending
                ]
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = 'Erro ao atualizar os gastos planejados.';
            $response = [
                'data' => [
                    'message' => $errorMessage,
                    'error' => $e->getMessage()
                ]
            ];

            return response()->json($response, 500);
        }
    }
}
