<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{


    // ------------------------------------
    // public function listProductsWithImages()
    // {
    //     $products = Product::with('images')->latest()->get();
    //     $response = [];

    //     foreach ($products as $product) {
    //         $images = [];

    //         foreach ($product->images as $image) {
    //             $images[] = [
    //                 'id' => $image->id,
    //                 'url' => Storage::url($image->path),
    //                 'is_primary' => $image->is_primary,
    //             ];
    //         }

    //         $response[] = [
    //             'id' => $product->id,
    //             'name' => $product->name,
    //             'slug' => $product->slug,
    //             'price' => $product->price,
    //             'images' => $images,
    //             'created_at' => $product->created_at->toDateTimeString(),
    //         ];
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Products with images retrieved successfully',
    //         'data' => $response,
    //     ]);
    // }
    // ------------------------------------
    public function listProductsWithImages()
    {
        $products = Product::with('images')->latest()->get();
        $response = [];

        foreach ($products as $product) {
            $images = [];

            foreach ($product->images as $image) {
                $images[] = [
                    'id' => $image->id,
                    'url' => Storage::url($image->path),
                    'is_primary' => $image->is_primary,
                ];
            }

            $response[] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'images' => $images,
                'created_at' => $product->created_at->toDateTimeString(),
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Products with images retrieved successfully',
            'data' => $response,
        ]);
    }




    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     //
    // }
    // //--------
    // // 📸 1. عرض كل الصور المرتبطة بمنتج
    // public function index(Product $product)
    // {
    //     $images = $product->images()->latest()->get();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product images retrieved successfully',
    //         'data' => $images->map(fn($img) => [
    //             'id' => $img->id,
    //             'url' => Storage::url($img->path),
    //             'is_primary' => $img->is_primary,
    //             'created_at' => $img->created_at->toDateTimeString(),
    //         ]),
    //     ]);
    // }

    //--------
    // 📸 1. عرض كل الصور المرتبطة بمنتج
    // public function index()
    // {
    //     $product = Product::with('images');

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product images retrieved successfully',
    //         'data' => $product->get(),
    //     ]);
    // }
    // public function index()
    // {
    //     $product = Product::with('images')->latest()->get();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product images retrieved successfully',
    //         'data' => $product,
    //     ]);
    // }
    // public function index()
    // {
    //     // $product = Product::with('images')->latest()->get();
    //     $images = ProductImage::with('product')->latest()->get();
    //     $product = $images->map(fn($img) => [
    //         'id' => $img->id,
    //         'url' => Storage::url($img->path),
    //         'is_primary' => $img->is_primary,
    //         'created_at' => $img->created_at->toDateTimeString(),
    //         'product' => [
    //             'id' => $img->product->id,
    //             'name' => $img->product->name,
    //         ],
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product images retrieved successfully',
    //         'data' => $product,
    //     ]);
    // }
    // public function index()
    // {
    //     $images = ProductImage::with('product')->latest()->get();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product images retrieved successfully',
    //         'data' => $images,
    //     ]);
    // }
    // ------------------------------------------
    // public function index()
    // {
    //     $product = Product::with('images')->latest()->first();
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product images retrieved successfully',
    //         'data' => $product,
    //     ]);
    // }
    // ------------------------------------------
    // public function index(Product $product)
    // {
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product images retrieved successfully',
    //         'data' => $product->images->map(fn($img) => [
    //             'id' => $img->id,
    //             'url' => Storage::url($img->path),
    //             'is_primary' => $img->is_primary,
    //             'created_at' => $img->created_at->toDateTimeString(),
    //         ]),
    //     ]);
    // }
    // ------------------------------------------
    // عرض كل الصور المرتبطة بمنتج
    public function index(Product $product)
    {
        $images = $product->images()->latest()->get();
        $response = [];

        foreach ($images as $image) {
            $response[] = [
                'id' => $image->id,
                'url' => Storage::url($image->path),
                'is_primary' => $image->is_primary,
                'created_at' => $image->created_at->toDateTimeString(),
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Product images retrieved successfully',
            'data' => $response,
        ]);
    }





    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    // }
    // -------
    // 🆕 2. رفع صورة جديدة
    // public function store(Request $request, Product $product)
    // {
    //     $request->validate([
    //         'image' => 'required|image|max:2048',
    //     ]);

    //     $path = $request->file('image')->store('products/images', 'public');

    //     $image = $product->images()->create([
    //         'path' => $path,
    //         'is_primary' => $product->images()->count() === 0,
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Image uploaded successfully',
    //         'data' => [
    //             'id' => $image->id,
    //             'url' => Storage::url($image->path),
    //             'is_primary' => $image->is_primary,
    //         ],
    //     ], 201);
    // }
    // --------------------------------------------------------
    // رفع صورة واحدة أو أكثر
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
     */
    // public function show(string $id)
    // {
    //     //
    // }
    // ------------------------------------------------
    // عرض صورة واحدة
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
    // public function update(Request $request, string $id)
    // {
    //     //
    // }
    // // ------------------------------------------------

    // // تعديل صورة (تغيير الملف أو تعيينها كرئيسية)
    // public function update(Request $request, Product $product, ProductImage $image)
    // {
    //     if ($image->product_id !== $product->id) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Image does not belong to this product',
    //         ], 403);
    //     }

    //     $request->validate([
    //         'image' => 'nullable|image|max:2048',
    //         'is_primary' => 'nullable|boolean',
    //     ]);

    //     if ($request->hasFile('image')) {
    //         Storage::disk('public')->delete($image->path);
    //         $image->path = $request->file('image')->store('products/images', 'public');
    //     }

    //     if ($request->boolean('is_primary')) {
    //         $product->images()->update(['is_primary' => false]);
    //         $image->is_primary = true;
    //     }

    //     $image->save();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Image updated successfully',
    //         'data' => [
    //             'id' => $image->id,
    //             'url' => Storage::url($image->path),
    //             'is_primary' => $image->is_primary,
    //         ],
    //     ]);
    // }
    // // ------------------------------------------------
    // public function update(Request $request, Product $product, ProductImage $image)
    // {
    //     if ($image->product_id !== $product->id) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Image does not belong to this product',
    //         ], 403);
    //     }

    //     $request->validate([
    //         'image' => 'nullable|image|max:2048',
    //         'is_primary' => 'nullable|boolean',
    //     ]);

    //     $updated = false;

    //     if ($request->hasFile('image')) {
    //         Storage::disk('public')->delete($image->path);
    //         $image->path = $request->file('image')->store('products/images', 'public');
    //         $updated = true;
    //     }

    //     if ($request->has('is_primary') && $request->boolean('is_primary')) {
    //         $product->images()->update(['is_primary' => false]);
    //         $image->is_primary = true;
    //         $updated = true;
    //     }

    //     if ($updated) {
    //         $image->save();
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => $updated ? 'Image updated successfully' : 'No changes applied',
    //         'data' => [
    //             'id' => $image->id,
    //             'url' => Storage::url($image->path),
    //             'is_primary' => $image->is_primary,
    //         ],
    //     ]);
    // }

    // ------------------------------------------------
    // ---------this is very good version -------------
    // ------------------------------------------------
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
    // public function destroy(string $id)
    // {
    //     //
    // }
    // ------------------------------------------------

    // حذف صورة
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


    // ------------------------------------------------
    // تعيين صورة كصورة رئيسية
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
