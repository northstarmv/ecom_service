<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function searchProducts(Request $request): JsonResponse
    {
        if ($request->get('search_key') == 'ALL') {
            return response()->json(Product::all());
        } else {
            return response()->json(Product::where('name', 'like', '%' . $request->get('search_key') . '%')->get());
        }
    }

    public function addProduct(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'required|string',
            'code' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        Product::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'code' => $request->get('code'),
            'image_path' => $request->get('image_path'),
            'price' => $request->get('price'),
            'quantity' => $request->get('quantity'),
        ]);
        return response()->json([
            'message' => 'Product added successfully',
        ]);
    }

}
