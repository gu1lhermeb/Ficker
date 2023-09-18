<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Category;
use App\Models\Type;
use App\Models\Transaction;

class CategoryController extends Controller
{

    public static function storeTransaction($description, $type) :JsonResponse
    {
        try {
            $category = Category::create([

                'category_description' => $description,
                'type_id' => $type
            ]);

            $response = [
                'data' => [
                    'category' => $category
                ]
            ];
            return response()->json($response, 201);
        } catch (\Exception $e) {
            $errorMessage = "A categoria não foi criada";
            $response = [
                'Error' => [
                    'message' => $errorMessage,
                    'error' => $e
                ]
            ];

            return response()->json($response, 404);
        }
    }

    public function showTypeCategories($id): JsonResponse
    {
        try {
            $categories = Type::find($id)->categories;

            $response = [];
            foreach($categories as $category){
                array_push($response, $category);
            }

            return response()->json($response, 200);

        } catch(\Exception $e) {

            $errorMessage = "Nenhuma categoria encontrada.";
            $response = [
                "data" => [
                    "message" => $errorMessage,
                    "error" => $e
                ]
            ];

            return response()->json($response, 404);
        }
    }

    public static function showCategory($id)
    {
        try {

            $category = Category::find($id);

            $description = $category->category_description;

            return $description;

        } catch (\Exception $e) {
            $errorMessage = "Error: " . $e;
            $response = [
                "data" => [
                    "message" => $errorMessage,
                    "error" => $e
                ]
            ];

            return response()->json($response, 404);
        }
    }

    public function showCategories(): JsonResponse
    {
        try {
            $categories = Category::all();

            $response = [];
            foreach($categories as $category){
                $ammount = 0;
                foreach(Transaction::where('category_id', $category->id)->get() as $transaction){
                    $transactionMonth = date('m',strtotime($transaction->date));
                    $currentMonth = date('m');
                    if($transactionMonth === $currentMonth){
                        $ammount += $transaction->value;
                    };
                };
                $category->ammount = $ammount;
                array_push($response, $category);
            }
            return response()->json($response, 200);

        } catch (\Exception $e) {
            $errorMessage = "Nenhuma categoria foi encontrada";
            $response = [
                "data" => [
                    "message" => $errorMessage,
                    "error" => $e
                ]
            ];

            return response()->json($response, 404);
        }
    }

    public static function store(Request $request) :JsonResponse
    {
        try {
            $category = Category::create([

                'category_description' => $request->category_description,
                'type_id' => $request->type_id
            ]);

            $response = [
                'data' => [
                    'category' => $category
                ]
            ];
            return response()->json($response, 201);
        } catch (\Exception $e) {
            $errorMessage = "A categoria não foi criada";
            $response = [
                'Error' => [
                    'message' => $errorMessage,
                    'error' => $e
                ]
            ];

            return response()->json($response, 404);
        }
    }
}