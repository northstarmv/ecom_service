<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationErrrorHandler;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function searchProducts(Request $request): JsonResponse
    {
        if ($request->get('search_key') == 'ALL') {
            return response()->json(Product::all());
        } else {
            return response()->json(Product::join('product_categories', 'products.CategoryId', '=', 'product_categories.id')
                ->select('products.*', 'product_categories.name as category_name')->where('products.name', 'like', '%' . $request->get('search_key') . '%')->get());
        }
    }

    public function addProduct(Request $request): JsonResponse
    {

        
        try{
        
            $this->validate($request, [
                'name' => 'required|string|max:255|xssPrevent',
                'description' => 'required|string|xssPrevent',
                'image_path' => 'required|string',
                'code' => 'required|string|max:255|xssPrevent',
                'price' => 'required|numeric',
                'quantity' => 'required|integer',
                'category_id' => 'required|integer|nullable|min:1|max:2147483647',
            ]);

                try{  
             
                    Product::create([
                        'name' => $request->get('name'),
                        'categoryId' => $request->get('category_id'),
                        'description' => $request->get('description'),
                        'code' => $request->get('code'),
                        'image_path' => $request->get('image_path'),
                        'price' => $request->get('price'),
                        'quantity' => $request->get('quantity'),
                    ]);
            
                    return response()->json([
                        ResponseHelper::success("200","","Success")
                    ], 200);
    
                }catch  (\Exception $e) {
                    error_log($e);
                    return response()->json(
                        [
                            ResponseHelper::error("0000", $e)
                        ], 200
                        );
    
                }
           
        }catch  (ValidationException  $e) {
            return response()->json(
                [
                    ResponseHelper::error( "0000", ValidationErrrorHandler::handle($e->validator->errors()) )
                ], 200
                );
        }
        
    }

    public function editProduct(Request $request): JsonResponse
    {

        
        try{
        
            $this->validate($request, [
                'name' => 'required|string|max:255|xssPrevent',
                'description' => 'required|string|xssPrevent',
                'image_path' => 'required|string',
                'code' => 'required|string|max:255|xssPrevent',
                'price' => 'required|numeric',
                'quantity' => 'required|integer',
                'CategoryId' => 'required|integer|nullable|min:1|max:2147483647',
                'id' => 'required|integer|nullable|min:1|max:2147483647',
            ]);

                try{  
                    
                    $checkCategory = ProductCategory::select('id')
                    ->where('id', $request->get('CategoryId'))
                    ->get()->first();
        
                    if(!$checkCategory){
                        
                        return response()->json(
                            [
                                ResponseHelper::error("0045")
                            ], 200
                            );

                    }

                    Product::where('id','=',$request->get('id'))->update([
                        'name' => $request->get('name'),
                        'categoryId' => $request->get('CategoryId'),
                        'description' => $request->get('description'),
                        'code' => $request->get('code'),
                        'image_path' => $request->get('image_path'),
                        'price' => $request->get('price'),
                        'quantity' => $request->get('quantity'),
                    ]);
            
                    return response()->json([
                        ResponseHelper::success("200","","Success")
                    ], 200);
    
                }catch  (\Exception $e) {
                    error_log($e);
                    return response()->json(
                        [
                            ResponseHelper::error("0000", $e)
                        ], 200
                        );
    
                }
           
        }catch  (ValidationException  $e) {
            return response()->json(
                [
                    ResponseHelper::error( "0000", ValidationErrrorHandler::handle($e->validator->errors()) )
                ], 200
                );
        }
        
    }

    public function del_product(Request $request):JsonResponse
    {
        try{
        
            $this->validate($request, [
                'id' => 'required|integer|nullable|min:1|max:2147483647',
            ]);

            try{
                
                $product = ProductCategory::find($request->get('id'))->delete();
        
                return response()->json([
                    ResponseHelper::success("200","","Success")
                ], 200);

            }catch  (\Exception $e) {
                error_log($e);
                return response()->json(
                    [
                        ResponseHelper::error("0000", $e)
                    ], 200
                    );

            }
           
        }catch  (ValidationException  $e) {
            return response()->json(
                [
                    ResponseHelper::error( "0000", ValidationErrrorHandler::handle($e->validator->errors()) )
                ], 200
                );
        }
    }
}
