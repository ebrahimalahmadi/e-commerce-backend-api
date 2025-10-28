<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{

    public function listProductsWithImages()
    {
        // $products = Product::with('images')->latest()->get();
        $products = Product::with('images', 'category')->latest()->get();

        $response = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'active' => $product->active,
                'featured' => $product->featured,
                'category_id' => $product->category_id,
                'status' => $product->status,
                'created_at' => $product->created_at->toDateTimeString(),
                'updated_at' => $product->updated_at->toDateTimeString(),
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                    'description' => $product->category->description,
                    'image_url' => $product->category->image ? Storage::url($product->category->image) : null,
                    'created_at' => $product->category->created_at->toDateTimeString(),
                    'updated_at' => $product->category->updated_at->toDateTimeString(),
                ],
                'images' => $product->images->map(fn($img) => [
                    'id' => $img->id,
                    'product_id' => $img->product_id,
                    'url' => Storage::url($img->path),
                    'is_primary' => $img->is_primary,
                    'created_at' => $img->created_at->toDateTimeString(),
                    'updated_at' => $img->updated_at->toDateTimeString(),
                ]),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Products with images retrieved successfully',
            'data' => $response,
        ]);
    }



    /**
     * Display a listing of the resource.
     * عرض جميع الصور الخاصة بمنتج معين
     */
    public function index(Product $product)
    {
        $images = $product->images()->latest()->get()->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => Storage::url($image->path),
                'is_primary' => $image->is_primary,
                'created_at' => $image->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Product images retrieved successfully',
            'data' => $images,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     * رفع صورة واحدة أو أكثر
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|max:2048',
        ]);

        $isFirst = $product->images()->count() === 0;
        $response = [];

        foreach ($request->file('images') as $index => $file) {
            $path = $file->store('products/images', 'public');

            $image = $product->images()->create([
                'path' => $path,
                'is_primary' => $isFirst && $index === 0,
            ]);

            $response[] = [
                'id' => $image->id,
                'url' => Storage::url($image->path),
                'is_primary' => $image->is_primary,
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Images uploaded successfully',
            'data' => $response,
        ], 201);
    }


    /**
     * Display the specified resource.
     *  عرض صورة واحدة
     */
    public function show(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            return response()->json([
                'status' => false,
                'message' => 'Image does not belong to this product',
            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product image retrieved successfully',
            'data' => [
                'id' => $image->id,
                'url' => Storage::url($image->path),
                'is_primary' => $image->is_primary,
                'created_at' => $image->created_at->toDateTimeString(),
                'updated_at' => $image->updated_at->toDateTimeString(),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            return response()->json([
                'status' => false,
                'message' => 'Image does not belong to this product',
            ], 403);
        }

        $request->validate([
            'image' => 'nullable|image|max:2048',
            'is_primary' => 'nullable|boolean',
        ]);

        $updated = false;

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($image->path);
            $image->path = $request->file('image')->store('products/images', 'public');
            $updated = true;
        }

        if ($request->has('is_primary') && $request->boolean('is_primary')) {
            $product->images()->update(['is_primary' => false]);
            $image->is_primary = true;
            $updated = true;
        }

        if ($updated) {
            $image->save();
        }

        return response()->json([
            'status' => true,
            'message' => $updated ? 'Image updated successfully' : 'No changes applied',
            'data' => [
                'id' => $image->id,
                'url' => Storage::url($image->path),
                'is_primary' => $image->is_primary,
            ],
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            return response()->json([
                'status' => false,
                'message' => 'Image does not belong to this product',
            ], 403);
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully',
        ]);
    }


    /**
     * Set an image as primary
     */
    public function setPrimary(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            return response()->json([
                'status' => false,
                'message' => 'Image does not belong to this product',
            ], 403);
        }

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Primary image updated successfully',
        ]);
    }
}
