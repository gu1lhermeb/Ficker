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
                'planned_spending' => $request->planned_spending,
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
    public function destroy(Request $request): JsonResponse
    {
        try {
            Spending::find($request->id)->delete();

            $response = [
                'data' => [
                    'message' => 'Gasto planejado deletado com sucesso.'
                ]
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = 'Erro ao deletar os gastos planejados.';
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

                $spendingByDay = Transaction::where('user_id', Auth::user()->id)
                ->selectRaw('MONTH(date) as month, DAY(date) as day, 
                            SUM(CASE WHEN type_id = 1 THEN transaction_value ELSE 0 END) as incomes,
                            SUM(CASE WHEN type_id != 1 THEN transaction_value ELSE 0 END) as spendings')
                ->groupBy('day')
                ->groupBy('month')
                ->get();

                $response = [
                    'data' => [
                        $spendingByDay,
                    ]
                ];

            } elseif ($request->query('sort') == 'month') {

                $spendingsByMonth = Transaction::where('user_id', Auth::user()->id)
                    ->selectRaw('MONTH(date) as month, YEAR(date) as year,
                                SUM(CASE WHEN type_id = 1 THEN transaction_value ELSE 0 END) as incomes,
                                SUM(CASE WHEN type_id != 1 THEN transaction_value ELSE 0 END) as real_spending')
                    ->groupBy('year')
                    ->groupBy('month')
                    ->get();

                $planned_spendings = Spending::where('user_id', Auth::user()->id)
                    ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, planned_spending')
                    ->groupBy('year')
                    ->groupBy('month')
                    ->get();
                

                for($i = 0; $i < count($spendingsByMonth); $i++) {
                    $spendingsByMonth[$i]->planned_spending = $planned_spendings[$i]->planned_spending;
                }

                $response = [
                    'data' => [
                        $spendingsByMonth,
                    ]
                ];
            } elseif ($request->query('sort') == 'year') {

                $spendingByYear = Transaction::where('user_id', Auth::user()->id)
                ->selectRaw('YEAR(date) as year, 
                            SUM(CASE WHEN type_id = 1 THEN transaction_value ELSE 0 END) as incomes,
                            SUM(CASE WHEN type_id != 1 THEN transaction_value ELSE 0 END) as spendings')
                ->groupBy('year')
                ->get();

                $response = [
                    'data' => $spendingByYear
                ];

            } else {

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
