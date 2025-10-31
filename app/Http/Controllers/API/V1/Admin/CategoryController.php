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

        $categories = Category::withCount('products')
            ->latest()->paginate(10);

        $categoriesResource =
            CategoryResource::collection($categories);

        return apiResponse(
            200,
            'Categories retrieved successfully',
            $categoriesResource
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('categories', 'public');
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        $categoriesResource =
            CategoryResource::make($category);

        return apiResponse(
            201,
            'Category created successfully',
            $categoriesResource
        );
    }


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('products');

        $categoryResource =
            CategoryResource::make($category);

        return apiResponse(
            200,
            'Category retrieved successfully',
            $categoryResource
        );
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

        $categoryResource = CategoryResource::make($category);

        return apiResponse(
            200,
            'Category updated successfully',
            $categoryResource
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return apiErrorResponse(
                404,
                'Category not found'
            );
        }

        // حذف الصورة من التخزين إذا كانت موجودة
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // حذف التصنيف من قاعدة البيانات
        $category->delete();

        return apiResponse(
            200,
            'Category deleted successfully'
        );
    }
}
