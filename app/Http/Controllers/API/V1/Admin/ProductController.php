<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// 
use Illuminate\Support\Str;
use App\Models\ProductImage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $products = Product::with('category')->latest()->get();
        $products = Product::with('category', 'images')->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreProductRequest $request)
    {
        // $validatedData = $request->validated();
        // $validatedData['slug'] = Str::slug($validatedData['name']);

        $validatedData = $request->validated();
        $validatedData = Product::create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            // 'featured' => $validatedData['featured'] ?? false,
            'featured' => $validatedData['featured'] ? true : false,
            // 'active' => $validatedData['active'] ?? true,
            'active' => $validatedData['active']  ? true : false,
            'category_id' => $validatedData['category_id'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products/images', 'public');

                $validatedData->images()->create([
                    'path' => $path,
                    'is_primary' => $index === 0, // أول صورة تعتبر الصورة الأساسية
                ]);
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Product created successfully with images',
            'data' => $validatedData->load('images'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category', 'images');
        return response()->json([
            'status' => true,
            'message' => 'Product retrieved successfully',
            'data' => $product,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('images')) {
            if ($product->images) {
                // حذف الصور القديمة من التخزين وقاعدة البيانات
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
            }
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products/images', 'public');

                $product->images()->create([
                    'path' => $path,
                    'is_primary' => $index === 0, // أول صورة تعتبر الصورة الأساسية
                ]);
            }
        }
        // if (isset($validatedData['name'])) {
        //     $validatedData['slug'] = Str::slug($validatedData['name']);
        // }
        // $validatedData['slug'] = Str::slug($validatedData['name']) ?? $validatedData['slug'] ?: $product->slug;
        $validatedData['slug'] = Str::slug($request->name ?? $product->name);

        $product->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            // 'data' => $product,
            'data' => $product->load('images'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::with('images')->find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found',
            ], 404);
        }

        // ✅ حذف الصور من التخزين ومن قاعدة البيانات
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        // ✅ حذف المنتج نفسه
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product and related images deleted successfully',
        ], 200);
    }
}
