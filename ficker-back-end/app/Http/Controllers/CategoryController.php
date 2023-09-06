<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Category;
use App\Models\Type;

class CategoryController extends Controller
{

    public static function store($description, $type)
    {
        $category = Category::create([

            'category_description' => $description,
            'type_id' => $type
        ]);

        return $category;
    }

    public function showCategories($id): JsonResponse
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
                    "error" => $errorMessage
                ]
            ];

            return response()->json($response, 404);
        }
    }
}