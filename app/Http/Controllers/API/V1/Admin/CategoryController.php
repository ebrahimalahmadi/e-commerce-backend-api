<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
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
        // //
        // $categories = Category::all();

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Categories retrieved successfully',
        //     'data' => $categories
        // ]);


        //
        $categories = Category::orderBy('created_at', 'DESC')->get();

        return response()->json([
            'status' => true,
            'message' => 'Categories retrieved successfully',
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // //
        // $validated = $request->validated();
        // $validated['slug'] = Str::slug($validated['name']);
        // $category = Category::create($validated);
        // return response()->json([
        //     'status' => true,
        //     'message' => 'Category created successfully',
        //     'data' => $category
        // ], 201);

        //

        // php artisan storage:link
        // $imagePath = null;
        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('categories', 'public');
        // }

        // $category = Category::create([
        //     'name' => $request->name,
        //     'slug' => Str::slug($request->name),
        //     'image' => $imagePath,
        //     'description' => $request->description,
        // ]);

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Category created successfully',
        //     'data' => $category
        // ], 201);




        // // Get the uploaded file
        // $file = $request->file('image');

        // // Generate a unique name for the file
        // $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

        // // Store the file in the 'public/uploads' directory
        // $filePath = $file->storeAs('public/uploads', $fileName);

        // // Return a response
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Image uploaded successfully!',
        //     'file_name' => $fileName,
        //     'file_path' => Storage::url($filePath),
        // ]);




        // $data = $request->validated();

        // if ($request->hasFile('image')) {
        //     $data['image'] = $this->uploadImage($request->file('image'), 'Companies');
        // }

        // Company::create($data);


        // 

        // $imagePath = null;
        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('categories', 'public');
        // }

        // $category = Category::create([
        //     'name' => $request->name,
        //     'slug' => Str::slug($request->name),
        //     'image' => $imagePath,
        //     'description' => $request->description,
        // ]);

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Category created successfully',
        //     'data' => $category,
        //     'image_path' => Storage::url($imagePath)
        // ], 201);



        // ==================


        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Category created successfully',
        //     'category_name' => $category['name'],
        //     'category_slug' => $category['slug'],
        //     'category_description' => $category['description'],
        //     'category_image' =>  Storage::url($category['image']),
        //     // 'image_path' => Storage::url($imagePath)
        // ], 201);

        return response()->json([
            'status' => true,
            'message' => 'Category created successfully',
            'data' => [
                'category name' => $category['name'],
                'category slug' => $category['slug'],
                'category description' => $category['description'],
                'category image' =>  Storage::url($category['image']),
                // 'image_url' =>  Storage::url($category['image']),

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


    public function show(string $id)
    {
        //
        // $category = Category::find(id: $id);
        // $category = Category::where('id', $id)->first();
        $category = Category::where('id', $id)->first();
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Category retrieved successfully',
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $category = Category::where('id', $id)->first();
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }
        $category->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $category = Category::where('id', $id)->first();
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }
        $category->delete();
        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
