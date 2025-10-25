<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
// 
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     //
    // }
    public function index()
    {
        //
        $products = Product::all();
        return response()->json([
            'status' => true,
            'message' => 'Products fetched successfully',
            'data' => $products,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    // }
    public function store(StoreProductRequest $request)
    {
        //
        $validateddata = $request->validated();
        $validateddata['slug'] = Str::slug($validateddata['name']);
        // dd($validateddata);
        $product = Product::create($validateddata);
        // return response()->json([
        //     'status' => true,
        //     'message' => 'Product created successfully',
        //     'data' => $product,
        // ], 201);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully',
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'featured' => $product->featured,
                'active' => $product->active,
                'category_id' => $product->category_id,
                // 'image_url' => $product->image ? Storage::url($product->image) : null,
                // 'created_at' => $product->created_at->toDateTimeString(),
                'created_at' => $product->created_at->format('Y-m-d'),
                // 'updated_at' => $product->updated_at->toDateTimeString(),
                'updated_at' => $product->updated_at->format('Y-m-d'),
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }
    public function show(string $id)
    {
        //
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Product fetched successfully',
            'data' => $product,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }
    // public function update(UpdateProductRequest $request, string $id)
    // {
    //     //
    //     $product = Product::find($id);
    //     if (!$product) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Product not found',
    //         ], 404);
    //     }

    //     $validatedData = $request->validated();
    //     $validatedData['slug'] = Str::slug($validatedData['name']);
    //     // dd($validatedData);

    //     $product->update($validatedData);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product updated successfully',
    //         'data' => $product,
    //     ], 200);
    // }
    // -----------------
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
    // // -----------------
    // public function update(UpdateProductRequest $request, Product $product)
    // {
    //     $validatedData = $request->validated();

    //     // توليد slug فقط إذا تم إرسال name
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
    // -----------------
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();

        // توليد slug فقط إذا تم إرسال name
        if (isset($validatedData['name'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }

        $product->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $product,
        ], 200);
    }



    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     //
    // }
    public function destroy(string $id)
    {
        //
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found',
            ], 404);
        }
        $product->delete();
        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully',
        ], 200);
    }
}
