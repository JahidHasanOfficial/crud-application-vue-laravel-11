<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::select('id', 'name', 'created_at')->orderBy('created_at', 'desc')->get();
    
            if ($categories->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No categories found',
                    'data' => []
                ]);
            }
    
            return response()->json([
                'status' => true,
                'message' => 'Data fetched successfully',
                'data' => $categories
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
    
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
          ]);

          if ($validation->fails()) {
            return response()->json([
              'status' => false,
              'message' => 'Validation failed',
              'errors' => $validation->errors()
            ]);
          }

          $category = new Category();
          $category->name = $request->name;
          $category->slug = Str::slug($request->name, '_'). '_'. Str::random(50);
          $category->save();

          return response()->json([
            'status' => true,
            'message' => 'Data created successfully',
            'data' => $category
          ]);
        } catch (Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage());
    
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
                'id' => 'required|exists:categories,id',
                'name' => 'required|string',
            ]);
    
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->errors()
                ]);
            }
    
            $category = Category::findOrFail($request->id);
            $category->name = $request->name;
            $category->slug = Str::slug($request->name, '_'). '_'. Str::random(50);
            $category->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Data updated successfully',
                'data' => $category
            ]);
        } catch (Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
    
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        try{
            $validation = Validator::make($request->all(), [
                'id' => 'required|exists:categories,id',
            ]);
    
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->errors()
                ]);
            }
    
            $category = Category::findOrFail($request->id);
            $category->delete();
    
            return response()->json([
                'status' => true,
                'message' => 'Data deleted successfully',
                'data' => $category
            ]);
        } catch (Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
    
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ]);
        }
    }
            
    
}
