<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function showPaymentMethods(): JsonResponse
    {
        try {
            $paymentMethods = PaymentMethod::all();

            $formattedPaymentMethods = $paymentMethods->map(function ($paymentMethod) {
                return [
                    'id' => $paymentMethod->id,
                    'description' => $paymentMethod->payment_method_description
                ];
            });

            $response = [
                'data' => [
                    'payment_methods' => $formattedPaymentMethods
                ]
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = 'Nenhum método de pagamento encontrado.';
            $response = [
                'data' => [
                    'errorMessage' => $errorMessage,
                    'error' => $e->getMessage()
                ]
            ];

            return response()->json($response, 404);
        }
    }
}
