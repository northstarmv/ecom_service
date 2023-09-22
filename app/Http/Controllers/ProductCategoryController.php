<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationErrrorHandler;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ProductCategory;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ProductCategoryController extends Controller
{
    public function upsert_category(Request $request):JsonResponse
    {
        try{
        
            $this->validate($request, [
                'name' => 'required|string|max:300|xssPrevent|unique:product_categories',
                'id' => 'required|integer|nullable|min:1|max:2147483647',
            ]);

            if($request->get('id') != null){

                try{  
             
                    $dateTime = Carbon::now('UTC')->format('Y-m-d H:i:s');
                    ProductCategory::where('id','=',$request->get('id'))->update([
                        'name' => $request->get('name'),
                        'modified_by' => $request["auth"]["id"],
                        'modified_time' => $dateTime
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

            }else{

                try{  
             
                    $dateTime = Carbon::now('UTC')->format('Y-m-d H:i:s');
                    ProductCategory::create([
                        'name' => $request->get('name'),
                        'added_by' => $request["auth"]["id"],
                        'added_time' => $dateTime,
                        'modified_by' => null,
                        'modified_time' => $dateTime
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

            }
           
        }catch  (ValidationException  $e) {
            return response()->json(
                [
                    ResponseHelper::error( "0000", ValidationErrrorHandler::handle($e->validator->errors()) )
                ], 200
                );
        }
    }

    public function list(): JsonResponse
    {
        try {
            $categories = ProductCategory::select('id','name')->get();
            return response()->json([
                ResponseHelper::success("200", [
                    'domain' => env('AWS_URL'),
                    'result' => $categories
                ],"success")
                    
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    ResponseHelper::error("0500")
                ], 200
                );
        }
    }

    public function del_category(Request $request):JsonResponse
    {
        try{
        
            $this->validate($request, [
                'id' => 'required|integer|nullable|min:1|max:2147483647',
            ]);

            try{  
                error_log("Dsdsds");

                $category_count = Product::select('id')->where('CategoryId','=',$request->get('id'))->count();

                if($category_count > 0){
                    return response()->json(
                        [
                            ResponseHelper::error("0055")
                        ], 200
                        );
                }
                
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
