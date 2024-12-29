<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // public function index()
    // {
    //     try {
    //         $products = Product::orderBy('created_at', 'desc')->get();
    
    //         if ($products->isEmpty()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'No products found',
    //                 'data' => []
    //             ]);
    //         }
    
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Data fetched successfully',
    //             'data' => $products
    //         ]);
    //     } catch (Exception $e) {
    //         Log::error('Error fetching products: ' . $e->getMessage());
    
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Something went wrong',
    //             'error' => $e->getMessage()
    //         ]);
    //     }
    // }

    public function index()
{
    try {
        // Eager load the category with products
        $products = Product::with('category')->orderBy('created_at', 'desc')->get();

        if ($products->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No products found',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data fetched successfully',
            'data' => $products
        ]);
    } catch (Exception $e) {
        Log::error('Error fetching products: ' . $e->getMessage());

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ]);
    }
}


    public function store(Request $request)
    {
        try {
          $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            // 'price' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
          ]);

          if ($validation->fails()) {
            return response()->json([
              'status' => false,
              'message' => 'Validation failed',
              'errors' => $validation->errors()
            ]);
          }

          $Product = new Product();
          $Product->name = $request->name;
          $Product->slug = Str::slug($request->name, '_'). '_'. Str::random(50);
          $Product->price = $request->price;
          $Product->category_id = $request->category_id;
          $Product->description = $request->description;
          if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $Product->image = $imagePath;
        }
          $Product->save();

          return response()->json([
            'status' => true,
            'message' => 'Data created successfully',
            'data' => $Product
          ]);
        } catch (Exception $e) {
            Log::error('Error creating Product: ' . $e->getMessage());
    
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ]);
        }
    }


    public function update(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'id' => 'required|exists:products,id',
                'name' => 'required|string',
                // 'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->errors()
                ]);
            }
    
            $Product = Product::findOrFail($request->id);
            $Product->name = $request->name;
            $Product->slug = Str::slug($request->name, '_'). '_'. Str::random(50);
            // $Product->price = $request->price;
            $Product->category_id = $request->category_id;
            $Product->description = $request->description;
            if ($request->hasFile('image')) {
                $oldImagePath = $Product->image;
    
                if ($oldImagePath) {
                    $relativePath = str_replace(url('storage/') . '/', '', $oldImagePath);
                    $fullPath = storage_path('app/public/' . $relativePath);
    
                    // Check if the file exists and delete it
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
    
                $imagePath = $request->file('image')->store('products', 'public');
                $Product->image = $imagePath;
            }
            $Product->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Data updated successfully',
                'data' => $Product
            ]);
        } catch (Exception $e) {
            Log::error('Error updating Product: ' . $e->getMessage());
    
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'id' => 'required|exists:products,id',
            ]);
    
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->errors()
                ]);
            }
    
            $Product = Product::findOrFail($request->id);
            
            // Store the image path before deletion to show in the response
            $imagePathBeforeDelete = $Product->image;
    
            // Check if the image exists before attempting to delete it
            if ($imagePathBeforeDelete) {
                $relativePath = str_replace(url('storage/') . '/', '', $imagePathBeforeDelete);
                $fullPath = storage_path('app/public/' . $relativePath);
    
                // Check if the file exists and delete it
                if (file_exists($fullPath)) {
                    unlink($fullPath);  // Delete the image file from storage
                }
            }
    
            // Delete the product record
            $Product->delete();
    
            return response()->json([
                'status' => true,
                'message' => 'Data deleted successfully',
                'image_path' => $imagePathBeforeDelete, // Include the image path in the response
                'data' => $Product
            ]);
        } catch (Exception $e) {
            Log::error('Error deleting Product: ' . $e->getMessage());
    
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ]);
        }
    }
    
    
}
