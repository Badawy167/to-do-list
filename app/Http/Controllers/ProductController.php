<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class ProductController extends Controller
{

 public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => true,
            'message' => 'successfully',
            'data' => ProductResource::collection($products), // استخدام الريسورس
        ]);
    }

    ///add
 public function store(ProductRequest $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'يجب تسجيل الدخول',
                    'en' => 'Please login'
                ]
            ], 401);
        }

        $validated = $request->validated();  ////ٌRequest
        $validated['user_id'] = $user->id;
        $product = Product::create($validated);

        return response()->json([
            'status' => true,
            'message' => [
                'ar' => 'تم بنجاح',
                'en' => 'Successfully'
            ],
            'data' => new ProductResource($product), 
        ], 201);
    }

    ///show id
public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'غير موجود',
                    'en' => 'Not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'successfully',
            'data' => new ProductResource($product), // استخدام الريسورس
        ]);
    }

    ///// search for a product
 public function search(Request $request)
    {
        $query = $request->query('q');
        $products = Product::where('name', 'LIKE', "%$query%")
                    ->orWhere('description', 'LIKE', "%$query%")
                    ->get();

        return response()->json([
            'status' => true,
            'message' => 'successfully',
            'data' => ProductResource::collection($products),
        ]);
    }


    // Update a product
   public function update(ProductRequest $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'يجب تسجيل الدخول',
                    'en' => 'Please login'
                ]
            ]);
        }

        $product = Product::where('id', $id)
                    ->where('user_id', $user->id)
                    ->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'غير موجود',
                    'en' => 'Not found'
                ]
            ]);
        }

        $validated = $request->validated();  ////ٌRequest
        $product->update($validated);

        return (new ProductResource($product))   ////ProductResource
            ->additional([
                'status' => true,
                'message' => [
                    'ar' => 'تم التعديل بنجاح',
                    'en' => 'Updated successfully'
                ]
            ]);
    }     

    // delete
 public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'يجب تسجيل الدخول',
                    'en' => 'Please login'
                ]
            ], 401);
        }

        $product = Product::where('id', $id)
                    ->where('user_id', $user->id)
                    ->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => [
                    'ar' => 'غير موجود',
                    'en' => 'Not found'
                ]
            ], 404);
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => [
                'ar' => 'تم الحذف بنجاح',
                'en' => 'Deleted successfully'
            ]
        ]);
    }
}


