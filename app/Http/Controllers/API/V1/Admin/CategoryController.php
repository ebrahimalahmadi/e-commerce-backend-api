<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
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
        $categories = Category::latest()->get();

        $formatted = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image_url' => $category->image ? Storage::url($category->image) : null,
                'created_at' => $category->created_at->toDateTimeString(),
                'updated_at' => $category->updated_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Categories retrieved successfully',
            'data' => $formatted,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category();

        // حفظ الصورة إذا تم رفعها
        if ($request->hasFile('image')) {
            $category->image = $request->file('image')->store('categories', 'public');
        }

        // حفظ البيانات الأساسية
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;

        $category->save();

        return response()->json([
            'status' => true,
            'message' => 'Category created successfully',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image_url' => $category->image ? Storage::url($category->image) : null,
                'created_at' => $category->created_at->toDateTimeString(),
            ],
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    //     // $category = Category::find(id: $id);
    //     // $category = Category::where('id', $id)->first();
    //     $category = Category::where('id', $id)->first();
    //     if (!$category) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Category not found'
    //         ], 404);
    //     }
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Category retrieved successfully',
    //         'data' => $category
    //     ]);
    // }

    // --------------------
    public function show(Category $category)
    {
        return response()->json([
            'status' => true,
            'message' => 'Category retrieved successfully',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image_url' => $category->image ? Storage::url($category->image) : null,
                'created_at' => $category->created_at->toDateTimeString(),
                'updated_at' => $category->updated_at->toDateTimeString(),
            ],
        ], 200);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // ✅ تحديث الصورة إذا تم رفعها
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إن وجدت
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            // حفظ الصورة الجديدة
            $category->image = $request->file('image')->store('categories', 'public');
        }

        // ✅ تحديث الاسم والوصف إذا تم إرسالها
        if ($request->has('name')) {
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
        }

        if ($request->has('description')) {
            $category->description = $request->description;
        }

        $category->save();

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image_url' => $category->image ? Storage::url($category->image) : null,
            ],
        ]);
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
