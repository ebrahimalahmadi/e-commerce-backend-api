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
    // ---------------------------------------------
    // In the index function 
    // I used the following
    // Laravel map() with Eloquent Collection
    // ---------------------------------------------


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->get();

        $formatted = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => number_format($product->price, 2),
                'stock' => $product->stock,
                'featured' => $product->featured,
                'active' => $product->active,
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ],
                'created_at' => $product->created_at->toDateTimeString(),
                'updated_at' => $product->updated_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Products retrieved successfully',
            'data' => $formatted,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StoreProductRequest $request)
    // {
    //     $validateddata = $request->validated();
    //     $validateddata['slug'] = Str::slug($validateddata['name']);
    //     // dd($validateddata);
    //     $product = Product::create($validateddata);
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product created successfully',
    //         'data' => [
    //             'id' => $product->id,
    //             'name' => $product->name,
    //             'slug' => $product->slug,
    //             'description' => $product->description,
    //             'price' => $product->price,
    //             'stock' => $product->stock,
    //             'featured' => $product->featured,
    //             'active' => $product->active,
    //             'category_id' => $product->category_id,
    //             // 'image_url' => $product->image ? Storage::url($product->image) : null,
    //             'image_url' => asset('storage/' . $product->image) ?? null,
    //             'created_at' => $product->created_at->format('Y-m-d'),
    //             'updated_at' => $product->updated_at->format('Y-m-d'),
    //         ],
    //     ], 201);
    // }
    //   --------------------------------------------------
    // public function store(StoreProductRequest $request)
    // {
    //     $validateddata = $request->validated();
    //     $validateddata['slug'] = Str::slug($validateddata['name']);
    //     // dd($validateddata);

    //     $product = Product::create($validateddata);
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product created successfully',
    //         'data' => [
    //             'id' => $product->id,
    //             'name' => $product->name,
    //             'slug' => $product->slug,
    //             'description' => $product->description,
    //             'price' => $product->price,
    //             'stock' => $product->stock,
    //             'featured' => $product->featured,
    //             'active' => $product->active,
    //             'category_id' => $product->category_id,
    //             // 'image_url' => $product->image ? Storage::url($product->image) : null,
    //             'image_url' => asset('storage/' . $product->image) ?? null,
    //             'created_at' => $product->created_at->format('Y-m-d'),
    //             'updated_at' => $product->updated_at->format('Y-m-d'),
    //         ],
    //     ], 201);
    // }
    // ------------------------------------------------
    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['slug'] = Str::slug($validatedData['name']);

        // إنشاء المنتج أولاً
        $product = Product::create($validatedData);

        // تحقق من وجود صور
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products/images', 'public');

                $product->images()->create([
                    'path' => $path,
                    'is_primary' => $index === 0, // أول صورة تعتبر الصورة الأساسية
                ]);
            }
        }

        // تجهيز البيانات النهائية للرد
        $responseData = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'featured' => $product->featured,
            'active' => $product->active,
            'category_id' => $product->category_id,
            'images' => $product->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    // 'url' => Storage::url($image->path),
                    // 'url' => Storage::url($image->path),
                    'url' => asset('storage/' . $image->path) ?? null,
                    'is_primary' => $image->is_primary,
                ];
            }),
            'created_at' => $product->created_at->format('Y-m-d'),
            'updated_at' => $product->updated_at->format('Y-m-d'),
        ];

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully with images',
            'data' => $responseData,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    // public function show()
    // {
    //     // $product = Product::with('category')->latest()->first();
    //     $product = Product::with('category')->latest()->find(request()->route('product'));
    //     if (!$product) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Product not found',
    //         ], 404);
    //     }
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product retrieved successfully',
    //         'data' => [
    //             'id' => $product->id,
    //             'name' => $product->name,
    //             'slug' => $product->slug,
    //             'description' => $product->description,
    //             'price' => number_format($product->price, 2),
    //             'stock' => $product->stock,
    //             'featured' => $product->featured,
    //             'active' => $product->active,
    //             'category' => [
    //                 'id' => $product->category->id,
    //                 'name' => $product->category->name,
    //             ],
    //             // 'image_url' => $product->image ? Storage::url($product->image) : null,
    //             'created_at' => $product->created_at->toDateTimeString(),
    //             'updated_at' => $product->updated_at->toDateTimeString(),
    //         ],
    //     ], 200);
    // }
    public function show()
    {
        // $product = Product::with('category')->latest()->first();
        $product = Product::with('category')->latest()->find(request()->route('product'));
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Product retrieved successfully',
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => number_format($product->price, 2),
                'stock' => $product->stock,
                'active' => $product->active,
                'featured' => $product->featured,
                'category_id' => $product->category_id,
                'image_url' => asset('storage/' . $product->image) ?? null,
                'created_at' => $product->created_at->format('Y-m-d'),
                'updated_at' => $product->updated_at->format('Y-m-d'),
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                    'description' => $product->category->description,
                    // 'image_url' => $product->category->image ? Storage::url($product->category->image) : null,
                    'image_url' => asset('storage/' . $product->category->image) ?? null,
                    'created_at' => $product->category->created_at->toDateTimeString(),
                    'updated_at' => $product->category->updated_at->toDateTimeString(),

                ],
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */

    // --------------------------------------------------------------------------------------------
    // this function uses the product ID from the route parameter to find and update the product.
    // --------------------------------------------------------------------------------------------

    // public function update(UpdateProductRequest $request, string $id)
    // {
    //     $product = Product::find($id);

    //     if (!$product) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Product not found',
    //         ], 404);
    //     }

    //     $validatedData = $request->validated();

    //     // فقط إذا تم إرسال الاسم، نولّد slug جديد
    //     if (isset($validatedData['name'])) {
    //         $validatedData['slug'] = Str::slug($validatedData['name']);
    //     }

    //     $product->update($validatedData);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product updated successfully',
    //         'data' => $product->fresh(),
    //     ], 200);
    // }
    // // --------------------------------------------------
    // public function update(UpdateProductRequest $request, string $id)
    // {
    //     $product = Product::find($id);

    //     if (!$product) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Product not found',
    //         ], 404);
    //     }

    //     $validatedData = $request->validated();

    //     // فقط إذا تم إرسال الاسم، نولّد slug جديد
    //     if (isset($validatedData['name'])) {
    //         $validatedData['slug'] = Str::slug($validatedData['name']);
    //     }

    //     $product->update($validatedData);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product updated successfully',
    //         'data' => $product->fresh(),
    //     ], 200);
    // }
    // --------------------------------------------------

    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $validatedData = $request->validated();

        // إذا تم إرسال الاسم، نولّد slug جديد
        if (isset($validatedData['name'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }

        // تحديث بيانات المنتج الأساسية
        $product->update($validatedData);

        // ==========================
        // 🖼️ إدارة الصور (إضافة / حذف)
        // ==========================

        // ✅ حذف الصور المطلوبة
        if ($request->filled('remove_image_ids')) {
            $idsToRemove = is_array($request->remove_image_ids)
                ? $request->remove_image_ids
                : explode(',', $request->remove_image_ids);

            $imagesToDelete = ProductImage::whereIn('id', $idsToRemove)
                ->where('product_id', $product->id)
                ->get();

            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }

        // ✅ رفع صور جديدة (لو أرسل)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products/images', 'public');
                $product->images()->create([
                    'path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        // ✅ تعيين صورة رئيسية (اختياري)
        if ($request->filled('primary_image_id')) {
            $primaryId = $request->primary_image_id;

            $image = ProductImage::where('product_id', $product->id)
                ->where('id', $primaryId)
                ->first();

            if ($image) {
                $product->images()->update(['is_primary' => false]);
                $image->update(['is_primary' => true]);
            }
        }

        // ==========================
        // 🧾 تجهيز الاستجابة النهائية
        // ==========================
        $product->load('images');

        $responseData = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'featured' => $product->featured,
            'active' => $product->active,
            'category_id' => $product->category_id,
            'images' => $product->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => Storage::url($image->path),
                    'is_primary' => $image->is_primary,
                ];
            }),
            'created_at' => $product->created_at->format('Y-m-d'),
            'updated_at' => $product->updated_at->format('Y-m-d'),
        ];

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $responseData,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     //
    //     $product = Product::find($id);
    //     if (!$product) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Product not found',
    //         ], 404);
    //     }
    //     $product->delete();
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product deleted successfully',
    //     ], 200);
    // }
    // --------------------------------------------------
    // public function destroy(string $id)
    // {
    //     //
    //     $product = Product::find($id);
    //     if (!$product) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Product not found',
    //         ], 404);
    //     }
    //     $product->delete();
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product deleted successfully',
    //     ], 200);
    // }
    // --------------------------------------------------
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
