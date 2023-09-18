<?php

namespace App\Http\Controllers;

use App\Models\UserCartItems;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserCartController extends Controller
{
    public function getMyCart(Request $request):JsonResponse
    {
        return response()->json(UserCartItems::with('product')->where('user_id','=',$request->get('auth_user_id'))->get());
    }

    public function addToCart(Request $request):JsonResponse
    {
        $this->validate($request,[
            'product_id' => 'required|integer',
            'quantity' => 'required|integer'
        ]);

        try {
            UserCartItems::create([
                'user_id' => $request->get('auth_user_id'),
                'product_id' => $request->get('product_id'),
                'quantity' => $request->get('quantity')
            ]);

            return response()->json(['message' => 'Product added to cart']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ],500);
        }
    }

    public function removeFromCart(Request $request):JsonResponse
    {
        $this->validate($request,[
            'cart_item_id' => 'required|integer',
        ]);

        try {
            UserCartItems::where('id', '=', $request->get('cart_item_id'))
                ->where( 'user_id', '=', $request->get('auth_user_id'))
                ->delete();

            return response()->json(['message' => 'Product removed from cart']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ],500);
        }
    }
}
