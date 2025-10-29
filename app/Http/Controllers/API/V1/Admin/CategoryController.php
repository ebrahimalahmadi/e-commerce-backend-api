<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// 
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $categories = Category::withCount('products')->latest()->get();
        // $categories = Category::withCount('products')->latest()->paginate(10);

        // $categories = Category::withCount('products')->latest()->paginate(10);
        // $categories = Category::withCount('products')->latest()->paginate(10); // 10 per page
        // $categories = Category::withCount('products')->latest()->get();
        $categories = Category::withCount('products')->latest()->paginate(10); // 10 per page



        $categoriesResource = CategoryResource::collection($categories);

        return apiResponse(200, 'Categories retrieved successfully', $categoriesResource);

        // return apiResponse(200, 'Categories retrieved successfully', $categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $imagePath,
        ]);
        // return response()->json([
        //     'status' => true,
        //     'message' => 'Category created successfully',
        //     'data' => $category
        // ], 201);
        // return apiResponse(201, 'Category created successfully', $category);
        return apiResponse(201, 'Category created successfully', $category);
    }


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('products');
        return response()->json([
            'status' => true,
            'message' => 'Category retrieved successfully',
            'data' => $category
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        // تحقق مما إذا تم تحميل صورة جديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            // حفظ الصورة الجديدة
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        // $data['slug'] = Str::slug($request->name); or keep the old slug if name not changed
        $data['slug'] = Str::slug($request->name) ?? $category->slug;

        $category->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully',
            'data' => $category,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // حذف الصورة من التخزين إذا كانت موجودة
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // حذف التصنيف من قاعدة البيانات
        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully',
        ], 200);
    }
}
