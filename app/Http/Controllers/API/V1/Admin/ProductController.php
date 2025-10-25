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

    // -------------------
    // Laravel map() Example with Eloquent Collection
    // -------------------
    // for  Fetch all products i use the method map()
    // -------------------
    // -----Search about Laravel map() Example with Eloquent Collection map()
    // -------------------
    // Laravel map() Example with Eloquent Collection map() is a method provided by Laravel's Eloquent Collection class that allows you to transform each item in the collection using a callback function. It is similar to PHP's native array_map() function but is specifically designed to work with Eloquent collections.
    // -------------------


    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     //
    //     $products = Product::all();
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Products fetched successfully',
    //         'data' => $products,
    //     ], 200);
    // }
    // // -----------------


    // -----------------
    // ---------Laravel map() Example with Eloquent Collection
    // -----------------
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
    //     public function store(StoreProductRequest $request)
    // {
    //     $product = new Product();

    //     $product->name = $request->name;
    //     $product->slug = Str::slug($request->name);
    //     $product->description = $request->description;
    //     $product->price = $request->price;
    //     $product->stock = $request->stock ?? 0;
    //     $product->featured = $request->featured ?? false;
    //     $product->active = $request->active ?? true;
    //     $product->category_id = $request->category_id;

    //     $product->save();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product created successfully',
    //         'data' => [
    //             'id' => $product->id,
    //             'name' => $product->name,
    //             'slug' => $product->slug,
    //             'price' => number_format($product->price, 2),
    //             'stock' => $product->stock,
    //             'featured' => $product->featured,
    //             'active' => $product->active,
    //             'category_id' => $product->category_id,
    //             'created_at' => $product->created_at->toDateTimeString(),
    //         ],
    //     ], 201);
    // }

    // -----------------
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

    // -----------------
    // public function show(string $id)
    // {
    //     //
    //     $product = Product::find($id);
    //     if (!$product) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Product not found',
    //         ], 404);
    //     }
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product fetched successfully',
    //         'data' => $product,
    //     ], 200);
    // }
    // -----------------
    public function show(Product $product)
    {
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
                'featured' => $product->featured,
                'active' => $product->active,
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ],
                // 'image_url' => $product->image ? Storage::url($product->image) : null,
                'created_at' => $product->created_at->toDateTimeString(),
                'updated_at' => $product->updated_at->toDateTimeString(),
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    // // -----------------this is good
    // 
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
    //         'data' => $product,
    //     ], 200);
    // }

    // // -----------------
    // // this function uses mass assignment to update only the fields provided in the request.
    // // -----------------

    // public function update(UpdateProductRequest $request, Product $product)
    // {
    //     $data = $request->only([
    //         'name',
    //         'description',
    //         'price',
    //         'stock',
    //         'featured',
    //         'active',
    //         'category_id',
    //     ]);

    //     if (isset($data['name'])) {
    //         $data['slug'] = Str::slug($data['name']);
    //     }

    //     $product->update($data);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product updated successfully',
    //         'data' => $product->fresh(),
    //     ], 200);
    // }

    // -----------------
    // this function uses the product ID from the route parameter to find and update the product.
    // -----------------

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

        // فقط إذا تم إرسال الاسم، نولّد slug جديد
        if (isset($validatedData['name'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }

        $product->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $product->fresh(),
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
