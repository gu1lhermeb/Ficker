<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Installment;


class InstallmentController extends Controller
{
    public function showInstallments($id): JsonResponse
    {
        try {

            $installments = Installment::where([
                'transaction_id' => $id
            ])->get();

            $response = [];
            foreach($installments  as $installment){
                array_push($response, $installment);
            }

            return response()->json($response, 200);

        } catch(\Exception $e) {

            $errorMessage = "Erro: Esta transação não possui parcelas.";
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
