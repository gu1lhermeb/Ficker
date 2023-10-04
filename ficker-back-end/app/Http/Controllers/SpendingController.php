<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Spending;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function spendingByYear(): JsonResponse
    {   
        try {
            $anosDistintos = Spending::select(DB::raw('YEAR(created_at) as ano'))
            ->where('user_id', Auth::user()->id)
            ->groupBy('ano')
            ->get();

            $expensesByYear = [];

            foreach ($anosDistintos as $ano) {
                $totalGasto = Spending::where('user_id', Auth::user()->id)
                    ->whereYear('created_at', $ano->ano)
                    ->sum('planned_spending');

                $expensesByYear[$ano->ano] = $totalGasto;
            }

            // $response = [
            //     'expensesByYear' => $expensesByYear,
            // ];

            return response()->json($expensesByYear, 200);
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

            $mes = now()->subMonths(12);

            $mesesDistintos = Spending::select(DB::raw('MONTH(created_at) as mes'))
                ->where('user_id', Auth::user()->id)
                ->where('created_at', '>=', $mes)
                ->groupBy('mes')
                ->get();

            $expensesByMonth = [];

            foreach ($mesesDistintos as $mes) {
                $totalGasto = Spending::where('user_id', Auth::user()->id)
                    ->whereMonth('created_at', $mes->mes)
                    ->sum('planned_spending');

                $expensesByMonth[$mes->mes] = $totalGasto;
            }

            return response()->json($expensesByMonth, 200);
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

    public function spendingByDay(): JsonResponse
    {
        try{
            
            $dias = Carbon::now()->subDays(31);

            $diasDistintos = Spending::select(DB::raw('DAY(created_at) as dia'))
                ->where('user_id', Auth::user()->id)
                ->where('created_at', '>=', $dias) 
                ->groupBy('dia')
                ->get();

            $expensesByDay = [];

            foreach ($diasDistintos as $dia) {
                $totalGasto = Spending::where('user_id', Auth::user()->id)
                    ->whereDay('created_at', $dia->dia)
                    ->sum('planned_spending');

                $expensesByDay[$dia->dia] = $totalGasto;
            }

            return response()->json($expensesByDay, 200);
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
