<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Spending;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

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
                ->first('planned_spending');

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

    public function spendings(Request $request): JsonResponse
    {
        try {
            if ($request->query('sort') == 'day') {

                $spendingByDay = Transaction::whereMonth('date', now()->month)
                    ->whereYear('date', now()->year)
                    ->whereDay('date', '<=', now()->day)
                    ->where('user_id', Auth::user()->id)
                    ->where('type_id', 2)
                    ->get();

                $response = [];

                foreach ($spendingByDay as $spending) {
                    $day = date('d', strtotime($spending->date));
                    $month = date('m', strtotime($spending->date));

                    $spending->day = $day;
                    $spending->month = $month;
                    $spendingFormatted = [
                        'data' => [
                            'day' => $day,
                            'month' => $month,
                            'ammount' => $spending->transaction_value
                        ]
                    ];
                    array_push($response, $spendingFormatted);
                }
            } elseif ($request->query('sort') == 'month') {
                $spendingByMonth = Transaction::where('user_id', Auth::user()->id)
                    ->where('type_id', 2)
                    ->selectRaw('MONTH(date) as month, SUM(transaction_value) as total')
                    ->groupBy('month')
                    ->get();

                $response = [
                    'data' => $spendingByMonth
                ];
            } else {

                $spendingByYear = Transaction::where('user_id', Auth::user()->id)
                    ->where('type_id', 2)
                    ->selectRaw('YEAR(date) as year, SUM(transaction_value) as total')
                    ->groupBy('year')
                    ->get();

                $response = [
                    'data' => $spendingByYear
                ];
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = "Erro: Nenhuma entrada foi encontrada.";
            $response = [
                "data" => [
                    "message" => $errorMessage,
                    "error" => $e->getMessage()
                ]
            ];
            return response()->json($response, 500);
        }
    }
}
