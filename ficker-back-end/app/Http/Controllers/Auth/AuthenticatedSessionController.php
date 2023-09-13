<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)){

            $message = 'Login Success';
            $user = $request->user();
            $token = $user->createToken('login_token')->plainTextToken;
            $response = [
                "message" => $message,
                "data" => [
                    "token" => $token,
                    "token_type" => 'Bearer'
                ]
            ];

            return response()->json($response, 200);
        } else {
            $message = 'Error';
            $response = [
                "message" => $message,
            ];
            return response()->json($response, 404);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $user = $request->user();
        $token = $user->createToken('Auth_token')->plainTextToken;

        $response = [
            "data" => [
                "token" => $token,
                "token_type" => 'Bearer'
            ]
        ];

        return response()->json($response, 200);
    }
}
