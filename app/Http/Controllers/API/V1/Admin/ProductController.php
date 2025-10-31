<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
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
        // $products = Product::with('category', 'images')->latest()->get();

        $products = Product::with('category', 'images')
            ->latest()->paginate(10);

        $productsResource =
            ProductResource::collection($products);

        return apiResponse(
            200,
            'Products retrieved successfully',
            $productsResource,
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData = Product::create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            'featured' => $validatedData['featured'] ? true : false,
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
        // $productResource = new ProductResource($product);
        $productResource =
            ProductResource::make($product);
        return apiResponse(
            200,
            'Product retrieved successfully',
            $productResource
        );
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();
        $validatedData['slug'] = Str::slug($request->name ?? $product->name);

        //  حذف الصور المطلوبة فقط
        if ($request->filled('deleted_images')) {
            $imagesToDelete = $product->images()->whereIn('id', $request->deleted_images)->get();
            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }

        //  إضافة صور جديدة (إن وجدت)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products/images', 'public');

                $product->images()->create([
                    'path' => $path,
                    'is_primary' => $index === 0 && !$product->images()->where('is_primary', true)->exists(),
                ]);
            }
        }

        $product->update($validatedData);

        return apiResponse(
            200,
            'Product updated successfully',
            $product->load('images')
        );
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

        //  حذف الصور من التخزين ومن قاعدة البيانات
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        //  حذف المنتج نفسه
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product and related images deleted successfully',
        ], 200);
    }

    /**
     * Upload multiple images
     */

    public function uploadImages(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        foreach ($request->file('images') as $image) {
            $path = $image->store('products/images', 'public');
            $product->images()->create([
                'path' => $path,
                'is_primary' => false,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Images uploaded successfully',
        ], 200);
    }

    /**
     * Delete multiple images
     */
    public function deleteImages(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array',
        ]);

        foreach ($request->input('images') as $imageId) {
            $image = $product->images()->find($imageId);
            if ($image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }
        return apiResponse(
            200,
            'Images deleted successfully'
        );
    }

    /**
     * Set an image as primary
     */
    public function setPrimaryImage(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            return response()->json([
                'status' => false,
                'message' => 'Image does not belong to this product',
            ], 403);
        }

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        $productResource = $product->load('images');

        return apiResponse(
            200,
            'Primary image updated successfully',
            $productResource
        );
    }
}
